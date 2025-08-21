<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->string('plan_title')->nullable();
            $table->decimal('total_estimated_cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('goal')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status', 50)->default('draft')->comment('Trạng thái: draft, active, completed, paused, cancelled');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('doctor_id')
                  ->references('id')->on('doctors')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_plans');
    }
};
