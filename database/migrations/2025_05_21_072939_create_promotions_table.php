<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id()->comment('ID khuyến mãi');
            $table->string('code', 50)->nullable()->comment('Mã code');
            $table->text('description')->nullable()->comment('Mô tả');
            $table->decimal('discount_percentage', 5, 2)->nullable()->comment('Phần trăm giảm');
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('promotions');
    }
};
