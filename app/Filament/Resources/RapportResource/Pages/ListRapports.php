<?php

namespace App\Filament\Resources\RapportResource\Pages;

use App\Filament\Resources\RapportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRapports extends ListRecords
{
    protected static string $resource = RapportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
