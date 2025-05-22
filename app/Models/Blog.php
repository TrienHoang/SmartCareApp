<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content',
        'author_id', 'published_at', 'thumbnail'
    ];
    public $timestamps = false;
}
