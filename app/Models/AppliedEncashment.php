<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AppliedEncashment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'employee_id',
        'number_of_days',
        'amount',
        'status',
        'created_by',
        'edited_by'
];

    public function employee()
    {
        return $this->belongsTo(MasEmployee::class);
    }

    public function encashmentApproval()
    {
        return $this->hasOne(EncashmentApproval::class, 'applied_encashment_id');
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

        static::created(function ($leave) {
            // Set the casual_leave_balance based on the matched LeaveRule
            $leave->leaveApproval()->create([
                'applied_leave_id' => $leave->id
            ]);
        });
    }
}
