<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentHistory;

class PaymentHistorySeeder extends Seeder
{
    public function run(): void
    {
        PaymentHistory::factory()->count(15)->create();
    }
}

