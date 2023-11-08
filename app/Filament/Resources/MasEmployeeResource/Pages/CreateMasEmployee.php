<?php

namespace App\Filament\Resources\MasEmployeeResource\Pages;

use App\Filament\Resources\MasEmployeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasEmployee extends CreateRecord
{
    protected static string $resource = MasEmployeeResource::class;

    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
