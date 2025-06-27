@extends('admin.dashboard')
@section('title', 'Tạo Danh mục Dịch vụ mới')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary me-3">
                                <i class="menu-icon tf-icons bx bx-category text-white"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 text-primary font-weight-bold">Tạo Danh mục Dịch vụ mới</h2>
                                <p class="text-muted mb-0">Thêm một danh mục dịch vụ mới vào hệ thống</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            <i class="feather icon-home me-1"></i>Trang chủ >
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">
                                            <i class="feather icon-list me-1"></i>Danh mục Dịch vụ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">Tạo mới</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-alert-circle me-2"></i>
                        <strong>Có lỗi xảy ra!</strong>
                    </div>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-x-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-folder-plus me-2"></i>
                        <h4 class="card-title mb-0 text-white font-weight-bold">Thông tin Danh mục</h4>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
                        class="needs-validation" novalidate>
                        @csrf

                        <div class="form-group mb-4">
                            <label for="name" class="form-label font-weight-semibold d-flex align-items-center">
                                <i class="bx bx-category-alt me-2 text-primary"></i>
                                Tên danh mục <span class="text-danger ms-1">*</span>
                            </label>
                            <input type="text" name="name"
                                class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
                                value="{{ old('name') }}" placeholder="Nhập tên danh mục..." required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted mt-1">
                                <i class="feather icon-info me-1"></i>Tên danh mục phải là duy nhất và dễ hiểu.
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="description" class="form-label font-weight-semibold d-flex align-items-center">
                                <i class="bx bx-notepad me-2 text-info"></i>
                                Mô tả
                            </label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                rows="5" placeholder="Mô tả chi tiết về danh mục dịch vụ...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted mt-1">
                                <i class="feather icon-edit-2 me-1"></i>Cung cấp mô tả rõ ràng để phân loại dịch vụ.
                            </small>
                        </div>

                        <div class="form-actions d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="text-muted">
                                <i class="feather icon-info me-1"></i>
                                <small>Các trường có dấu <span class="text-danger">*</span> là bắt buộc</small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('admin.categories.index') }}"
                                    class="btn btn-outline-secondary waves-effect me-2">
                                    <i class="feather icon-x me-1"></i>Hủy bỏ
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light px-4">
                                    <i class="feather icon-plus-circle me-2"></i>Thêm mới
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle i {
            margin: 0px auto
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .card {
            transition: all 0.3s ease;
        }

        .alert {
            border-radius: 10px;
        }

        .breadcrumb-item.active {
            color: #667eea !important;
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-group {
                width: 100%;
                display: flex;
            }

            .btn-group .btn {
                flex: 1;
            }
        }
    </style>
@endpush
