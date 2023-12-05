<?php

namespace App\Filament\Resources\SalaireResource\Pages;

use App\Models\Employe;
use App\Models\Faction;
use App\Models\Salaire;
use App\Models\Presence;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\DB;
use Database\Seeders\FactionSeeder;
use Filament\Support\Exceptions\Halt;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SalaireResource;

class CreateSalaire extends CreateRecord
{
    protected static string $resource = SalaireResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //Récuperation de l'id de l'employé selectionné
        $employe = Employe::find($data['employe_id']);
        //Recuperation du contrat de l'employé
        $typecontrat = $employe->contrat;
        //Récuperation du salaire de l'employé
        $baseSalary = $employe->salaire/30;
        //Récuperation du mois du formulaire
        $month = $data['mois'];
        //Récuperation de l'année du formulaire
        $year = $data['année'];
        //-------------------------------deb de nombre dheure travaillé--------------------------------//
        $nomFactions = [FactionSeeder::REPOS, FactionSeeder::ABSENCE, FactionSeeder::REPOS_24];

        $factionIds = DB::table('factions')
            ->whereIn('nom', $nomFactions)
            ->pluck('id');
        
        $nbre_heures_travail = Presence::join('factions', 'presences.faction_id', '=', 'factions.id')
            ->where('presences.employe_id', $data['employe_id'])
            ->whereMonth('presences.date', $month)
            ->whereYear('presences.date', $year)
            ->whereNotIn('presences.faction_id', $factionIds)
            ->sum('factions.nbre_heure');
        //-------------------------------Fin de nombre dheure travaillé--------------------------------//
        

        //-------------------------------deb de nombre dheure repos--------------------------------//
        $nomFactions2 = [FactionSeeder::REPOS, FactionSeeder::REPOS_24];

        $factionIds2 = DB::table('factions')
            ->whereIn('nom', $nomFactions2)
            ->pluck('id');
        
        $nbre_heure_repos = Presence::join('factions', 'presences.faction_id', '=', 'factions.id')
            ->where('presences.employe_id', $data['employe_id'])
            ->whereMonth('presences.date', $month)
            ->whereYear('presences.date', $year)
            ->whereIn('presences.faction_id', $factionIds2)
            ->sum('factions.nbre_heure');
         //-------------------------------Fin de nombre dheure repos--------------------------------//


         //-------------------------------deb nbre_absence--------------------------------//
        $nomFactions3 = [FactionSeeder::ABSENCE];

        $factionIds3 = DB::table('factions')
            ->whereIn('nom', $nomFactions3)
            ->pluck('id');
        
        $nbre_absence = Presence::join('factions', 'presences.faction_id', '=', 'factions.id')
            ->where('presences.employe_id', $data['employe_id'])
            ->whereMonth('presences.date', $month)
            ->whereYear('presences.date', $year)
            ->where('presences.faction_id', $factionIds3)
            ->count();
         //-------------------------------Fin nbre_absence--------------------------------//

         $presences = Presence::where('employe_id', $data['employe_id'])
         ->whereMonth('date', $month)
         ->whereYear('date', $year)
         ->get(['salairepresence']);
        
         $salaireTotal = $presences->sum('salairepresence');
         
         
        //Calcul du salaire total
        $totalsalary = $salaireTotal; 
        
       
        $tot = $totalsalary * 0.96;
        

        if ($typecontrat === Employe::CDD) {
            $data['salaire total'] = round($tot, 2);   
         }
         else {
            $data['salaire total'] = round($totalsalary, 2);
         }        

        $data['nbre_heures_travail'] = $nbre_heures_travail;
        $data['nbre_heure_repos'] = $nbre_heure_repos;
        $data['nbre_absence'] = $nbre_absence;

        return $data;
    }

     public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');
            
            $month = $data['mois'];
            $year = $data['année'];
            abort_if(
                Salaire::where("employe_id", $data["employe_id"])
                ->where("année", $year)
                ->where("mois", $month)
                ->where("etat", Salaire::Payé)
                ->exists(),
                403,
                "Le salaire de ce mois a deja été calculé et payé"
            );

            $this->record = $this->handleRecordCreation($data);

            $this->form->model($this->record)->saveRelationships();

            $this->callHook('afterCreate');
        } catch (Halt $exception) {
            return;
        }

        $this->getCreatedNotification()?->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->record::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $this->redirect($this->getRedirectUrl());
    }
}
