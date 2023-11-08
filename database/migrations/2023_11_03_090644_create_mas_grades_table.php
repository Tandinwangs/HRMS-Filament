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
        Schema::create('mas_grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name',50)->index();
            $table->tinyInteger("status")->comment("1 for active, 0 for In-active");
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
        Schema::dropIfExists('mas_grades');
    }
};
