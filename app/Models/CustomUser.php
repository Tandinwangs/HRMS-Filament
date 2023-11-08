<?php

namespace App\Models;

use Chiiya\FilamentAccessControl\Models\FilamentUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Chiiya\FilamentAccessControl\Database\Factories\FilamentUserFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomUser extends FilamentUser
{
    use HasRoles;
    use Notifiable;

    protected $table = 'custom_users';

    protected $fillable = [
        'id',
        'email',
        'password',
        'name', 
    ];

}
