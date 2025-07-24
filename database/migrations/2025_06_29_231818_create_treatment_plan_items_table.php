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
        Schema::create('treatment_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_id')
                  ->constrained('treatment_plans')
                  ->onDelete('cascade'); // Tự động xóa các items nếu plan bị xóa
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('expected_start_date')->nullable();
            $table->dateTime('expected_end_date')->nullable();
            $table->dateTime('actual_end_date')->nullable();
            $table->string('frequency')->nullable()->comment('Tần suất: 2 lần/ngày, 1 lần/tuần');
            $table->string('status', 50)->default('pending')->comment('Trạng thái: pending, in_progress, completed, paused, cancelled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_items');
    }
};
