<?php

namespace App\Filament\Resources\EmployeResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\EmployeResource;

class ViewEmploye extends ViewRecord
{
    protected static string $resource = EmployeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    public function mount($record): void
    {
        static::authorizeResourceAccess();

        $this->record = $this->resolveRecord($record);
        abort_unless(
            auth()->user()->hasPermissionTo(RolePermissionSeeder::permissionCreateEmploye),
            403,
            "Permission non accordé"
        );
        $this->fillForm();
    }
   
  
}