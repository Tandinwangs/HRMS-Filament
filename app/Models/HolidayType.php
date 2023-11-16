<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HolidayType extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'holiday_types';

    protected $fillable = [
        "id",
        "name",
        "status",
        "created_by",
        "edited_by",
    ];
    public function holidays(): HasMany{
        return $this->hasMany(Holiday::class,'holidaytype_id');
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
