<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class DSASettlement extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'expensetype_id',
        'date',
        'advance_no',
        'advance_amount',
        'total_amount_adjusted',
        'net_payable_amount',
        'balance_amount',
        'upload_file',
        // 'from_date',
        // 'from_location',
        // 'to_date',
        // 'to_location',
        // 'total_days',
        // 'da',
        // 'ta',
        // 'total_amount',
        // 'remarks',
        'level1',
        'level2',
        'level3',
        'status',
        'remark',

    ];

    public function user()
    {
        return $this->belongsTo(MasEmployee::class,'user_id');
    }
    public function advance()
    {
        return $this->belongsTo(ApplyAdvance::class,'advance_no');
    }
    public function expensetype()
    {
        return $this->belongsTo(ExpenseType::class,'expensetype_id');
    }
    public function dsamanual()
    {
        return $this->hasMany(DSAManual::class,'dsa_settlement_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Fetch the expense type and set the 'expensetype_id' attribute
            $expenseType = ExpenseType::where('name', 'DSA Settlement')->first();
            if ($expenseType) {
                $model->expensetype_id = $expenseType->id;
                  // Retrieve the 'expense_type_id' from the current ExpenseApplication instance
            $selectedExpenseTypeId = $expenseType->id;
            Log::info('Policy found:', ['Selected' => $selectedExpenseTypeId]);

            $policy = Policy::where('expense_type_id', $selectedExpenseTypeId)->first();

            // Log whether a policy is found
            Log::info('Policy found:', ['policy' => $policy]);
            
            // Check if a policy is found
            if (!$policy) {
                throw new \Exception('No policy found for this expense type.');
            }
            
            // Check if the policy has a rateDefinition
            Log::info('Rate definitions:', ['rateDefinitions' => $policy->rateDefinitions]);
            if (!$policy->rateDefinitions) {
                throw new \Exception('No rate definition found for this policy.');
            }
            
            // Check if attachment is required
            if ($policy->rateDefinitions->attachment_required == 1) {
                // Log whether attachment is required
                Log::info('Attachment required:', ['attachment_required' => $policy->rateDefinitions->attachment_required]);
            
                // Check if the 'attachment' attribute is set on the model
                Log::info('Expense type upload file:', ['upload_file' => $expenseType->upload_file]);
                if (!$model->upload_file) {
                    throw new \Exception('Attachment is required.');
                }
            } else {
                // If attachment is not required, make it nullable
                // $expenseType->attachment = null;
                Log::info('Attachment not required for this policy.');
            }
            } else {
                // Handle the case where the expense type does not exist
                echo "Expense type not found.";
            }
          
        });
    }

    public static function boots()
    {
        parent::boot();
    
        static::saving(function ($expenseType) {
    
            
        });
    }
    

}
