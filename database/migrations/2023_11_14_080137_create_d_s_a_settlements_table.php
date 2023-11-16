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
        Schema::create('d_s_a_settlements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('expensetype_id');
            $table->date('date'); 
            $table->uuid('advance_no')->nullable(); // Make it nullable
           // $table->string('advance_no')->nullable();
            $table->decimal('advance_amount', 10, 2)->default(0);
            $table->decimal('total_amount_adjusted', 10, 2)->nullable(); // Make it nullable
            $table->decimal('net_payable_amount', 10, 2)->nullable(); // Make it nullable
            $table->decimal('balance_amount', 10, 2)->nullable(); // Make it nullable
            $table->string('upload_file')->nullable();
            // $table->date('from_date')->nullable(); 
            // $table->string('from_location')->nullable(); 
            // $table->date('to_date')->nullable(); 
            // $table->string('to_location')->nullable(); 
            // $table->integer('total_days')->nullable(); 
            // $table->decimal('da', 10, 2)->nullable();
            // $table->decimal('ta', 10, 2)->nullable();
            // $table->decimal('total_amount', 10, 2)->nullable();
            // $table->text('remarks')->nullable();
            $table->enum('level1', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level2', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level3', ['pending', 'approved','rejected'])->default('pending');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('mas_employees')->onDelete('cascade');
            $table->foreign('expensetype_id')->references('id')->on('expense_types')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_s_a_settlements');
    }
};
