<?php

namespace App\Filament\Resources\PresenceResource\Pages;

use App\Models\Site;
use App\Models\User;
use App\Models\Employe;
use App\Models\Presence;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Database\Seeders\RolePermissionSeeder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PresenceResource;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class CreatePresence extends CreateRecord
{
    protected static string $resource = PresenceResource::class;

    public function mount(): void
    {
        parent::mount();

        abort_unless(
            auth()->user()->hasPermissionTo(RolePermissionSeeder::permissionCreatePresence),
            403,
            "Permission non accordé"
        );
    }

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('afterValidate');

        $data = $this->mutateFormDataBeforeCreate($data);

        $this->callHook('beforeCreate');

        $date = $data['date'];
        $manipdate = \Carbon\Carbon::parse($date); 
        $dayInmonth = $manipdate->daysInMonth;

        $siteId = $data['site_id'];

        $salaire = Site::where('id', $siteId)->value('salaire');
        $salairejournalier = $salaire/$dayInmonth;
        $salaireheure = $salairejournalier/12;

        $factionId = $data['faction_id'];
        $nombreHeures = \App\Models\Faction::where('id', $factionId)->value('nbre_heure');
        
        $salairepresence = $salaireheure * $nombreHeures;
        
        $data['salairepresence'] = $salairepresence;

        /*$employeId = $data['employe_id'];

        // Requête pour récupérer les présences de l'employé
        $presences = Presence::where('employe_id', $employeId)->get(['salairepresence']);
        
        $salaireTotal = $presences->sum('salairepresence');
        
        dd($salaireTotal);
        */
        
        //dd($data['salairepresence']);
        /*$hasPendingConfirmation = Presence::where("employe_id", $data["employe_id"])
            ->where("date", $date)
            ->exists()
        ;

        if($hasPendingConfirmation) {
            Notification::make()
                ->title("La présence de ce utilisateur a déja été mentionné pour cette date")
                ->danger()
                ->send();
            
            return;
        }
        
        abort_if(
            Presence::where("employe_id", $data["employe_id"])
            ->where("date", $date)
            ->exists(),
            403,
            "La présence de ce utilisateur a déja été mentionné pour cette date"
        );
        */


        $this->record = $this->handleRecordCreation($data);

        $this->form->model($this->record)->saveRelationships();

        $this->callHook('afterCreate');

        $employeId = $data['employe_id'];
        $employe = Employe::find($employeId);
        $nom = $employe->nomprenoms;

        $siteId = $data['site_id'];
        $site = Site::find($siteId);
        $recipients = User::whereHas('roles', function ($query) {
            $query->whereIn('name', [RolePermissionSeeder::RoleAdmin, RolePermissionSeeder::RoleSuperAdmin, RolePermissionSeeder::RoleComptable]);
        })->get();
 
        Notification::make()
            ->title('Présence Journalière')
            ->body(Auth::user()->name . ' vient de marquer la présence de l\'agent ' . $nom .' sur le site '. $site->description)
            ->sendToDatabase($recipients);

        if (filled($this->getCreatedNotificationMessage())) {
            Notification::make()
                ->title($this->getCreatedNotificationMessage())
                ->success()
                ->send();
        }

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->record::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $this->redirect($this->getRedirectUrl());
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }



}
