<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// faq
return new class extends Migration {
    public function up(): void {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void {
        Schema::dropIfExists('faq');
    }
};
