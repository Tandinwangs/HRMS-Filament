<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ExpenseApplication extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'expense_type_id',
        'application_date',
        'total_amount',
        'description',
        'attachment',
        'travel_type',
        'travel_mode',
        'travel_from_date',
        'travel_to_date',
        'travel_from',
        'travel_to',
        'level1',
        'level2',
        'level3',
        'status',
        'remark',
    ];

    public function user()
    {
        return $this->belongsTo(MasEmployee::class, 'user_id');
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class,'expense_type_id');
    }
  
    public static function boot()
    {
        parent::boot();
    
        static::saving(function ($expenseType) {
    
            // Retrieve the 'expense_type_id' from the current ExpenseApplication instance
            $selectedExpenseTypeId = $expenseType->expense_type_id;
    
            // Check if a valid 'expense_type_id' is present
            if ($selectedExpenseTypeId) {
                // Query the Policy model to find the first policy with a matching 'expense_type_id'
                $policy = Policy::where('expense_type_id', $selectedExpenseTypeId)->first();
    
                // Check if a policy is found
                if (!$policy) {
                    throw new \Exception('No policy found for this expense type.');
                }
    
                // Check if the policy has a rateDefinition
                if (!$policy->rateDefinitions) {
                    throw new \Exception('No rate definition found for this policy.');
                }
    
                // Check if attachment is required
                if ($policy->rateDefinitions->attachment_required == 1) {
                    // Check if the 'attachment' attribute is set on the model
                    if (!$expenseType->attachment) {
                        throw new \Exception('Attachment is required.');
                    }
                } else {
                    // If attachment is not required, make it nullable
                    // $expenseType->attachment = null;
                }
            }
        });
    }
    
    
    
    
    

}
