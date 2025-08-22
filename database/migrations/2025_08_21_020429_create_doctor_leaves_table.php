<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_leaves', function (Blueprint $table) {
            $table->id()->comment('ID nghỉ phép');
            $table->unsignedBigInteger('doctor_id');
            $table->date('start_date')->nullable()->comment('Ngày bắt đầu');
            $table->date('end_date')->nullable()->comment('Ngày kết thúc');
            $table->text('reason')->nullable()->comment('Lý do nghỉ');
            $table->boolean('urgent')->default(false);
            $table->dateTime('created_at')->nullable()->comment('Thời gian lập phiếu');
            $table->boolean('approved')->default(false)->comment('Đã duyệt chưa');
            $table->timestamp('deleted_at')->nullable()->comment('Thời gian bị xóa mềm');

            $table->foreign('doctor_id')
                  ->references('id')->on('doctors')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_leaves');
    }
};
