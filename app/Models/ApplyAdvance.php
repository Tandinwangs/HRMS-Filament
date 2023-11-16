<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ApplyAdvance extends Model
{
    use HasFactory, HasUuids;

      // Define the fillable fields
      protected $fillable = [
        'user_id','advance_type_id', 'advance_no', 'date', 'mode_of_travel', 'from_location', 'to_location',
        'from_date', 'to_date', 'amount', 'purpose', 'upload_file','remark',
        'emi_count', 'deduction_period',
        'interest_rate','total_amount','monthly_emi_amount','item_type',
        'level1','level2','level3','status','remark'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(MasEmployee::class);
    }
    public function dsaSettlement()
    {
        return $this->hasMany(DsaSettlement::class);
    }

    public function manualSettlements()
    {
        return $this->hasMany(DsaManualSettlement::class, 'advance_no', 'advance_no');
    }
    public function advanceType()
    {
        return $this->belongsTo(AdvanceType::class, 'advance_type_id');
    }
    public function device()
    {
        return $this->belongsTo(DeviceEMI::class, 'item_type');
    }
   
}
