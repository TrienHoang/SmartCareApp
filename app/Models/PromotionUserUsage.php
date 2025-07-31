<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionUserUsage extends Model
{
    use HasFactory;

    protected $table = 'promotion_user_usages';

    protected $fillable = [
        'user_id',
        'promotion_id',
        'used_at',
    ];
}
