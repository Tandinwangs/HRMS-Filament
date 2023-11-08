<?php

namespace App\Filament\Resources\MasEmployeeResource\Pages;

use App\Filament\Resources\MasEmployeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasEmployee extends EditRecord
{
    protected static string $resource = MasEmployeeResource::class;

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
