<?php

namespace App\Filament\Widgets;

use App\Models\Site;
use App\Models\Employe;
use App\Models\Salaire;
use App\Models\Departement;
use Database\Seeders\RolePermissionSeeder;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total des employés', Employe::all()->count())
            ,
            Card::make('Total des employés Actifs', Employe::where('statut', Employe::EN_ACTIVITE)->count())
            ,
            Card::make('Total des employés Inactifs', Employe::where('statut', Employe::NON_ACTIVITE)->count())
            ,
            Card::make('Total des fonctions ', Departement::all()->count())
            ,
            Card::make('Total des sites', Site::all()->count())
            ,
            //Card::make('Montant des salaires payés', Salaire::where('etat', '=', Salaire::Payé)->sum('salaire total').' '.'fcfa'),
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole([
            RolePermissionSeeder::RoleAdmin, 
            RolePermissionSeeder::RoleSuperAdmin,
        ]);
    }
  
   
}
