<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'keyword',
        'response',
        'suggested_services',
        'is_active',
        'priority'
    ];

    protected $casts = [
        'suggested_services' => 'array',
        'is_active' => 'boolean'
    ];
}
