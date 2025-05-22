<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UploadHistory;

class UploadHistorySeeder extends Seeder
{
    public function run(): void
    {
        UploadHistory::factory()->count(30)->create();
    }
}

