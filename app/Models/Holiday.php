<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;

class Holiday extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'year',
        'holidaytype_id',
        'optradioholidayfrom',
        'start_date',
        'optradioholidaylto',
        'end_date',
        'number_of_days',
        'description',
    ];

    public function holidayType()
    {
        return $this->belongsTo(HolidayType::class, 'holidaytype_id');
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'region_holidays', 'holiday_id', 'region_id')->withTimestamps();;
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
