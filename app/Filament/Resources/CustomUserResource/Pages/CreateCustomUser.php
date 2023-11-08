<?php

namespace App\Filament\Resources\CustomUserResource\Pages;

use App\Filament\Resources\CustomUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomUser extends CreateRecord
{
    protected static string $resource = CustomUserResource::class;
}
