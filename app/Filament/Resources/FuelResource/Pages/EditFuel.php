<?php

namespace App\Filament\Resources\FuelResource\Pages;

use App\Filament\Resources\FuelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFuel extends EditRecord
{
    protected static string $resource = FuelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
