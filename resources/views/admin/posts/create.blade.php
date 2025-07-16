@extends('admin.dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Thêm bài viết mới</h1>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Tiêu đề</label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="form-control @error('title') is-invalid @enderror">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Danh mục dịch vụ</label>
            <select name="service_cate_id" class="form-select @error('service_cate_id') is-invalid @enderror">
                <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $cate)
                    <option value="{{ $cate->id }}" {{ old('service_cate_id') == $cate->id ? 'selected' : '' }}>
                        {{ $cate->name }}
                    </option>
                @endforeach
            </select>
            @error('service_cate_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Nội dung</label>
            <textarea name="content" rows="6"
                class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror">
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Xuất bản</option>
                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Ảnh đại diện</label>
            <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror">
            @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
