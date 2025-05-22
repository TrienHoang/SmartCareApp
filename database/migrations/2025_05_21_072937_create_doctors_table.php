<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id()->comment('ID bác sĩ');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('ID người dùng liên kết bác sĩ');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete()->comment('ID phòng khám');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete()->comment('Phòng ban bác sĩ thuộc về');
            $table->string('specialization', 100)->nullable()->comment('Chuyên môn bác sĩ');
            $table->text('biography')->nullable()->comment('Tiểu sử, giới thiệu bác sĩ');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('doctors');
    }
};