<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DSAManual extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'user_id',
        'dsa_settlement_id',
        'from_date',
        'from_location',
        'to_date',
        'to_location',
        'total_days',
        'da',
        'ta',
        'total_amount',
        'remarks',
        
      
    ];
    public function user()
    {
        return $this->belongsTo(MasEmployee::class,'user_id');
    } 
    public function dsasettlement()
    {
        return $this->belongsTo(DSASettlement::class,'dsa_settlement_id');
    }

}
