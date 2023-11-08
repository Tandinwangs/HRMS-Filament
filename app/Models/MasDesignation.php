<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasDesignation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "id",
        "name",
        "status",
        "created_by",
        "edited_by",
    ];
}
