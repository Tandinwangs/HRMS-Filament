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
        Schema::create('levels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('hierarchy_id');
            $table->foreign('hierarchy_id')->references('id')->on('hierarchies')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->string('level');
            $table->string('value');
            $table->foreignId('emp_id')->nullable()->constrained('mas_employees');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger("status")->comment("1 for active, 0 for In-active");
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
        Schema::dropIfExists('levels');
    }
};
