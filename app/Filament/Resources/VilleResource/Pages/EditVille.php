<?php

namespace App\Filament\Resources\VilleResource\Pages;

use App\Filament\Resources\VilleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVille extends EditRecord
{
    protected static string $resource = VilleResource::class;

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
