<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\LeaveType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $casualLeaveType = LeaveType::where('name', 'Casual Leave')->first();

        // Define the default casual leave balance
        $defaultCasualLeaveBalance = $casualLeaveType ? $casualLeaveType->LeavePolicy->LeaveRules->first()->duration ?? 0.0 : 0.0;

        Schema::create('leave_balances', function (Blueprint $table) use ($defaultCasualLeaveBalance) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('mas_employees')->onDelete('cascade');
            $table->decimal('earned_leave_balance', 10, 2)->default(0.0);
            $table->decimal('casual_leave_balance', 10, 2)->default($defaultCasualLeaveBalance);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
