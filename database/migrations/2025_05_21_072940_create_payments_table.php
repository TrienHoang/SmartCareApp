<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->comment('ID thanh toán');
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade')->comment('Cuộc hẹn liên quan');
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->nullOnDelete()->comment('Khuyến mãi');
            $table->decimal('amount', 10, 2)->comment('Tổng tiền');
            $table->string('payment_method', 20)->nullable()->comment('Phương thức thanh toán');
            $table->string('status', 20)->nullable()->comment('Trạng thái thanh toán');
            $table->dateTime('paid_at')->nullable()->comment('Thời gian thanh toán');
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
