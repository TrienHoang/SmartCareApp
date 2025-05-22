<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_cate_id', 'name', 'description', 'price', 'duration',
        'status', 'created_at', 'updated_at', 'deleted_at'
    ];
    public $timestamps = true;

}

