<?php

namespace App\Filament\Resources\FuelResource\Pages;

use App\Filament\Resources\FuelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFuels extends ListRecords
{
    protected static string $resource = FuelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
