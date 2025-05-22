<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id()->comment('ID lịch hẹn');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade')->comment('Người đặt lịch');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade')->comment('Bác sĩ thực hiện khám');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade')->comment('Dịch vụ khám được chọn');
            $table->dateTime('appointment_time')->nullable()->comment('Thời gian khám');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->nullable()->comment('Trạng thái lịch hẹn');
            $table->text('reason')->nullable()->comment('Lý do đặt lịch');
            $table->text('cancel_reason')->nullable()->comment('Lý do hủy');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('appointments');
    }
};
