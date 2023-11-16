<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class vehicle extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'vehicle_number',
        'vehicle_mileage',
        'status',
 
     ];
}
