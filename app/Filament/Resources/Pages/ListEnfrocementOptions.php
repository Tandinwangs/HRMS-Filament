<?php

namespace App\Filament\Resources\EnfrocementOptionResource\Pages;

use App\Filament\Resources\EnfrocementOptionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnfrocementOptions extends ListRecords
{
    protected static string $resource = EnfrocementOptionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
