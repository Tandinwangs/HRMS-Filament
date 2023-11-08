<?php

namespace App\Filament\Resources\MasDesignationResource\Pages;

use App\Filament\Resources\MasDesignationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasDesignations extends ListRecords
{
    protected static string $resource = MasDesignationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
