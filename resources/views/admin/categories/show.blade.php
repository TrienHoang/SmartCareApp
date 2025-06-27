@extends('admin.dashboard')
@section('title', 'Chi tiết Danh mục Dịch vụ')

@section('content')
<<<<<<< HEAD
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-category me-2 text-primary" style="font-size: 24px;"></i>
                            <h2 class="content-header-title mb-0">Chi tiết Danh mục Dịch vụ</h2>
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
                                    <li class="breadcrumb-item active">Chi tiết</li>
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
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper me-3">
                                        <div class="avatar avatar-lg bg-primary">
                                            <i class="bx bx-category text-white" style="font-size: 20px;"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="card-title text-xl mb-1">Danh mục #{{ $serviceCategory->id }}</h4>
                                        <small class="text-muted">Tên danh mục: {{ $serviceCategory->name }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-info-circle text-primary me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Thông tin chi tiết</h5>
                                        </div>
                                        <div class="rounded p-3 bg-light-subtle mb-3">
                                            <p class="mb-2"><strong>Tên Danh mục:</strong> <span
                                                    class="fw-medium">{{ $serviceCategory->name }}</span></p>
                                            <p class="mb-0"><strong>Mô tả:</strong> <span
                                                    class="fw-medium">{{ $serviceCategory->description }}</span></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bx bx-time me-2"></i>Thông tin thời gian
                                            </h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="timeline-item mb-2">
                                                <small class="text-muted d-block">Ngày tạo</small>
                                                <span
                                                    class="fw-medium">{{ $serviceCategory->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="timeline-item">
                                                <small class="text-muted d-block">Ngày cập nhật</small>
                                                <span
                                                    class="fw-medium">{{ $serviceCategory->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start gap-2">
                                        <a href="{{ route('admin.categories.index') }}"
                                            class="btn btn-outline-secondary waves-effect">
                                            <i class="bx bx-arrow-back me-1"></i> Quay lại danh sách
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $serviceCategory->id) }}"
                                            class="btn btn-primary waves-effect waves-light">
                                            <i class="bx bx-edit me-1"></i> Chỉnh sửa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
=======
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
>>>>>>> 13d3b53bc7312809af652bc345f71bb52040d720
    </div>
@endsection
