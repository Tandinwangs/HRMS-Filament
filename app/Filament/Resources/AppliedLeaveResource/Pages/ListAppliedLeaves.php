<?php

namespace App\Filament\Resources\AppliedLeaveResource\Pages;

use App\Filament\Resources\AppliedLeaveResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppliedLeaves extends ListRecords
{
    protected static string $resource = AppliedLeaveResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
