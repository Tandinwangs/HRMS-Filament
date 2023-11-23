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
        Schema::create('mas_employees', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string("first_name",100);
            $table->string("middle_name",100)->nullable();
            $table->string("last_name",100)->nullable();
            $table->string("emp_id",50)->index();
            $table->date("date_of_appointment");
            $table->uuid("grade_id")->index();
            $table->uuid("grade_step_id")->index();
            $table->uuid("created_by")->index();
            $table->uuid("edited_by")->index()->nullable();
            $table->uuid("designation_id")->index();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');

            $table->unsignedBigInteger('section_id')->nullable();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');

            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null');

            $table->foreign("designation_id")->on("mas_designations")->references("id")->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign("grade_id")->on("mas_grades")->references("id")->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign("grade_step_id")->on("mas_grade_steps")->references("id")->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign("created_by")->on("users")->references("id")->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign("edited_by")->on("users")->references("id")->restrictOnDelete()->cascadeOnUpdate();
            $table->string('employee_display', 350)->storedAs("concat(first_name,case when middle_name is not null then concat(' ',middle_name) else '' end, case when last_name is not null then concat(' ',last_name) else '' end, ' (E00',emp_id,')')");
            $table->string('gender')->nullable();
            $table->string('employment_type')->nullable();
            $table->boolean('is_sectionHead')->default(0);
            $table->boolean('is_departmentHead')->default(0);
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_employees');
    }
};
