<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->enum('status', ['pending','paid','confirmed','completed','cancelled'])
                  ->default('pending');
            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();

            // FK
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('appointment_id')
                  ->references('id')->on('appointments')
                  ->onDelete('set null');

            $table->foreign('payment_id')
                  ->references('id')->on('payments')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
