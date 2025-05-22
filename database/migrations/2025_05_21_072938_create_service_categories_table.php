<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id()->comment('ID danh mục dịch vụ');
            $table->string('name', 100)->nullable()->comment('Tên danh mục');
            $table->text('description')->nullable()->comment('Mô tả danh mục');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('service_categories');
    }
};
