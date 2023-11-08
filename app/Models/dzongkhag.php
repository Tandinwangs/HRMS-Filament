<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dzongkhag extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'status',
        'region_id',   
        'country_id',  
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
