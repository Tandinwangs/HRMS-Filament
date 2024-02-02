<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;
use Illuminate\Support\Facades\Auth;


class EncashmentApprovalCondition extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'encashment_rule_id',
        'approval_type',
        'hierarchy_id',
        'employee_id',
        'MaxLevel',
        'created_by', 
        'updated_by'
    ];

    // Define the relationship to approval_rules
    public function leaveEncashmentApprovalRule()
    {
        return $this->belongsTo(EncashmentApprovalRule::class, 'encashment_rule_id ');
    }

    public function hierarchy()
    {
        return $this->belongsTo(Hierarchy::class, 'hierarchy_id');
    }

    public function employee()
    {
        return $this->belongsTo(MasEmployee::class, 'employee_id');
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
