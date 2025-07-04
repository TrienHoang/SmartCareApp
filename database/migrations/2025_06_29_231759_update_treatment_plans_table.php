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
        Schema::table('treatment_plans', function (Blueprint $table) {
            // Thêm các cột mới sau cột 'notes' cho hợp lý
            $table->text('diagnosis')->nullable()->after('notes');
            $table->text('goal')->nullable()->after('diagnosis');
            $table->date('start_date')->nullable()->after('goal');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('status', 50)->default('draft')->after('end_date')->comment("Trạng thái: draft, active, completed, paused, cancelled");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_plans', function (Blueprint $table) {
            $table->dropColumn(['diagnosis', 'goal', 'start_date', 'end_date', 'status']);
        });
    }
};
