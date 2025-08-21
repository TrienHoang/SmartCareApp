<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id');
            $table->unsignedBigInteger('medicine_id');
            $table->integer('quantity')->nullable();
            $table->text('usage_instructions')->nullable();

            $table->foreign('prescription_id')
                  ->references('id')->on('prescriptions')
                  ->onDelete('cascade');

            $table->foreign('medicine_id')
                  ->references('id')->on('medicines')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
