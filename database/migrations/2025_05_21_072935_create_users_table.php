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
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('phone', 20)->nullable()->comment('Số điện thoại');
            $table->string('gender', 10)->nullable()->comment('Giới tính');
            $table->date('date_of_birth')->nullable()->comment('Ngày sinh');
            $table->string('address')->nullable()->comment('Địa chỉ');
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete()->comment('Vai trò (liên kết bảng roles)');
            $table->string('avatar')->nullable()->comment('Đường dẫn ảnh đại diện');
            $table->timestamps();
        });

    

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
