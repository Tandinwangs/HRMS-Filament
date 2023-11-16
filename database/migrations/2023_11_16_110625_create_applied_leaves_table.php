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
        Schema::create('applied_leaves', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('mas_employees')->onDelete('cascade');
            $table->uuid('leave_id');
            $table->foreign('leave_id')->references('id')->on('leave_types')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->double('number_of_days'); 
            $table->string('status')->default('pending');
            $table->string('remark')->nullable();
            $table->string('file_path')->nullable(); 

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
        Schema::dropIfExists('applied_leaves');
    }
};
