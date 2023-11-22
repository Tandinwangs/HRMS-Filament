<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Fuel extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'user_id',
        'location',
        'application_date',
        'date',
        'vehicle_type',
        'vehicle_no',
        'initial_km',
        'final_km' ,
        'quantity' ,
        'mileage',
        'rate',
        'amount',
        'level1',
        'level2',
        'level3',
        'status',
        'remark',
        'expense_type_id',
        'attachment',
    ];

    public function user()
    {
        return $this->belongsTo(MasEmployee::class,'user_id');
    }
    public function vehicle()
    {
        return $this->belongsTo(AddVehicle::class,'vehicle_no');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Fetch the expense type and set the 'expensetype_id' attribute
            $expenseType = ExpenseType::where('name', 'Expense Fuel')->first();
            if ($expenseType) {
                $model->expense_type_id = $expenseType->id;
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
                if (!$model->attachment) {
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


}
