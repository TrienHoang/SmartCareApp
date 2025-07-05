<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;
use App\Models\PaymentHistory;

class PaymentHistorySeeder extends Seeder
{
   public function run(): void
{
    $payments = Payment::all();

    if ($payments->isEmpty()) {
        $this->command->warn('⚠️ Không có payment để tạo lịch sử thanh toán.');
        return;
    }

    foreach ($payments as $payment) {
        PaymentHistory::create([
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'payment_date' => $payment->paid_at ?? now(),
        ]);
    }
}
}

