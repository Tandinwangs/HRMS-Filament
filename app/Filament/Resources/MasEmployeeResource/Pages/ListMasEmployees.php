<?php

namespace App\Filament\Resources\MasEmployeeResource\Pages;

use App\Filament\Resources\MasEmployeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasEmployees extends ListRecords
{
    protected static string $resource = MasEmployeeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
