<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','user_id', 'title', 'message', 'is_read', 'created_at'
    ];
    public $timestamps = false;
}
