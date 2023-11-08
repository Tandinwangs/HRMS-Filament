<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class country extends Model
{
    use HasFactory;

    protected $fillable =[
        'code',
        'name',
        'status'
    ];

    public function regions(){
        return $this->hasMany(Region::class);
    }

    public function dzongkhags(){
        return $this->hasMany(Dzongkhag::class);
    }
}
