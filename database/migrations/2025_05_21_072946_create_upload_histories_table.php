<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('upload_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_upload_id')->constrained('file_uploads')->onDelete('cascade');
            $table->string('action', 20);
            $table->dateTime('timestamp');
        });
    }

    public function down(): void {
        Schema::dropIfExists('upload_histories');
    }
};