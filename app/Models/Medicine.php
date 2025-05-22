<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'unit',
        'dosage', 'price', 'created_at'
    ];
    public $timestamps = false;
}
