<?php

namespace App\Filament\Resources\VilleResource\Pages;

use App\Filament\Resources\VilleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVille extends CreateRecord
{
    protected static string $resource = VilleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
