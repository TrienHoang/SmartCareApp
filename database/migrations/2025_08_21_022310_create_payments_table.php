<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->comment('ID thanh toán');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('promotion_id')->nullable();
            $table->decimal('amount', 10, 2)->comment('Tổng tiền');
            $table->string('payment_method', 20)->nullable()->comment('Phương thức thanh toán');
            $table->string('status', 20)->nullable()->comment('Trạng thái thanh toán');
            $table->string('note', 255)->nullable();
            $table->enum('refund_status', ['none','pending','completed','failed'])
                  ->default('none')
                  ->comment('Trạng thái hoàn tiền: none, pending, completed, failed');
            $table->dateTime('refunded_at')->nullable()->comment('Thời gian hoàn tiền thành công');
            $table->dateTime('paid_at')->nullable()->comment('Thời gian thanh toán');
            $table->string('vnp_txn_ref')->nullable();
            $table->string('vnp_transaction_no')->nullable();
            $table->string('vnp_response_code')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();

            // FK
            $table->foreign('appointment_id')
                  ->references('id')->on('appointments')
                  ->onDelete('cascade');

            $table->foreign('promotion_id')
                  ->references('id')->on('promotions')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
