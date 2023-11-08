<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasGrade extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        "id",
        "name",
        "status",
        "created_by",
        "edited_by",
    ];
    public function gradeSteps(): HasMany{
        return $this->hasMany(MasGradeStep::class,'grade_id');
    }
}
