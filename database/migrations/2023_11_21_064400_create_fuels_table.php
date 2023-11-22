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
        Schema::create('fuels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('location');
            $table->date('application_date');
            $table->uuid('vehicle_no');
            $table->foreign('vehicle_no')->references('id')->on('add_vehicles')->onDelete('cascade');
            $table->string('vehicle_type');
            $table->date('date');
            $table->string('initial_km');
            $table->string('final_km');
            $table->string('quantity');
            $table->string('mileage');
            $table->string('rate');
            $table->string('amount');
            $table->string('attachment')->nullable();
            $table->enum('level1', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level2', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level3', ['pending', 'approved','rejected'])->default('pending');
            $table->enum('status', ['pending', 'approved','rejected'])->default('pending'); // Add the status field
            $table->foreign('user_id')->references('id')->on('mas_employees')->onDelete('cascade');
            $table->uuid('expense_type_id');
            $table->foreign('expense_type_id')->references('id')->on('expense_types')->onDelete('cascade');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuels');
    }
};
