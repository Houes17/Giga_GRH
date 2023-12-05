<?php

namespace App\Filament\Resources\FactionResource\Pages;

use App\Filament\Resources\FactionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFactions extends ListRecords
{
    protected static string $resource = FactionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
