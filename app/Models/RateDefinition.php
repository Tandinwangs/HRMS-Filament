<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateDefinition extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'policy_id',
        'attachment_required',
        'travel_type',
        'type',
        'name',
        'rate_limit',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function rateLimits()
    {
        return $this->hasMany(RateLimit::class);
    }
}
