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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);                // Tên thuốc
            $table->text('description')->nullable();    // Mô tả thuốc
            $table->string('unit', 20)->nullable();     // Đơn vị (viên, gói, ml, ...)
            $table->dateTime('created_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->dateTime('updated_at')->nullable()->comment('Thời gian cập nhật thuốc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
