<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('changed_by');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->timestamp('changed_at')->useCurrent();

            $table->timestamps(); // 👉 Thêm dòng này

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_logs');
    }
};
