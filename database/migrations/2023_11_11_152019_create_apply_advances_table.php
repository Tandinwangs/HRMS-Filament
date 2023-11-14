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
        Schema::create('apply_advances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('advance_type_id');
            $table->string('advance_no')->unique();
            $table->date('date')->default(DB::raw('CURRENT_DATE'));
            $table->string('mode_of_travel')->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->decimal('amount', 10, 2);
            $table->text('purpose')->nullable();
            $table->string('upload_file')->nullable();
            $table->integer('emi_count')->nullable();
            $table->date('deduction_period')->nullable();
            $table->decimal('interest_rate', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('monthly_emi_amount', 10, 2)->nullable();
            $table->uuid('item_type')->nullable()->constrained('device_e_m_i_s');
            $table->enum('level1', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level2', ['pending', 'approved','rejected'])->default('pending'); 
            $table->enum('level3', ['pending', 'approved','rejected'])->default('pending');
            $table->enum('status', ['pending', 'approved','rejected'])->default('pending'); // Add the status field
            $table->string('remark')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('mas_employees')->onDelete('cascade');
            $table->foreign('advance_type_id')->references('id')->on('advance_types')->onDelete('cascade');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apply_advances');
    }
};
