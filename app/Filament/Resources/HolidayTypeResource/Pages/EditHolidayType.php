<?php

namespace App\Filament\Resources\HolidayTypeResource\Pages;

use App\Filament\Resources\HolidayTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHolidayType extends EditRecord
{
    protected static string $resource = HolidayTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
