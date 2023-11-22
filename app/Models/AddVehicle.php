<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AddVehicle extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'vehicle_type',
        'vehicle_number',
        'vehicle_mileage',
        'status',
 
     ];}
