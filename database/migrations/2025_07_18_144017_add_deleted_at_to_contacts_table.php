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
        Schema::table('contacts', function (Blueprint $table) {
            $table->softDeletes(); // Tự động thêm cột deleted_at
        });
    }

    public function down()
    {
        if (Schema::hasColumn('contacts', 'deleted_at')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
    }
};
