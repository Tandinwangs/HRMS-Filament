<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AdvanceType extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'name',
        'expense_type_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }
    public function advancetype()
    {
        return $this->hasMany(AdvanceApplication::class, 'advance_type_id');
    }
}
