<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;

class AppliedLeave extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'employee_id',
        'leave_id',
        'start_date',
        'end_date',
        'number_of_days',
        'file_path',
        'status',
        'remark',
        'created_by',
        'edited_by'
    ];
    

    public function employee() {
        return $this->belongsTo(MasEmployee::class, 'employee_id');
    }

    public function leavetype(){
        return $this->belongsTo(LeaveType::class, 'leave_id');
    }

    public function user()
    {
        return $this->belongsTo(MasEmployee::class, 'employee_id');
    }

    public function leaveApproval()
    {
        return $this->hasOne(leaveApproval::class, 'applied_leave_id');
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
