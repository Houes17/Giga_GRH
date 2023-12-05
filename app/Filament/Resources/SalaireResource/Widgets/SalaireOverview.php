<?php

namespace App\Filament\Resources\SalaireResource\Widgets;

use App\Models\Salaire;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class SalaireOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total des salaires payés', Salaire::where('etat', '=', Salaire::Payé)->count()),
            Card::make('Total des salaires non payés', Salaire::where('etat', '=', Salaire::Non_Payé)->count()),
        ];
    }
}
