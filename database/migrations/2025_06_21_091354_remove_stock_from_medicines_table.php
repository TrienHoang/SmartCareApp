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
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }

    public function down()
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->unsignedInteger('stock')->default(0);
        });
    }
};
