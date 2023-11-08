<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'earned_leave_balance',
        'casual_leave_balance'
    ];


    public function user()
    {
        return $this->belongsTo(MasEmployee::class, 'user_id');
    }
}
