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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ID người dùng');
            $table->string('username')->unique()->comment('Tên đăng nhập');
            $table->string('password')->comment('Mật khẩu đã mã hóa');
            $table->string('full_name', 100)->nullable()->comment('Họ tên đầy đủ');
            $table->string('email')->unique();
            $table->string('facebook_id')->unique()->nullable();
            $table->string('google_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('phone', 20)->nullable()->comment('Số điện thoại');
            $table->string('gender', 10)->nullable()->comment('Giới tính');
            $table->date('date_of_birth')->nullable()->comment('Ngày sinh');
            $table->string('address')->nullable()->comment('Địa chỉ');
            $table->foreignId('role_id')->nullable()
                ->constrained('roles')
                ->nullOnDelete();
            $table->string('avatar')->nullable()->comment('Đường dẫn ảnh đại diện');
            $table->enum('status', ['online', 'offline'])->default('online')->comment('Trạng thái hoạt động');
            $table->timestamps();
            $table->string('province_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('ward_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
