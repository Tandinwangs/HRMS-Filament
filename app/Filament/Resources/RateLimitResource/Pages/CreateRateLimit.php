<?php

namespace App\Filament\Resources\RateLimitResource\Pages;

use App\Filament\Resources\RateLimitResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRateLimit extends CreateRecord
{
    protected static string $resource = RateLimitResource::class;
}
