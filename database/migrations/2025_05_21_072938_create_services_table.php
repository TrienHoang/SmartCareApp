<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('services', function (Blueprint $table) {
            $table->id()->comment('ID dịch vụ');
            $table->foreignId('service_cate_id')->nullable()->constrained('service_categories')->nullOnDelete()->comment('Loại dịch vụ liên kết');
            $table->string('name', 255)->nullable()->comment('Tên dịch vụ');
            $table->text('description')->nullable()->comment('Mô tả ngắn về dịch vụ');
            $table->decimal('price', 12, 2)->nullable()->comment('Giá dịch vụ');
            $table->integer('duration')->nullable()->comment('Thời lượng (phút)');
            $table->enum('status', ['active', 'inactive'])->nullable()->comment('Trạng thái dịch vụ');
            $table->timestamps();
            $table->softDeletes()->comment('Thời gian xóa (soft-delete), null nếu chưa bị xóa');
        });
    }

    public function down(): void {
        Schema::dropIfExists('services');
    }
};