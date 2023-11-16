<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'employee_id',
        'earned_leave_balance',
        'casual_leave_balance'
    ];


    public function employee()
    {
        return $this->belongsTo(MasEmployee::class, 'employee_id');
    }
}
