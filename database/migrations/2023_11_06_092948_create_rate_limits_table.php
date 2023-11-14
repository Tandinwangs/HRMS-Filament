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
        Schema::create('rate_limits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('region'); // Define the rate_definition_id column
            $table->decimal('limit_amount', 10, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->tinyInteger("status")->comment("1 for active, 0 for In-active");
            $table->uuid('grade');
                    $table->foreign('grade')
                        ->references('id')
                        ->on('mas_grades')
                        ->onDelete('cascade');
            $table->foreign('region') // Define the foreign key constraint
                ->references('id')
                ->on('regions')
                ->onDelete('cascade'); // This enables cascade deletion
            $table->uuid('policy_id')
                ->constrained('policies')
                ->onDelete('cascade'); // This enables cascade deletion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_limits');
    }
};
