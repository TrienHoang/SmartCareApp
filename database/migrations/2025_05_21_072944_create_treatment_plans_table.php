<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// treatment_plans
return new class extends Migration {
    public function up(): void {
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->string('plan_title')->nullable();
            $table->decimal('total_estimated_cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('created_at')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('treatment_plans');
    }
};
