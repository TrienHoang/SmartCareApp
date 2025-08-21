<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id()->comment('ID lịch hẹn');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->enum('status', ['pending','confirmed','checked_in','completed','cancelled'])
                  ->default('pending')
                  ->comment('Trạng thái cuộc hẹn');
            $table->dateTime('appointment_time')->nullable()->comment('Thời gian khám');
            $table->dateTime('check_in_time')->nullable()->comment('Thời gian bệnh nhân đến khám');
            $table->dateTime('end_time')->nullable()->comment('Thời gian kết thúc dự kiến');
            $table->text('reason')->nullable()->comment('Lý do đặt lịch');
            $table->text('cancel_reason')->nullable()->comment('Lý do hủy');
            $table->text('symptom_note')->nullable()->comment('Ghi chú triệu chứng bệnh khi hoàn thành');
            $table->timestamps();
            $table->unsignedBigInteger('treatment_plan_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('treatment_plan_item_id')->nullable();
            $table->string('qr_code')->nullable();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('treatment_plan_id')->references('id')->on('treatment_plans')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('treatment_plan_item_id')->references('id')->on('treatment_plan_items')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
