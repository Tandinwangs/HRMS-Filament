<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasGradeStep extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "id",
        "grade_id",
        "name",
        "status",
        "starting_salary",
        "increment",
        "ending_salary",
        "pay_scale",
    ];

    public function grade(): BelongsTo{
        return $this->belongsTo(MasGrade::class,'grade_id');
    }
}
