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
        Schema::create('leave_yearend_processes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('policy_id');
            $table->foreign('policy_id')->references('id')->on('leave_policies')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->boolean('allow_carryover')->default(false);
            $table->integer('carryover_limit')->default(0);
            $table->boolean('payat_yearend')->default(false);
            $table->integer('min_balance')->default(0);
            $table->integer('max_balance')->default(0);
            $table->boolean('carryforward_toEL')->default(false);
            $table->integer('carryforward_toEL_limit')->default(0);
            $table->unsignedBigInteger("created_by")->index();
            $table->unsignedBigInteger("edited_by")->index()->nullable();
            $table->foreign("created_by")->references("id")->on("mas_employees")->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreign("edited_by")->references("id")->on("mas_employees")->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_yearend_processes');
    }
};
