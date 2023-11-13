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
        Schema::create('leave_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('policy_id');
            $table->foreign('policy_id')->references('id')->on('leave_policies')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->boolean('attachment_required')->default(false);
            $table->string('gender');
            $table->string('leave_year'); // Add leave_year column
            $table->string('credit_frequency'); // Add credit_frequency column
            $table->string('credit'); // Add credit column
            $table->boolean('include_public_holidays')->default(false); 
            $table->boolean('include_weekends')->default(false); 
            $table->boolean('can_be_clubbed_with_el')->default(false);
            $table->boolean('can_be_clubbed_with_cl')->default(false);
            $table->boolean('can_be_half_day')->default(false); 
            $table->boolean('probation_period')->default(false);
            $table->boolean('regular_period')->default(false);
            $table->boolean('contract_period')->default(false); 
            $table->boolean('notice_period')->default(false); 
            $table->unsignedBigInteger("created_by")->index();
            $table->unsignedBigInteger("edited_by")->index()->nullable();
            $table->foreign("created_by")->references("id")->on("mas_employees")->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreign("edited_by")->references("id")->on("mas_employees")->onDelete('RESTRICT')->onUpdate('CASCADE');
            
            $table->unique('policy_id');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_plans');
    }
};
