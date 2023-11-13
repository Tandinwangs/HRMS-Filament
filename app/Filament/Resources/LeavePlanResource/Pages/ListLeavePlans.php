<?php

namespace App\Filament\Resources\LeavePlanResource\Pages;

use App\Filament\Resources\LeavePlanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeavePlans extends ListRecords
{
    protected static string $resource = LeavePlanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
