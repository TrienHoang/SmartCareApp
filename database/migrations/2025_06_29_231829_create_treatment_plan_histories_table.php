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
        Schema::create('treatment_plan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_id')
                  ->constrained('treatment_plans')
                  ->onDelete('cascade');
            $table->foreignId('changed_by_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->text('change_description');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->timestamp('changed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_histories');
    }
};
