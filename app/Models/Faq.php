<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'service_category_id',
        'question',
        'answer',
        'is_active',
        'display_order'
    ];
}
