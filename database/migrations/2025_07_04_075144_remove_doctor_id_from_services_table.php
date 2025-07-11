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
        Schema::table('services', function (Blueprint $table) {
            // Xóa foreign key trước, sau đó xóa cột
            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('doctor_id')
                ->nullable()
                ->after('department_id')
                ->constrained('doctors')
                ->onDelete('cascade');
        });
    }
};
