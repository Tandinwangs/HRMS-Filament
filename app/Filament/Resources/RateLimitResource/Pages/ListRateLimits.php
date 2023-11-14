<?php

namespace App\Filament\Resources\RateLimitResource\Pages;

use App\Filament\Resources\RateLimitResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRateLimits extends ListRecords
{
    protected static string $resource = RateLimitResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
