<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'description', 'discount_percentage',
        'valid_from', 'valid_until'
    ];
    public $timestamps = false;
}

