<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data');
            $table->timestamp('changed_at')->useCurrent();

            $table->foreign('prescription_id')
                  ->references('id')->on('prescriptions')
                  ->onDelete('cascade');

            $table->foreign('updated_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_histories');
    }
};
