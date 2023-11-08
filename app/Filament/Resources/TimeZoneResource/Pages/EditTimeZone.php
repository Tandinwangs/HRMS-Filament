<?php

namespace App\Filament\Resources\TimeZoneResource\Pages;

use App\Filament\Resources\TimeZoneResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeZone extends EditRecord
{
    protected static string $resource = TimeZoneResource::class;

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
