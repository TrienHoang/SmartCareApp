@extends('admin.dashboard')
@section('title', 'Chỉnh sửa Danh mục Dịch vụ')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1 class="mb-4">Chỉnh sửa Danh mục Dịch vụ</h1>

        <form action="{{ route('admin.categories.update', $serviceCategory->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Category Name Input -->
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $serviceCategory->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Description Input -->
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $serviceCategory->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Cập nhật danh mục</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
            </div>
        </form>
    </div>
@endsection
