<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DeviceEMI extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['type', 'amount'];

    public function device()
    {
        return $this->hasOne(ApplyAdvance::class, 'item_type');
    }



}
