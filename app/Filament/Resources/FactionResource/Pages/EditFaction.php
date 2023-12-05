<?php

namespace App\Filament\Resources\FactionResource\Pages;

use App\Filament\Resources\FactionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaction extends EditRecord
{
    protected static string $resource = FactionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
