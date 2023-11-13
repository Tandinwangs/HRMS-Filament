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
        Schema::create('leave_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('policy_id');
            $table->foreign('policy_id')->references('id')->on('leave_policies')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->uuid('grade_step_id');
            $table->foreign('grade_step_id')->on("mas_grade_steps")->references('id')->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('duration');
            $table->string('uom');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('islossofpay')->default(false);
            $table->string('employee_type');
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('leave_rules');
    }
};
