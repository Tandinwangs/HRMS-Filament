<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveFormula extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'approval_rule_id',
        'condition',
        'field',
        'operator',
        'value',
        'employee_id',
        'created_by', 
        'updated_by'
    ];

    public function employee() {
        return $this->belongsTo(MasEmployee::class, 'employee_id');
    }

    public function approvalRule()
    {
        return $this->belongsTo(LeaveApprovalRule::class, 'approval_rule_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::saving(function ($model) {
            if (Auth::check()) {
                $model->edited_by = Auth::id();
            }
        });
    }

}
