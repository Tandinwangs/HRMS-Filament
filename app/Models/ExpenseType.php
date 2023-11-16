<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory, HasUuids;
     protected $fillable = ['name', 'start_date', 'end_date', 'status'];


    public function rateLimits()
    {
        return $this->hasManyThrough(RateLimit::class, Policy::class);
    }

    public function policies()
    {
        return $this->hasOne(Policy::class);
    }
    public function dsasettlement()
    {
        return $this->hasMany(DSASettlement::class);
    }


}
