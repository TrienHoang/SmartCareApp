@extends('admin.dashboard')
@section('title', 'Chi tiết Danh mục Dịch vụ')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1 class="mb-4">Chi tiết Danh mục Dịch vụ</h1>

        <div class="mb-3">
            <label class="form-label"><strong>Tên danh mục:</strong></label>
            <div class="form-control-plaintext">{{ $serviceCategory->name }}</div>
        </div>

        <div class="mb-3">
            <label class="form-label"><strong>Mô tả:</strong></label>
            <div class="form-control-plaintext">{{ $serviceCategory->description }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
            <a href="{{ route('admin.categories.edit', $serviceCategory->id) }}" class="btn btn-primary">Chỉnh sửa</a>
        </div>
    </div>
@endsection
