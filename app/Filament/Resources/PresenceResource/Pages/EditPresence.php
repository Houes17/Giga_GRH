<?php

namespace App\Filament\Resources\PresenceResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\PresenceResource;

class EditPresence extends EditRecord
{
    protected static string $resource = PresenceResource::class;

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
    protected function authorizeAccess(): void
    {
        static::authorizeResourceAccess();

        abort_unless(
            auth()->user()->hasPermissionTo(RolePermissionSeeder::permissionEditPresence),
            403,
            "Permission non accordé"
        );
    }
}
