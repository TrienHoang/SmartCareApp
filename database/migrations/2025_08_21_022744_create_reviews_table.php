<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedTinyInteger('rating');
            $table->unsignedInteger('useful_count')->default(0);
            $table->text('comment');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->foreign('appointment_id')
                  ->references('id')->on('appointments')
                  ->onDelete('cascade');

            $table->foreign('patient_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('doctor_id')
                  ->references('id')->on('doctors')
                  ->onDelete('cascade');

            $table->foreign('service_id')
                  ->references('id')->on('services')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
