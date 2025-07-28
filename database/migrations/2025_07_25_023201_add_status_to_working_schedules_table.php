<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            $table->enum('status', ['Chờ xét duyệt', 'Đã xét duyệt'])->default('Chờ xét duyệt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            //
        });
    }
};
