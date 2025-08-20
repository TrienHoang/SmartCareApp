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
        Schema::create('services', function (Blueprint $table) {
            $table->id()->comment('ID dịch vụ');
            $table->foreignId('service_cate_id')
                  ->nullable()
                  ->constrained('service_categories')
                  ->nullOnDelete();
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments')
                  ->nullOnDelete();
            $table->string('name')->nullable()->comment('Tên dịch vụ');
            $table->text('description')->nullable()->comment('Mô tả ngắn về dịch vụ');
            $table->string('image')->nullable();
            $table->foreignId('room_id')
                  ->nullable()
                  ->constrained('rooms')
                  ->nullOnDelete();
            $table->longText('content')->nullable();
            $table->decimal('price', 12, 2)->nullable()->comment('Giá dịch vụ');
            $table->integer('duration')->nullable()->comment('Thời lượng (phút)');
            $table->integer('min_booking_hours')->default(12);
            $table->enum('status', ['active', 'inactive'])->nullable()->comment('Trạng thái dịch vụ');
            $table->timestamps();
            $table->softDeletes()->comment('Thời gian xóa (soft-delete), null nếu chưa bị xóa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
