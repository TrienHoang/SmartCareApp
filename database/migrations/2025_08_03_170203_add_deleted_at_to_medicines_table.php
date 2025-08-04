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
            $table->softDeletes();
            $table->dropColumn('dosage');
            $table->dropColumn('price');
            $table->dateTime('updated_at')->nullable()->after('created_at')->comment('Thời gian cập nhật thuốc');
        });
    }
    
    public function down()
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('updated_at');
        });
    }
};
