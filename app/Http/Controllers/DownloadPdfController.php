<?php

namespace App\Http\Controllers;

use TCPDF;
use Carbon\Carbon;
use App\Models\Employe;
use App\Models\Salaire;
use App\Models\Presence;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Database\Seeders\FactionSeeder;

class DownloadPdfController extends Controller
{
    public function download(Salaire $record){
        //Récuperation des informations du modele Employe
        $id = $record['employe_id'];
        $employe = Employe::find($id);
        $nom = $employe->nomprenoms;
        $matricule = $employe->matricule;
       
        $typecontrat = $employe->contrat;
      
        //Récuperation des informations du modele Departement 
        $departement = $employe->departement->nom;
        //Récupération des informations de présence
        $nbre_heures_travail = $record['nbre_heures_travail'];
        $nbre_heure_repos = $record['nbre_heure_repos'];
        $nbre_absence = $record['nbre_absence'];
        //Recuperation des informations sur le salaire
        $month = $record['mois'];
        $year = $record['année'];
        $totalsalary = $record['salaire total'];
        $date = Carbon::now();
        //-------------------------------Salaire CNSS---------------------------------------------//
            if ($typecontrat === Employe::CDD) {
                $cnss = '4%';

                $montantcnss = $totalsalary * 4/100;
            }
            else {
                $cnss = '0%';
                $montantcnss = 0;
            }

            
       //-----------------------------------Fin salaire CNSS------------------------------------------//


        //-------------------------------deb salaire travaillé--------------------------------//
        $nomFactions = [FactionSeeder::REPOS, FactionSeeder::ABSENCE, FactionSeeder::REPOS_24];

        $factionIds = DB::table('factions')
            ->whereIn('nom', $nomFactions)
            ->pluck('id');
        
        $salairetravail = Presence::join('factions', 'presences.faction_id', '=', 'factions.id')
            ->where('presences.employe_id', $record['employe_id'])
            ->whereMonth('presences.date', $month)
            ->whereYear('presences.date', $year)
            ->whereNotIn('presences.faction_id', $factionIds)
            ->sum('presences.salairepresence');
       
        //-------------------------------Fin salaire travaillé--------------------------------//

         //-------------------------------deb salaire repos--------------------------------//
         $nomFactions2 = [FactionSeeder::REPOS, FactionSeeder::REPOS_24];

         $factionIds2 = DB::table('factions')
             ->whereIn('nom', $nomFactions2)
             ->pluck('id');
         
         $salaire_repos = Presence::join('factions', 'presences.faction_id', '=', 'factions.id')
             ->where('presences.employe_id', $record['employe_id'])
             ->whereMonth('presences.date', $month)
             ->whereYear('presences.date', $year)
             ->whereIn('presences.faction_id', $factionIds2)
             ->sum('presences.salairepresence');
             
          //-------------------------------Fin salaire repos--------------------------------//
        

          //-------------------------------deb nbre_absence--------------------------------//
        $nomFactions3 = [FactionSeeder::ABSENCE];

        $factionIds3 = DB::table('factions')
            ->whereIn('nom', $nomFactions3)
            ->pluck('id');
        
        $salaire_absence = Presence::join('factions', 'presences.faction_id', '=', 'factions.id')
            ->where('presences.employe_id', $record['employe_id'])
            ->whereMonth('presences.date', $month)
            ->whereYear('presences.date', $year)
            ->where('presences.faction_id', $factionIds3)
            ->sum('presences.salairepresence');
         //-------------------------------Fin nbre_absence--------------------------------//
        
        
         // Créez une nouvelle instance de TCPDF
         $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
  
         // Définissez les métadonnées du PDF
         $pdf->SetCreator('EasyRH');
         $pdf->SetAuthor('HOUESSOU Ivan');
         $pdf->SetTitle('Informations sur le salaire de l\'employé');
         $pdf->SetSubject('Informations sur le salaire de l\'employé');
         $pdf->SetKeywords('salaire, employé, PDF');
  
         // Ajoutez une nouvelle page
         $pdf->AddPage();

         $html = '<head>
         <style>
           body {
             font-family: Arial, sans-serif;
             height: 100%;
           }
           .employe-info {
               margin-bottom: 20px;
           }
       
           .myDIV {
               height:300px;
               background-color:#FFFFFF;
               color: #0000ff;
           }

           .paystub-total td{
               font-size: 24px;
               font-weight: bold;
               color: #f9f9f9;
           }
       
           .paystub {
               border: 2px dotted blue;
               padding: 20px;
               max-width: 500px;
               margin: 0 auto;
               background-color: #f9f9f9;
           }
       
           .paystub-header {
             text-align: center;
             margin-bottom: 20px;
            
           }
       
           .paystub-header h1 {
               
             font-size: 24px;
             color: #333;
             margin: 25px;
             border: 2px solid black;
             background-color: yellow;
           }
       
           .paystub-table {
             width: 100%;
             border-collapse: collapse;
             margin-bottom: 20px;
             background-color: #f9f9f9;
             
           }
       
           .paystub-table th,
           .paystub-table td {
             padding: 10px;
             text-align: left;
             border-bottom: 1px solid #ccc;
           }
       
           .paystub-table th {
             background-color: yellow;
           }
       
           .company-name {
               text-align: center;
               font-size: 24px;
               font-weight: bold;
               margin: 20px;
               padding: 0;
               color: black;
           }
           .paystub-total {
             text-align: right;
             font-weight: bold;
           }
       
           .paystub-footer {
           margin: 25px;
           margin-left: 0;
             text-align: left;
           }
           .signature {
           margin: 100px;
           margin-right: 0;
           text-align: center;
           }
         </style>

       </head>
       <body>
         <div class="paystub">
       
             <div id="myDIV">
           <h1 class="company-name">EASY RH MANAGE</h1>
           </div>
           <div class="paystub-header">
             <h1>Fiche de paie</h1>
           </div>
       
           <div class="employe-info">
             <h4>Informations de l\'employé :</h4>
             <p><strong>Matricule :</strong> ' . $matricule . ' </p>
             <p><strong>Nom et Prénoms:</strong> ' . $nom . '</p>
             <p><strong>Département :</strong> ' . $departement . '</p>
             <p><strong>Contrat :</strong> ' . $typecontrat . '</p>
             <p><strong>Salaire Net :</strong> ' . round($totalsalary, 2) . '</p>
           </div>
       
           <table class="paystub-table">
             <tr>
               <th>Date</th>
               <th>Libellé</th>
               <th>Valeur</th>
               <th>Salaire</th>
               <th>Montant</th>
             </tr>
             <tr>
               <td>' . $month . '/' . $year . '</td>
               <td>Nbre heures travaillées</td>
               <td>' . $nbre_heures_travail . '</td>
               <td>'  . round($salairetravail, 2) .  ' fcfa</td>
               <td>'  . round($salairetravail, 2) .  ' fcfa</td>
             </tr>

             <tr>
                <td>' . $month . '/' . $year . '</td>
                <td>Nbre heures repos</td>
                <td>' . $nbre_heure_repos . '</td>
                <td>'  . round($salaire_repos, 2) .  ' fcfa</td>
                <td>'  . round($salaire_repos, 2) .  ' fcfa</td>
             </tr>

             <tr>
                <td>' . $month . '/' . $year . '</td>
                <td>Nbre jours absences</td>
                <td>' . $nbre_absence . '</td>
                <td> 0 fcfa </td>
                <td> 0 fcfa </td>
             </tr>

             <tr>
                <td>' . $month . '/' . $year . '</td>
                <td>CNSS</td>
                <td>' . $cnss . '</td>
                <td>'  . round($montantcnss, 2) .  ' fcfa</td>
                <td>'  . round($montantcnss, 2) .  ' fcfa</td>
             </tr>

             <tr>
               <td>' . $month . '/' . $year . '</td>
               <td  class="paystub-total">Total</td>
               <td>' . $nbre_heure_repos + $nbre_heures_travail . ' </td>
               <td>'  . round($totalsalary, 2) .  ' fcfa</td>
               <td class="paystub-total td">' . round($totalsalary, 2) . ' fcfa</td>
             </tr>
           </table>
       
           <div class="paystub-footer">
             <p>Date d\'émission : '.$date.'</p> <p class="signature">Signature :</p>
           </div>
           
         </div>
       </body>';
        
       
         // Écrivez le contenu HTML dans le PDF
         $pdf->writeHTML($html, true, false, true, false, '');

       // Générez le contenu du PDF et enregistrez-le sur le serveur
         $pdf->Output($nom .'  '. $month .'-'. $year . ".pdf", 'D');
  
    }
}
