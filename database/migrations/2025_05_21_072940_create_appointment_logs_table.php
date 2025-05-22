<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('appointment_logs', function (Blueprint $table) {
            $table->id()->comment('ID log cuộc hẹn');
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade')->comment('ID cuộc hẹn');
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade')->comment('ID người thay đổi');
            $table->string('status_before', 20)->nullable()->comment('Trạng thái trước đó');
            $table->string('status_after', 20)->nullable()->comment('Trạng thái sau thay đổi');
            $table->dateTime('change_time')->nullable()->comment('Thời điểm thay đổi');
            $table->text('note')->nullable()->comment('Ghi chú thay đổi');
        });
    }

    public function down(): void {
        Schema::dropIfExists('appointment_logs');
    }
};