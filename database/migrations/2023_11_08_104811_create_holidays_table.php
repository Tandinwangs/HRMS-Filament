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
        Schema::create('holidays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('year');
            $table->uuid('holidaytype_id');
            $table->foreign('holidaytype_id')->references('id')->on('holiday_types')->onDelete('cascade');
            $table->string('optradioholidayfrom')->nullable();
            $table->date('start_date');
            $table->string('optradioholidaylto')->nullable();
            $table->date('end_date');
            $table->decimal('number_of_days', 5, 2); // Adjust precision and scale as needed
            $table->text('description')->nullable(); // Use text for longer descriptions
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
        Schema::dropIfExists('holidays');
    }
};
