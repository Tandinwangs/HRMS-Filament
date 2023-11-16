<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class TransferClaim extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'employee_id',
        'date',
        'designation',
        'department',
        'basic_pay',
        'transfer_claim_type',
        'current_location',
        'new_location',
        'claim_amount',
        'distance_km',
        'level1',
        'level2',
        'level3',
        'status',
        'expense_type_id',
        'attachment',
        'remark',

    ];
    public function user()
    {
        return $this->belongsTo(MasEmployee::class,'user_id');
    }
    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class,'expense_type_id');
    }
    public function pay()
    {
        return $this->belongsTo(MasGradeStep::class,'basic_pay');
    }
}
