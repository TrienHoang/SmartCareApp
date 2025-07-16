<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'service_cate_id', 'thumbnail', 'status', 'published_at'
    ];

    public function serviceCategory() {
    return $this->belongsTo(ServiceCategory::class, 'service_cate_id');
}

}
