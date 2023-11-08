<?php

namespace App\Filament\Resources\MasGradeResource\Pages;

use App\Filament\Resources\MasGradeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasGrades extends ListRecords
{
    protected static string $resource = MasGradeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
