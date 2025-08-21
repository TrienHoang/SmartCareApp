<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_logs', function (Blueprint $table) {
            $table->id()->comment('ID log cuộc hẹn');
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('changed_by');
            $table->string('status_before', 20)->nullable()->comment('Trạng thái trước đó');
            $table->string('status_after', 20)->nullable()->comment('Trạng thái sau thay đổi');
            $table->dateTime('change_time')->nullable()->comment('Thời điểm thay đổi');
            $table->text('note')->nullable()->comment('Ghi chú thay đổi');

            $table->foreign('appointment_id')
                  ->references('id')->on('appointments')
                  ->onDelete('cascade');

            $table->foreign('changed_by')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_logs');
    }
};
