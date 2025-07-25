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
    Schema::table('services', function (Blueprint $table) {
        $table->longText('content')->nullable()->after('description');
        $table->string('image')->nullable()->after('description'); 
        $table->unsignedBigInteger('room_id')->after('image');

        $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('services', function (Blueprint $table) {
        $table->dropColumn('content');
        $table->dropForeign(['room_id']);
        $table->dropColumn(['image', 'room_id']);
    });
}

};
