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
        Schema::create('mas_grade_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid("grade_id")->index();
            $table->string("name",50)->index();
            $table->tinyInteger("status")->comment("1 for active, 0 for In-active");
            $table->integer("starting_salary")->nullable();
            $table->integer("increment")->nullable();
            $table->integer("ending_salary")->nullable();
            $table->string("pay_scale",100)->nullable();
            $table->foreign('grade_id')->on("mas_grades")->references('id')->restrictOnDelete()->cascadeOnUpdate();
            $table->uuid("created_by")->index();
            $table->uuid("edited_by")->index()->nullable();
            $table->foreign("created_by")->on("users")->references("id")->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign("edited_by")->on("users")->references("id")->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_grade_steps');
    }
};
