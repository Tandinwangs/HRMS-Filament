<?php

namespace App\Filament\Resources\AddVehicleResource\Pages;

use App\Filament\Resources\AddVehicleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAddVehicles extends ListRecords
{
    protected static string $resource = AddVehicleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
