<?php

namespace App\Filament\Resources\SalaireResource\Pages;

use App\Filament\Resources\SalaireResource;
use App\Filament\Resources\SalaireResource\Widgets\SalaireOverview;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalaires extends ListRecords
{
    protected static string $resource = SalaireResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            SalaireOverview::class,
        ];
    }
}
