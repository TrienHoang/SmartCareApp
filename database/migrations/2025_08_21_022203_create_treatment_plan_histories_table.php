<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_plan_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_plan_id');
            $table->unsignedBigInteger('changed_by_id')->nullable();
            $table->text('change_description');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();

            $table->foreign('treatment_plan_id')
                  ->references('id')->on('treatment_plans')
                  ->onDelete('cascade');

            $table->foreign('changed_by_id')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_histories');
    }
};
