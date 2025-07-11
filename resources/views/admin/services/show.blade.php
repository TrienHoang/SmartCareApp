@extends('admin.dashboard')
@section('title', 'Chi tiết Dịch vụ')

@section('content')
    <div class="content-wrapper">
        <!-- Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-cog me-2 text-primary" style="font-size: 24px;"></i>
                            <h2 class="content-header-title mb-0">Chi tiết Dịch vụ</h2>
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
                                        <a href="{{ route('admin.services.index') }}" class="text-decoration-none">
                                            Dịch vụ > 
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

        <!-- Main Content -->
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <!-- Service Card -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper me-3">
                                        <div class="avatar avatar-lg bg-primary">
                                            <i class="bx bx-cog text-white" style="font-size: 20px;"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="card-title text-xl mb-1">{{ $service->name }}</h4>
                                        <small class="text-muted">Dịch vụ #{{ $service->id }}</small>
                                    </div>
                                </div>
                                <div class="status-badge">
                                    <span class="badge
                                        @if ($service->status == 'active') bg-success
                                        @else bg-danger @endif
                                        rounded-pill px-3 py-2">
                                        <i class="bx 
                                            @if ($service->status == 'active') bx-check-circle
                                            @else bx-x-circle @endif
                                            me-1"></i>
                                        {{ $service->status == 'active' ? 'Hoạt động' : 'Tạm ngừng' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Left Column -->
                                <div class="col-lg-8">
                                    <!-- Service Information Section -->
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-info-circle text-primary me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Thông tin dịch vụ</h5>
                                        </div>
                                        <div class="rounded p-3 bg-light">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="info-item">
                                                        <small class="text-muted d-block">Tên dịch vụ</small>
                                                        <strong class="text-dark">{{ $service->name }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-item">
                                                        <small class="text-muted d-block">Loại dịch vụ</small>
                                                        <strong class="text-dark">{{ $service->category->name ?? '---' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-item">
                                                        <small class="text-muted d-block">Giá dịch vụ</small>
                                                        <strong class="text-success">{{ number_format($service->price) }} đ</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-item">
                                                        <small class="text-muted d-block">Thời lượng</small>
                                                        <strong class="text-info">{{ $service->duration }} phút</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description Section -->
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-file-blank text-primary me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Mô tả dịch vụ</h5>
                                        </div>
                                        <div class="rounded p-3 bg-light">
                                            <div class="service-description">
                                                {{ $service->description ?? 'Không có mô tả' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-lg-4">
                                    <!-- Information Cards -->
                                    <div class="row g-3">
                                        <!-- Status Card -->
                                        <div class="col-12">
                                            <div class="card border-left-{{ $service->status == 'active' ? 'success' : 'danger' }}">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-{{ $service->status == 'active' ? 'check-circle' : 'x-circle' }} text-{{ $service->status == 'active' ? 'success' : 'danger' }} me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Trạng thái</small>
                                                            <strong>{{ $service->status == 'active' ? 'Hoạt động' : 'Tạm ngừng' }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Category Card -->
                                        <div class="col-12">
                                            <div class="card border-left-info">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-category text-info me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Danh mục</small>
                                                            <strong>{{ $service->category->name ?? 'Không xác định' }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Price Card -->
                                        <div class="col-12">
                                            <div class="card border-left-success">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-money text-success me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Giá dịch vụ</small>
                                                            <strong>{{ number_format($service->price) }} đ</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Duration Card -->
                                        <div class="col-12">
                                            <div class="card border-left-warning">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-time text-warning me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Thời lượng</small>
                                                            <strong>{{ $service->duration }} phút</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Timestamps -->
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bx bx-time me-2"></i>Thông tin thời gian
                                            </h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="timeline-item mb-2">
                                                <small class="text-muted d-block">Ngày tạo</small>
                                                <span class="fw-medium">{{ $service->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="timeline-item">
                                                <small class="text-muted d-block">Cập nhật cuối</small>
                                                <span class="fw-medium">{{ $service->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('admin.services.index') }}" 
                                           class="btn btn-outline-secondary waves-effect">
                                            <i class="bx bx-arrow-back me-1"></i>Quay lại danh sách
                                        </a>
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-primary" href="{{ route('admin.services.edit', $service) }}">
                                                <i class="bx bx-edit-alt me-1"></i>Chỉnh sửa
                                            </a>
                                            <button type="button" class="btn btn-outline-info">
                                                <i class="bx bx-copy me-1"></i>Sao chép
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .border-left-primary {
            border-left: 4px solid var(--bs-primary) !important;
        }
        .border-left-info {
            border-left: 4px solid var(--bs-info) !important;
        }
        .border-left-warning {
            border-left: 4px solid var(--bs-warning) !important;
        }
        .border-left-success {
            border-left: 4px solid var(--bs-success) !important;
        }
        .border-left-danger {
            border-left: 4px solid var(--bs-danger) !important;
        }
        
        .service-description {
            line-height: 1.6;
            font-size: 0.95rem;
        }
        
        .avatar {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .info-item {
            padding: 8px 0;
        }
        
        .card {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .timeline-item:not(:last-child) {
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }
        
        .btn-group .btn {
            border-radius: 0.375rem;
            margin-left: 0.25rem;
        }
        
        .timeline-item {
            border-left: none !important;
        }
        
        @media (max-width: 768px) {
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }
            
            .btn-group {
                width: 100%;
            }
            
            .btn-group .btn {
                flex: 1;
            }
        }
    </style>
@endsection