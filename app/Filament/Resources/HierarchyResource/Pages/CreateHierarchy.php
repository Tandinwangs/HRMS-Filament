<?php

namespace App\Filament\Resources\HierarchyResource\Pages;

use App\Filament\Resources\HierarchyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHierarchy extends CreateRecord
{
    protected static string $resource = HierarchyResource::class;

    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
