<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['wallet_id', 'type', 'amount', 'description', 'bank_name', 'status'];

    // Quan hệ với ví
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
