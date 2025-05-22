<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileUpload;

class FileUploadSeeder extends Seeder
{
    public function run(): void
    {
        FileUpload::factory()->count(15)->create();
    }
}

