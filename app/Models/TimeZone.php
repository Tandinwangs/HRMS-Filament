<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'timezone',
        'name',
        'country_id',
        'status'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
