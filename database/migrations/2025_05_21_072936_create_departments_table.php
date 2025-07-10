<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('departments', function (Blueprint $table) {
            $table->id()->comment('ID phòng ban');
            $table->string('name', 100)->nullable()->comment('Tên phòng ban');
            $table->text('description')->nullable()->comment('Mô tả phòng ban');
            $table->string('slug')->unique()->comment('Slug phòng ban');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('departments');
    }
};
