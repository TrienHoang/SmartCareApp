<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\ServiceCategory; // ✅ Sửa lại model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index() {
        $posts = Post::with('serviceCategory')->orderByDesc('created_at')->paginate(10); // ✅ sửa quan hệ
        return view('admin.posts.index', compact('posts'));
    }

    public function create() {
        $categories = ServiceCategory::where('status', 'active')->get(); // ✅ sửa model
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:service_categories,id', // ✅ sửa bảng
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['slug'] = str()->slug($validated['title']);
        $validated['excerpt'] = str()->limit(strip_tags($request->content), 150);

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Thêm bài viết thành công');
    }

    public function edit(Post $post) {
        $categories = ServiceCategory::where('status', 'active')->get(); // ✅ sửa model
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post) {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:service_categories,id', // ✅ sửa bảng
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['slug'] = str()->slug($validated['title']);
        $validated['excerpt'] = str()->limit(strip_tags($request->content), 150);

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công');
    }

    public function destroy(Post $post) {
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Xóa bài viết thành công');
    }

    public function show(Post $post) {
        return view('admin.posts.show', compact('post'));
    }
}
