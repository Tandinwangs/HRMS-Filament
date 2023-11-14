<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RateLimit extends Model
{
    use HasFactory, HasUuids;
     // Define the table associated with the model
     protected $table = 'rate_limits';

     // Define the fillable fields (columns)
     protected $fillable = [
         'grade',
         'region',
         'limit_amount',
         'start_date',
         'end_date',
         'status',
         'policy_id',
         // Add other fields here as needed
     ];
 
     // Define relationships if applicable
     public function policy()
     {
         return $this->belongsTo(Policy::class);
     }
     public function gradeName()
     {
     return $this->belongsTo(MasGrade::class, 'grade'); // 'grade' is the foreign key in RateLimit
     }

     public function regionname()
     {
     return $this->belongsTo(region::class, 'region'); // 'grade' is the foreign key in RateLimit
     }
}
