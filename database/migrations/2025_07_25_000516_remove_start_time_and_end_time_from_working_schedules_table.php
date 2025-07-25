<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStartTimeAndEndTimeFromWorkingSchedulesTable extends Migration
{
    public function up()
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']); // Xóa 2 cột
        });
    }

    public function down()
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            $table->time('start_time')->nullable(); // Thêm lại nếu rollback
            $table->time('end_time')->nullable();
        });
    }
}
