<?php

namespace App\Filament\Resources\PolicyResource\Pages;

use App\Filament\Resources\PolicyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPolicies extends ListRecords
{
    protected static string $resource = PolicyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
