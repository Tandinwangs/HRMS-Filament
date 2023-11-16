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
        Schema::create('d_s_a_manuals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('dsa_settlement_id')->nullable(); // Make the dsa_settlement_id nullable
            $table->date('from_date')->nullable(); 
            $table->string('from_location')->nullable(); 
            $table->date('to_date')->nullable(); 
            $table->string('to_location')->nullable(); 
            $table->integer('total_days')->nullable(); 
            $table->decimal('da', 10, 2)->nullable();
            $table->decimal('ta', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('mas_employees')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_s_a_manuals');
    }
};
