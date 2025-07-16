@extends('admin.dashboard')

@section('content')
<div class="container">
    <h2>Danh sách bài viết</h2>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-success mb-3">+ Thêm bài viết</a>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
                <td>{{ $post->serviceCategory->name ?? '---' }}</td>
                <td>
                    @switch($post->status)
                        @case('published') <span class="badge bg-success">Xuất bản</span> @break
                        @case('draft') <span class="badge bg-secondary">Nháp</span> @break
                        @case('archived') <span class="badge bg-dark">Lưu trữ</span> @break
                    @endswitch
                </td>
                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-sm btn-info">Xem</a>
                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa bài viết?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $posts->links() }}
</div>
@endsection
