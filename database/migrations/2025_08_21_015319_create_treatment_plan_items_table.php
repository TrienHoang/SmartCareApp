<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_plan_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_plan_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('expected_start_date')->nullable();
            $table->dateTime('expected_end_date')->nullable();
            $table->dateTime('actual_end_date')->nullable();
            $table->string('frequency')->nullable()->comment('Tần suất: 2 lần/ngày, 1 lần/tuần');
            $table->string('status', 50)->default('pending')->comment('Trạng thái: pending, in_progress, completed, paused, cancelled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('service_id')->nullable();

            $table->foreign('treatment_plan_id')
                  ->references('id')->on('treatment_plans')
                  ->onDelete('cascade');

            $table->foreign('service_id')
                  ->references('id')->on('services')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_items');
    }
};
