<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id()->comment('ID lịch sử thanh toán');
            $table->unsignedBigInteger('payment_id');
            $table->decimal('amount', 10, 2)->comment('Số tiền thanh toán');
            $table->string('payment_method', 20)->nullable()->comment('Phương thức');
            $table->dateTime('payment_date')->nullable()->comment('Ngày thanh toán');

            $table->foreign('payment_id')
                  ->references('id')->on('payments')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
