<?php

namespace App\Filament\Resources\ApprovalResource\Pages;

use App\Filament\Resources\ApprovalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovals extends ListRecords
{
    protected static string $resource = ApprovalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
