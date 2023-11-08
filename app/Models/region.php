<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class region extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'status',
        'country_id', // Don't forget to include the foreign key field
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function dzongkhags(){
        return $this->hasMany(Dzongkhag::class);
    }

    public function holidays()
    {
        return $this->belongsToMany(Holiday::class, 'region_holiday', 'region_id', 'holiday_id');
    }
}
