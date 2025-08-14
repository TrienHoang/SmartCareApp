<?php

namespace App\Services;

use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletService
{
    // Cộng tiền
    public function addBalance($userId, $amount, $type, $description = null, $status = 'Hoàn thành')
    {
        return DB::transaction(function () use ($userId, $amount, $type, $description, $status) {
            $wallet = Wallet::firstOrCreate(['user_id' => $userId], ['balance' => 0]);
            $wallet->balance += $amount;
            $wallet->save();

            $wallet->transactions()->create([
                'type' => $type,
                'amount' => $amount,
                'description' => $description,
                'status' => $status
            ]);

            return $wallet;
        });
    }

    // Trừ tiền
    public function deductBalance($userId, $amount, $type, $description = null, $bankName = null, $status = 'Chờ xử lý')
    {
        return DB::transaction(function () use ($userId, $amount, $type, $description, $bankName, $status) {
            $wallet = Wallet::where('user_id', $userId)->firstOrFail();

            if ($wallet->balance < $amount && $type !== 'refund') {
                throw new \Exception('Số dư ví không đủ.');
            }

            $wallet->balance -= $amount;
            $wallet->save();

            $wallet->transactions()->create([
                'type' => $type,
                'amount' => $amount,
                'description' => $description,
                'bank_name' => $bankName,
                'status' => $status
            ]);

            return $wallet;
        });
    }

    // Hoàn tiền
    public function refundBalance($userId, $amount, $description = null)
    {
        return $this->addBalance($userId, $amount, 'refund', $description, 'Hoàn thành');
    }
}
