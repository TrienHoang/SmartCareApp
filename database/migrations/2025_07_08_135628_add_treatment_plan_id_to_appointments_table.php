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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('treatment_plan_id')->nullable()->constrained('treatment_plans')->onDelete('set null');
            $table->unsignedBigInteger('treatment_plan_item_id')->nullable()->after('treatment_plan_id');

            $table->foreign('treatment_plan_item_id')
                ->references('id')
                ->on('treatment_plan_items')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['treatment_plan_id']);
            $table->dropColumn('treatment_plan_id');
            $table->dropForeign(['treatment_plan_item_id']);
            $table->dropColumn('treatment_plan_item_id');
        });
    }
};
