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
        Schema::create('add_vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('vehicle_type');
            $table->string('vehicle_number');
            $table->decimal('vehicle_mileage', 10, 2);
            $table->tinyInteger("status")->comment("1 for active, 0 for In-active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_vehicles');
    }
};
