<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class storelocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'dzongkhag_id', 
        'timezone_id',
    ];

    public function dzongkhag()
    {
        return $this->belongsTo(Dzongkhag::class);
    }

    public function timezone(){
        return $this->belongsTo(TimeZone::class);
    }
}
