<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;

class LeaveYearendProcess extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "policy_id",
        "allow_carryover",
        "carryover_limit",
        "payat_yearend",
        "min_balance",
        "max_balance",
        "carryforward_toEL",
        "carryforward_toEL_limit",
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
