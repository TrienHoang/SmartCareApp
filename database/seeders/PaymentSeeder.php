<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Promotion;

class PaymentSeeder extends Seeder
{
public function run(): void
{
    $appointments = Appointment::limit(10)->get();

    if ($appointments->isEmpty()) {
        $this->command->warn('⚠️ Không có appointment để tạo payment.');
        return;
    }

    foreach ($appointments as $appointment) {
        Payment::create([
            'appointment_id' => $appointment->id,
            'promotion_id' => Promotion::inRandomOrder()->value('id'),
            'amount' => fake()->randomFloat(2, 200, 800),
            'payment_method' => 'cash',
            'status' => 'paid',
            'paid_at' => now()->subDays(rand(1, 5)),
        ]);
    }
}
}

