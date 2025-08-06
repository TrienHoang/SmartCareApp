<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending', 'confirmed', 'check_in', 'completed', 'cancelled') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nếu rollback, quay lại enum cũ (không có check_in)
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL");
    }
};
