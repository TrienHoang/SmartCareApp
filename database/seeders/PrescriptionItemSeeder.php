<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrescriptionItem;

class PrescriptionItemSeeder extends Seeder
{
    public function run(): void
    {
        PrescriptionItem::factory()->count(15)->create();
    }
}

