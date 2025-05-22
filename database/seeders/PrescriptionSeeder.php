<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prescription;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        Prescription::factory()->count(15)->create();
    }
}

