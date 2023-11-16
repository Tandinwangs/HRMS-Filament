<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;

class LeaveType extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = [
        'name',
        'short_code',
        'status',
        'created_by',
        'edited_by',
    ];


    public function LeavePolicy()
    {
        return $this->hasOne(LeavePolicy::class, 'leave_id');
    }

    public function scopeFilteredByGender($query, $userGender, $leavePlanGender)
    {
        return $query->where(function ($query) use ($userGender, $leavePlanGender) {
            // Include leave types where gender restriction is 'A' or matches the user's gender
            $query->where('gender', '=', 'A')
                ->orWhere('gender', '=', $userGender)
                ->orWhere('gender', '=', $leavePlanGender);
        });
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
