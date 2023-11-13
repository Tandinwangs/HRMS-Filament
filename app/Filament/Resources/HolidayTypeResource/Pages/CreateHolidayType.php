<?php

namespace App\Filament\Resources\HolidayTypeResource\Pages;

use App\Filament\Resources\HolidayTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHolidayType extends CreateRecord
{
    protected static string $resource = HolidayTypeResource::class;

    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
