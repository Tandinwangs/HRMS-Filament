<?php

namespace App\Filament\Resources\EncashmentResource\Pages;

use App\Filament\Resources\EncashmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEncashments extends ListRecords
{
    protected static string $resource = EncashmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
