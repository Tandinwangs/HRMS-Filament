<?php

namespace App\Filament\Resources\DzongkhagResource\Pages;

use App\Filament\Resources\DzongkhagResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDzongkhags extends ListRecords
{
    protected static string $resource = DzongkhagResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
