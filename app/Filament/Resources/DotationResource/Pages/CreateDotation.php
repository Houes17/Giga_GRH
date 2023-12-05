<?php

namespace App\Filament\Resources\DotationResource\Pages;

use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DotationResource;

class CreateDotation extends CreateRecord
{
    protected static string $resource = DotationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $date = $data['date'];
        if($date){
            $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
            $data['month'] = $carbonDate->format('m');
            $data['year'] =  $carbonDate->format('Y');
        }
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
