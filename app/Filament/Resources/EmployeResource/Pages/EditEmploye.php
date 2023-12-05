<?php

namespace App\Filament\Resources\EmployeResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\EmployeResource;

class EditEmploye extends EditRecord
{
    protected static string $resource = EmployeResource::class;

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
            auth()->user()->hasPermissionTo(RolePermissionSeeder::permissionEditEmploye),
            403,
            "Permission non accordé"
        );
    }
    

  

   
}
