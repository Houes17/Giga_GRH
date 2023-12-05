<?php

namespace App\Filament\Resources\RapportResource\Pages;

use App\Filament\Resources\RapportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRapport extends EditRecord
{
    protected static string $resource = RapportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
