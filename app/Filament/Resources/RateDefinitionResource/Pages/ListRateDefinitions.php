<?php

namespace App\Filament\Resources\RateDefinitionResource\Pages;

use App\Filament\Resources\RateDefinitionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRateDefinitions extends ListRecords
{
    protected static string $resource = RateDefinitionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
