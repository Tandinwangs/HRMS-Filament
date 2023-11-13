<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;

class LeaveRule extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "policy_id",
        "grade_step_id",
        "duration",
        "uom",
        "start_date",
        "end_date",
        "islossofpay",
        "employee_type",
        "status",
        'created_by',
        'edited_by',
    ];

    public function policy()
    {
        return $this->belongsTo(LeavePolicy::class, 'policy_id');
    }

    public function gradeStep() {
        return $this->belongsTo(MasGradeStep::class, 'grade_step_id');
    }

    public function leaves()
    {
        return $this->hasMany(LeaveType::class); 
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
