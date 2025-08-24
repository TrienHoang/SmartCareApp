<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->bigInteger('size')->nullable();
            $table->string('file_category', 100)->nullable();
            $table->text('note')->nullable();
            $table->dateTime('uploaded_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('download_count')->default(0);

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('appointment_id')
                  ->references('id')->on('appointments')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_uploads');
    }
};
