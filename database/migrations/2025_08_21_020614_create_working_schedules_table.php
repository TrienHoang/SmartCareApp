<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_schedules', function (Blueprint $table) {
            $table->id()->comment('ID lịch làm việc');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->string('day_of_week', 10)->nullable()->comment('Thứ trong tuần');
            $table->dateTime('day')->comment('Ngày làm việc');
            $table->timestamps();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->enum('status', ['Chờ xét duyệt', 'Đã xét duyệt'])
                  ->default('Chờ xét duyệt');

            // FK
            $table->foreign('doctor_id')
                  ->references('id')->on('doctors')
                  ->onDelete('cascade');

            $table->foreign('room_id')
                  ->references('id')->on('rooms')
                  ->onDelete('set null');

            $table->foreign('shift_id')
                  ->references('id')->on('shifts')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_schedules');
    }
};
