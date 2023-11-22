<?php

namespace App\Filament\Resources\AddVehicleResource\Pages;

use App\Filament\Resources\AddVehicleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddVehicle extends EditRecord
{
    protected static string $resource = AddVehicleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
