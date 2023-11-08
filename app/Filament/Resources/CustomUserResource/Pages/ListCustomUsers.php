<?php

namespace App\Filament\Resources\CustomUserResource\Pages;

use App\Filament\Resources\CustomUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomUsers extends ListRecords
{
    protected static string $resource = CustomUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
