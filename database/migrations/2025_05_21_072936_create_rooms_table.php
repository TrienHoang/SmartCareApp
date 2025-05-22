<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id()->comment('ID phòng khám');
            $table->string('name', 100)->nullable()->comment('Tên phòng');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete()->comment('Phòng ban liên kết');
            $table->text('description')->nullable()->comment('Mô tả phòng');
            // $table->string('status', 20)->default('active')->comment('Trạng thái phòng');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rooms');
    }
};
