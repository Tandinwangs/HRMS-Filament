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
        Schema::create('rate_definitions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('policy_id')
            ->constrained('policies')
            ->onDelete('cascade'); // This enables cascade deletion           
            $table->boolean('attachment_required');
            $table->string('travel_type');
            $table->string('type');
            $table->string('name');
            $table->enum('rate_limit', ['daily', 'monthly', 'yearly']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_definitions');
    }
};
