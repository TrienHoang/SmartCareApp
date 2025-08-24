<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
{
    $query = Post::with('serviceCategory')->orderByDesc('created_at');

    if ($request->filled('category')) {
        $query->where('service_cate_id', $request->category);
    }

    $posts = $query->paginate(10);
    $categories = ServiceCategory::where('status', 'active')->get();

    return view('admin.posts.index', compact('posts', 'categories'));
}

    public function create()
    {
        $categories = ServiceCategory::where('status', 'active')->get(); // ✅ sửa model
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'min:5',
                'max:255',
                Rule::unique('posts', 'slug')->where(function ($query) use ($request) {
                    return $query->where('slug', Str::slug($request->title));
                }),
            ],
            'excerpt' => 'nullable|string|max:255', 
            'content' => 'required',
            'service_cate_id' => 'required|exists:service_categories,id',
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        // ✅ Nếu excerpt không có, tạo fallback từ content
        if (empty($validated['excerpt'])) {
            $validated['excerpt'] = Str::limit(strip_tags($request->content), 150);
        }

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Thêm bài viết thành công');
    }


    public function edit(Post $post)
    {
        $categories = ServiceCategory::where('status', 'active')->get(); // ✅ sửa model
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'min:5',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($post->id)->where(function ($query) use ($request) {
                    return $query->where('slug', Str::slug($request->title));
                }),
            ],
            'content' => 'required',
            'service_cate_id' => 'required|exists:service_categories,id',
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable|image|max:2048'
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.min' => 'Tiêu đề phải có ít nhất :min ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá :max ký tự.',
            'title.unique' => 'Tiêu đề đã tồn tại. Vui lòng chọn tiêu đề khác.',
            'content.required' => 'Vui lòng nhập nội dung.',
            'service_cate_id.required' => 'Vui lòng chọn danh mục dịch vụ.',
            'service_cate_id.exists' => 'Danh mục không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'thumbnail.image' => 'Tệp tải lên phải là hình ảnh.',
            'thumbnail.max' => 'Ảnh đại diện không được vượt quá 2MB.',
        ]);

        $currentStatus = $post->status;
        $newStatus = $validated['status'];

        if (
            ($currentStatus === 'archived' && in_array($newStatus, ['draft', 'published'])) ||
            ($currentStatus === 'published' && $newStatus === 'draft')
        ) {
            return back()->withErrors(['status' => 'Không thể thay đổi trạng thái này.'])->withInput();
        }

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['excerpt'] = Str::limit(strip_tags($request->content), 150);

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công');
    }


    public function destroy(Post $post)
    {
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Xóa bài viết thành công');
    }

    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }
}
