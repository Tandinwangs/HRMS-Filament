<?php

namespace App\Filament\Resources\HierarchyResource\Pages;

use App\Filament\Resources\HierarchyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHierarchies extends ListRecords
{
    protected static string $resource = HierarchyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
