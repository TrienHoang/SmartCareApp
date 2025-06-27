@extends('admin.dashboard')
@section('title', 'Chỉnh sửa Danh mục Dịch vụ')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-edit-alt me-2 text-primary" style="font-size: 24px;"></i>
                            <h2 class="content-header-title mb-0">Chỉnh sửa Danh mục Dịch vụ</h2>
                        </div>
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">
                                            Danh mục Dịch vụ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="m-0 font-weight-bold text-primary">Chỉnh sửa Danh mục #{{ $serviceCategory->id }}
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.categories.update', $serviceCategory->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name" class="form-label d-flex align-items-center">
                                                <i class="bx bx-category-alt text-primary me-2"></i> Tên danh mục <span
                                                    class="text-danger ms-1">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name"
                                                value="{{ old('name', $serviceCategory->name) }}"
                                                placeholder="Nhập tên danh mục..." required>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label d-flex align-items-center">
                                                <i class="bx bx-notepad text-info me-2"></i> Mô tả
                                            </label>
                                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Nhập mô tả danh mục...">{{ old('description', $serviceCategory->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        <i class="bx bx-save me-1"></i> Cập nhật Danh mục
                                    </button>
                                    <a href="{{ route('admin.categories.index') }}"
                                        class="btn btn-outline-secondary waves-effect">
                                        <i class="bx bx-arrow-back me-1"></i> Quay lại
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
