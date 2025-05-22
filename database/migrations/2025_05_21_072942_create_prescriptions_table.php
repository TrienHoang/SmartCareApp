<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');
            $table->dateTime('prescribed_at')->nullable();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('prescriptions');
    }
};