<?php

namespace App\Filament\Resources\HolidayTypeResource\Pages;

use App\Filament\Resources\HolidayTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHolidayTypes extends ListRecords
{
    protected static string $resource = HolidayTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
