<?php

namespace App\Services;

use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletService
{
    // Cộng tiền
    public function addBalance($userId, $amount, $type, $description = null)
    {
        return DB::transaction(function () use ($userId, $amount, $type, $description) {
            $wallet = Wallet::firstOrCreate(['user_id' => $userId], ['balance' => 0]);
            $wallet->balance += $amount;
            $wallet->save();

            $wallet->transactions()->create([
                'type' => $type,
                'amount' => $amount,
                'description' => $description
            ]);

            return $wallet;
        });
    }

    // Trừ tiền
    public function deductBalance($userId, $amount, $type, $description = null)
    {
        return DB::transaction(function () use ($userId, $amount, $type, $description) {
            $wallet = Wallet::where('user_id', $userId)->firstOrFail();

            if ($wallet->balance < $amount) {
                throw new \Exception('Số dư ví không đủ.');
            }

            $wallet->balance -= $amount;
            $wallet->save();

            $wallet->transactions()->create([
                'type' => $type,
                'amount' => $amount,
                'description' => $description
            ]);

            return $wallet;
        });
    }
}
