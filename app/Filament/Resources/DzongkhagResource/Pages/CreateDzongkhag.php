<?php

namespace App\Filament\Resources\DzongkhagResource\Pages;

use App\Filament\Resources\DzongkhagResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDzongkhag extends CreateRecord
{
    protected static string $resource = DzongkhagResource::class;

    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
