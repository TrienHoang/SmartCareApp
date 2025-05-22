<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('unit', 20)->nullable();
            $table->string('dosage', 30)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->dateTime('created_at')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('medicines');
    }
};

