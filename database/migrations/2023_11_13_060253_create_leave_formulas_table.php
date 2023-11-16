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
        Schema::create('leave_formulas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('condition')->nullable();
            $table->string('field');
            $table->string('operator');
            $table->integer('value')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('mas_employees')->onDelete('cascade');
            $table->uuid('approval_rule_id')->nullable();
            $table->foreign("approval_rule_id")->references("id")->on("leave_approval_rules")->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->unsignedBigInteger("created_by")->index();
            $table->unsignedBigInteger("edited_by")->index()->nullable();
            $table->foreign("created_by")->references("id")->on("mas_employees")->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreign("edited_by")->references("id")->on("mas_employees")->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->string('formula_display')->nullable()->storedAs(
                "concat_ws(' ', 
                    `condition`, 
                    field, 
                    operator, 
                    case when value is not null then cast(value as char) else '' end,
                    case when employee_id is not null then cast(employee_id as char) else '' end
                )"
            );
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_formulas');
    }
};
