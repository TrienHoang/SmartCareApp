<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->enum('type', ['refund','withdraw','payment','deposit']);
            $table->decimal('amount', 15, 2);
            $table->string('description', 255)->nullable();
            $table->string('account_number', 255)->nullable();
            $table->string('account_holder_name', 255)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->enum('status', ['Chờ xử lý','Hoàn thành','Không thành công'])
                  ->default('Chờ xử lý');
            $table->timestamps();

            $table->foreign('wallet_id')
                  ->references('id')->on('wallets')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
