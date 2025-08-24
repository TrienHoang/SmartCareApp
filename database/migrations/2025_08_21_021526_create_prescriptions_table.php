<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('medical_record_id');
            $table->dateTime('prescribed_at')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->unsignedTinyInteger('edit_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('doctor_id')
                  ->references('id')->on('doctors')
                  ->onDelete('set null');

            $table->foreign('medical_record_id')
                  ->references('id')->on('medical_records')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
