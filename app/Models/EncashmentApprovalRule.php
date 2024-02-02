<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Scopes\CreatedByScope;
use App\Scopes\EditedByScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncashmentApprovalRule extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'For',
        'type_id',
        'RuleName',
        'start_date',
        'end_date',
        'status',
        'created_by', 
        'updated_by'
    ];

    public function type()
    {
        return $this->belongsTo(encashment::class, 'type_id');
    }

     // Define the relationship to approval_conditions
     public function encashmentApprovalCondition()
     {
         return $this->hasMany(EncashmentApprovalCondition::class, 'encashment_rule_id' );
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
