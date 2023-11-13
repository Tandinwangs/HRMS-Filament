<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeavePolicy extends Model
{
    use HasFactory, HasUuids;

    protected $fillable=[
        'leave_id',
        'policy_name',
        'policy_description',
        'start_date',
        'end_date',
        'status',
        'is_information_only',
        'created_by',
        'edited_by',
    ];

    public function leavetype()
    {
        return $this->belongsTo(leavetype::class, 'leave_id');
    }


    public function LeavePlan()
    {
        return $this->hasOne(LeavePlan::class, 'policy_id');
    }

    public function LeaveRules()
    {
        return $this->hasMany(LeaveRule::class, 'policy_id');
    }

    public function YearEndProcess()
    {
        return $this->hasOne(LeaveYearendProcess::class, 'policy_id');
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
