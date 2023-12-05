<?php

namespace App\Filament\Resources\SalaireResource\Pages;

use App\Filament\Resources\SalaireResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalaire extends EditRecord
{
    protected static string $resource = SalaireResource::class;

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
