<?php

namespace App\Filament\Resources\HierarchyResource\Pages;

use App\Filament\Resources\HierarchyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHierarchy extends EditRecord
{
    protected static string $resource = HierarchyResource::class;

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
