<?php

namespace App\Filament\Resources\RapportResource\Pages;

use App\Models\Site;
use App\Models\Salaire;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RapportResource;

class CreateRapport extends CreateRecord
{
    protected static string $resource = RapportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $month = $data['mois'];
        $year = $data['année'];
        $site = Site::find($data['site_id']);

        
        $employes = $site->employes()->with('salaires')->get();

        //recuperation des sites totals des employés sur le site sélectionné
        $employee =  $site->employes();
        
         $sits = $employee->whereHas('departement', function ($query) {
            $query->where('id', 3);
        })
        ->withCount('sites')
        ->get();
        $nombreSites = 0;
        foreach ($sits as $sit) {
            
            $nombreSites = $sit->sites_count;
            
        }

        
        //calcul du salaire total des employés se trouvant sur le site
        $salaireEmployé = 0;
        foreach ($employes as $employe) {
            $salaires = $employe->salaires->where('mois', $month)->where('année', $year)->where("etat", Salaire::Payé);
           
            if ($employe->departement_id === 3) {
                $salaireEmployé += $salaires->sum('salaire total')/$nombreSites;
            } else {
                $salaireEmployé += $salaires->sum('salaire total');
            } 
        }
    
        //calcul des dotations totales
        $dots = $site->employes()->with('dotations')->get();

        $totaldot= 0;

        foreach ($dots as $dot) {
            $dotations = $dot->dotations;
            if ($dot->departement_id === 3) {
                $totaldot += $dotations->where('month', $month)->where('year', $year)->sum('montant')/$nombreSites;
            } else {
                $totaldot += $dotations->sum('montant')/6;
            }  
        }
       
        $data['montantdotation'] = round($totaldot, 2);

        $data['totalsalaire'] = round($salaireEmployé, 2);

        //total des dépenses au niveau du site
        $valeur = $totaldot + $salaireEmployé;
        $data['depensetotal'] =  round($valeur, 2);

        //nombre total d'employé sur le site
        $nombreEmployes = $site->employes()->count();
        
        $data['totalemployé'] = $nombreEmployes;

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}
