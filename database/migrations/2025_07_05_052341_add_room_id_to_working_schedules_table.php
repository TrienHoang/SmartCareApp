<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoomIdToWorkingSchedulesTable extends Migration
{
    public function up()
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id')->after('doctor_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');
        });
    }
}
