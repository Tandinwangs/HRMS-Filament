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
        Schema::create('leave_approval_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('approval_rule_id');
            $table->string('approval_type');
            $table->uuid('hierarchy_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('MaxLevel')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('approval_rule_id')->references('id')->on('leave_approval_rules')->onDelete('cascade');
            $table->foreign('hierarchy_id')->references('id')->on('hierarchies')->onDelete('set null');
            $table->foreign('employee_id')->references('id')->on('mas_employees')->onDelete('set null');
            $table->unsignedBigInteger("created_by")->index();
            $table->unsignedBigInteger("edited_by")->index()->nullable();
            $table->foreign("created_by")->references("id")->on("mas_employees")->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreign("edited_by")->references("id")->on("mas_employees")->onDelete('RESTRICT')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_approval_conditions');
    }
};
