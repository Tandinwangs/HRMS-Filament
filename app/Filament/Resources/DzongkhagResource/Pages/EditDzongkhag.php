<?php

namespace App\Filament\Resources\DzongkhagResource\Pages;

use App\Filament\Resources\DzongkhagResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDzongkhag extends EditRecord
{
    protected static string $resource = DzongkhagResource::class;

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
