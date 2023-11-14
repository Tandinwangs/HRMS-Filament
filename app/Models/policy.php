<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class policy extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['expense_type_id', 'name', 'description', 'start_date', 'end_date','status'];

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }
    public function rateLimits()
    {
        return $this->hasMany(RateLimit::class, 'policy_id');
    }
    public function rateDefinitions()   
    {
        return $this->hasOne(RateDefinition::class, 'policy_id');
    }
    public function enforcementOptions()
    {
        return $this->hasOne(EnforcementOption::class, 'policy_id');
    }
}
