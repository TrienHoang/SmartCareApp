<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('treatment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->text('treatment_description')->nullable();
            $table->dateTime('treatment_date')->nullable();
            $table->dateTime('created_at')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('treatment_history');
    }
};