<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class EnforcementOption extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = [
        'policy_id',
        'prevent_submission',
        'display_warning',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }
}
