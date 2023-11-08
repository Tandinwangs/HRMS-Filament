<?php

namespace App\Filament\Resources\TimeZoneResource\Pages;

use App\Filament\Resources\TimeZoneResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTimeZone extends CreateRecord
{
    protected static string $resource = TimeZoneResource::class;

    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
