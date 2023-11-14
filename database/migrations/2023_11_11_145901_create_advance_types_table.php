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
        Schema::create('advance_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('expense_type_id')->nullable()->constrained('expense_types');           
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger("status")->comment("1 for Enforce, 0 for Draft");
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_types');
    }
};
