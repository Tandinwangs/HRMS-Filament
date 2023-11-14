<?php

namespace App\Filament\Resources\AdvanceTypeResource\Pages;

use App\Filament\Resources\AdvanceTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvanceTypes extends ListRecords
{
    protected static string $resource = AdvanceTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
