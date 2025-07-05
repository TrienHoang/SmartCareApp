<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->date('date');
            $table->integer('total_doctors')->default(0);
            $table->integer('total_patients')->default(0);
            $table->integer('total_appointments')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->integer('appointments_pending')->default(0);
            $table->integer('appointments_completed')->default(0);
            $table->integer('appointments_cancelled')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
