<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('working_schedules', function (Blueprint $table) {
            $table->id()->comment('ID lịch làm việc');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade')->comment('ID bác sĩ');
            $table->date('date')->nullable()->comment('Ngày làm việc');
            $table->string('day_of_week', 10)->nullable()->comment('Thứ trong tuần');
            $table->string('start_time', 10)->nullable()->comment('Giờ bắt đầu');
            $table->string('end_time', 10)->nullable()->comment('Giờ kết thúc');
            $table->datetime('day')->comment('Ngày làm việc');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_schedules');
    }
};
