<?php

namespace App\Filament\Resources\StorelocationResource\Pages;

use App\Filament\Resources\StorelocationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStorelocation extends EditRecord
{
    protected static string $resource = StorelocationResource::class;

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
