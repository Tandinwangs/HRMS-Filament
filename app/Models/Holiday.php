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

    protected static function booted()
    {
        static::saving(function ($holiday) {
            $startDate = $holiday->start_date;
            $endDate = $holiday->end_date;
            $dayTypeStart = $holiday->optradioholidayfrom;
            $dayTypeEnd = $holiday->optradioholidaylto;
    
            if ($endDate === null && $dayTypeStart === 'First Half') {
                // Set end_date to start_date when 'First Half' is chosen
                $holiday->end_date = $startDate;
                $numberOfDays = 0.5;
                   $holiday->number_of_days = $numberOfDays;
            }else{
                $numberOfDays = self::calculateNumberOfDays($startDate, $endDate, $dayTypeStart, $dayTypeEnd);
            
                // Set the number_of_days directly without relying on the form field
                $holiday->number_of_days = $numberOfDays;
            }
       
        });
    }

    private static function calculateNumberOfDays($startDate, $endDate, $dayTypeStart, $dayTypeEnd)
    {
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);

        // Calculate the number of days
        $numberOfDays = ceil(abs($endTimestamp - $startTimestamp) / 86400); // 86400 seconds in a day

        // Adjust based on the selected time intervals
        if ($dayTypeStart === 'First Half') {
            $numberOfDays += 0.5;
        }

        if ($dayTypeEnd === 'First Half') {
            $numberOfDays -= 0.5;
        }

        return $numberOfDays;
    }
    
}
