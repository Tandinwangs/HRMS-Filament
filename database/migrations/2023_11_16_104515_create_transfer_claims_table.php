<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transfer_claims', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('employee_id');
            $table->date('date');
            $table->string('designation');
            $table->string('department');
            $table->string('basic_pay');
            $table->string('transfer_claim_type');
            $table->string('current_location');
            $table->string('new_location');
            $table->string('claim_amount');
            $table->string('attachment')->nullable();
            $table->string('distance_km')->nullable();
            $table->enum('level1', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level2', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level3', ['pending', 'approved','rejected'])->default('pending');
            $table->enum('status', ['pending', 'approved','rejected'])->default('pending'); // Add the status field
            $table->uuid('expense_type_id');
            $table->foreign('expense_type_id')->references('id')->on('expense_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('mas_employees')->onDelete('cascade');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_claims');
    }
};
