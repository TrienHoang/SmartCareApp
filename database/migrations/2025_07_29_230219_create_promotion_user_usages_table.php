<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promotion_user_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->timestamp('used_at')->nullable(); // ngày dùng mã
            $table->timestamps();

            $table->unique(['user_id', 'promotion_id']); // Mỗi user chỉ dùng mã 1 lần
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_user_usages');
    }
};
