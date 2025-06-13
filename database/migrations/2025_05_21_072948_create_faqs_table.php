<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_category_id');
            $table->string('question');
            $table->text('answer');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->foreign('service_category_id')->references('id')->on('service_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
