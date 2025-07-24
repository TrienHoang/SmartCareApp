<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        // Chỉ lấy danh mục dịch vụ đang hoạt động
        $serviceCategories = ServiceCategory::where('status', 'active')->get();

        return view('client.news.index', compact('posts', 'serviceCategories'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('client.news.show', compact('post'));
    }
}

