<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id()->comment('ID quyền');
            $table->string('name', 100)->comment('Tên quyền');
            $table->text('description')->nullable()->comment('Mô tả quyền');
        });
    }

    public function down(): void {
        Schema::dropIfExists('permissions');
    }
};
