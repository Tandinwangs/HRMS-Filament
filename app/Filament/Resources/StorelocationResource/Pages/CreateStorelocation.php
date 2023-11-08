<?php

namespace App\Filament\Resources\StorelocationResource\Pages;

use App\Filament\Resources\StorelocationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStorelocation extends CreateRecord
{
    protected static string $resource = StorelocationResource::class;

      
    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
