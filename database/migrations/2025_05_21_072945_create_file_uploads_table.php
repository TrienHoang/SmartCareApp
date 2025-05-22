<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_category', 100)->nullable();
            $table->dateTime('uploaded_at')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('file_uploads');
    }
};