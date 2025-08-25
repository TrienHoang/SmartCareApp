<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $sortOption = $request->get('sort', 'desc');
        $sort = in_array($sortOption, ['asc', 'desc']) ? $sortOption : 'desc';

        $query = Post::where('status', 'published');

        // Tìm kiếm tiêu đề
        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where('title', 'like', '%' . $keyword . '%');
        }
$popularArticles = Post::where('status', 'published')
    ->orderByDesc('view_count') // Sắp xếp theo lượt xem nhiều nhất
    ->take(5)
    ->get(['title', 'slug', 'view_count', 'created_at']);

        // Sắp xếp mặc định: mới nhất lên đầu
        $query->orderBy('created_at', $sort);

        // Phân trang và giữ lại query string
        $posts = $query->paginate(6)->appends($request->query());

        $serviceCategories = ServiceCategory::where('status', 'active')->get();

        return view('client.news.index', compact('posts', 'serviceCategories', 'popularArticles'));
    }

   public function category($id, Request $request)
{
    $serviceCategories = ServiceCategory::where('status', 'active')->get(); // Danh mục filter
    $category = ServiceCategory::findOrFail($id);

    $posts = Post::where('status', 'published')
        ->where('service_cate_id', $id)
        ->orderBy('created_at', 'desc')
        ->paginate(6)
        ->appends($request->query()); // giữ query string nếu có

    $popularArticles = Post::where('status', 'published')
        ->orderByDesc('view_count')
        ->take(5)
        ->get(['title', 'slug', 'view_count', 'created_at']);

    return view('client.news.index', compact('posts', 'serviceCategories', 'category', 'popularArticles'));
}


    public function show($slug)
{
    $post = Post::where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    // Tăng lượt xem
    $post->increment('view_count');


    return view('client.news.show', compact('post'));
}

}
