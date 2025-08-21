<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('doctor_leaves', function (Blueprint $table) {
            $table->unsignedBigInteger('replacement_doctor_id')->nullable()->after('doctor_id');

            $table->foreign('replacement_doctor_id')
                ->references('id')->on('doctors')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('doctor_leaves', function (Blueprint $table) {
            $table->dropForeign(['replacement_doctor_id']);
            $table->dropColumn('replacement_doctor_id');
        });
    }
};
