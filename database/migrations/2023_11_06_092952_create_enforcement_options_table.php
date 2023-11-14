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
        Schema::create('enforcement_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('policy_id');
            $table->boolean('prevent_submission')->default(false);
            $table->boolean('display_warning')->default(false);
            $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enforcement_options');
    }
};
