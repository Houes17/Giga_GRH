<?php

namespace App\Filament\Resources\DotationResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\DotationResource;

class EditDotation extends EditRecord
{
    protected static string $resource = DotationResource::class;

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
