<?php

namespace App\Filament\Resources\ApplyAdvanceResource\Pages;

use App\Filament\Resources\ApplyAdvanceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplyAdvances extends ListRecords
{
    protected static string $resource = ApplyAdvanceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
