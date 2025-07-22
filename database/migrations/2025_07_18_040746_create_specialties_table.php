<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Tên chuyên môn
            $table->string('description')->nullable(); // Mô tả nếu có
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialties');
    }
};
