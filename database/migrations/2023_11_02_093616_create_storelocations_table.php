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
        Schema::create('storelocations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('dzongkhag_id');
            $table->foreign('dzongkhag_id')->references('id')->on('dzongkhags')->onDelete('cascade');
            $table->unsignedBigInteger('timezone_id');
            $table->foreign('timezone_id')->references('id')->on('time_zones')->onDelete('cascade');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storelocations');
    }
};
