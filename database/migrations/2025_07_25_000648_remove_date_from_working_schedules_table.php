<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDateFromWorkingSchedulesTable extends Migration
{
    public function up()
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            $table->dropColumn('date'); // Xóa cột date
        });
    }

    public function down()
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            $table->date('date')->nullable(); // Thêm lại nếu rollback
        });
    }
}
