<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doctor_leaves', function (Blueprint $table) {
            $table->id()->comment('ID nghỉ phép');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade')->comment('Bác sĩ nghỉ phép');
            $table->date('start_date')->nullable()->comment('Ngày bắt đầu');
            $table->date('end_date')->nullable()->comment('Ngày kết thúc');
            $table->text('reason')->nullable()->comment('Lý do nghỉ');
            $table->dateTime('created_at')->nullable()->comment('Thời gian lập phiếu');
            $table->boolean('approved')->default(false)->comment('Đã duyệt chưa');
            $table->softDeletes()->comment('Thời gian bị xóa mềm');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_leaves');
    }
};
