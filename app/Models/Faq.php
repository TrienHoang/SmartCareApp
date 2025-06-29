<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_category_id',
        'question',
        'answer',
        'is_active',
        'display_order'
    ];
}
