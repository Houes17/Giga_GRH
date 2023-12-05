<?php

namespace App\Filament\Resources\EmployeResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Support\Carbon;
use Filament\Support\Exceptions\Halt;
use Database\Seeders\RolePermissionSeeder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EmployeResource;

class CreateEmploye extends CreateRecord
{
    protected static string $resource = EmployeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function mount(): void
    {
        parent::mount();

        abort_unless(
            auth()->user()->hasPermissionTo(RolePermissionSeeder::permissionCreateEmploye),
            403,
            "Permission non accordé"
        );
    }
    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');

            // Date naissance de l'employé
            $dateOfBirth = $data['date naissance'];
            //Convertir la date de naissance
            $dateOfBirth = Carbon::parse($dateOfBirth);
            //Obtenir la date du jour
            $currentDate = Carbon::now();
            // faire la difference d'age
            $age = $currentDate->diffInYears($dateOfBirth);
            abort_if(
                $age < 18,
                403,
                "L'age doit etre supérieur ou égal à 18 ans!"
            );
            $this->record = $this->handleRecordCreation($data);

            $this->form->model($this->record)->saveRelationships();

            $this->callHook('afterCreate');
        } catch (Halt $exception) {
            return;
        }

        $this->getCreatedNotification()?->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->record::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $this->redirect($this->getRedirectUrl());
    }
   
}
