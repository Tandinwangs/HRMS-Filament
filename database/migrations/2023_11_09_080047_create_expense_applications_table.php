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
        Schema::create('expense_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('expense_type_id')->constrained();
            $table->date('application_date');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->string('travel_type')->nullable();
            $table->string('travel_mode')->nullable();
            $table->date('travel_from_date')->nullable();
            $table->date('travel_to_date')->nullable();
            $table->string('travel_from')->nullable();
            $table->string('travel_to')->nullable();
            $table->enum('level1', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level2', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level3', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('status', ['pending', 'approved','rejected'])->default('pending'); 
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('mas_employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_applications');
    }
};
