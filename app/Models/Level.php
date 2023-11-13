<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;

class Level extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'level',
        'value',
        'emp_id',
        'start_date',
        'end_date',
        'status',
        'hierarchy_id',
        'created_by',
        'edited_by',
    ];
    public function employeeName()
    {
        return $this->belongsTo(MasEmployee::class, 'emp_id');
    }

    public function hierarchy()
    {
        return $this->belongsTo(Hierarchy::class, 'hierarchy_id');
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
