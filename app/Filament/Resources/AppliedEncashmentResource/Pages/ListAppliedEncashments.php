<?php

namespace App\Filament\Resources\AppliedEncashmentResource\Pages;

use App\Filament\Resources\AppliedEncashmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppliedEncashments extends ListRecords
{
    protected static string $resource = AppliedEncashmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
