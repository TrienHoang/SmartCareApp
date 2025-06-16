<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('type')->default('system');
            $table->unsignedBigInteger('sender_id')->nullable(); // ID của Admin gửi
            $table->string('recipient_type')->nullable(); 
            $table->json('recipient_ids')->nullable();
            $table->timestamp('scheduled_at')->nullable(); 
            $table->timestamp('sent_at')->nullable(); 
            $table->string('status')->default('draft'); // 'draft', 'scheduled', 'sent', 'failed'
            $table->timestamps();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};