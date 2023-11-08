<?php

namespace App\Filament\Resources\StorelocationResource\Pages;

use App\Filament\Resources\StorelocationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStorelocations extends ListRecords
{
    protected static string $resource = StorelocationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
