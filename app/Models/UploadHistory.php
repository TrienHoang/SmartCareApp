<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class UploadHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_upload_id', 'action', 'timestamp'
    ];
    public $timestamps = false;
}

