<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;

class LeavePlan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'policy_id',
        'attachment_required',
        'gender',
        'leave_year',
        'credit_frequency',
        'credit',
        'include_public_holidays',
        'include_weekends',
        'can_be_clubbed_with_el',
        'can_be_clubbed_with_cl',
        'can_be_half_day',
        'probation_period',
        'regular_period',
        'contract_period',
        'notice_period',
        'created_by',
        'edited_by',
    ];

    public function policy()
    {
        return $this->belongsTo(LeavePolicy::class, 'policy_id');
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
