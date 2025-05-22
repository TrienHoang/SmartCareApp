<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class FileUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'appointment_id', 'file_name',
        'file_path', 'file_category', 'uploaded_at'
    ];
    public $timestamps = false;
}

