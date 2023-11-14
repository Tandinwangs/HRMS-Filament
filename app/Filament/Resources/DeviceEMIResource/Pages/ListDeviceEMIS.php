<?php

namespace App\Filament\Resources\DeviceEMIResource\Pages;

use App\Filament\Resources\DeviceEMIResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeviceEMIS extends ListRecords
{
    protected static string $resource = DeviceEMIResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
