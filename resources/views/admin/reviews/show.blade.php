@extends('admin.dashboard')
@section('title', 'Chi tiết Đánh giá')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-star me-2 text-warning" style="font-size: 24px;"></i>
                            <h2 class="content-header-title mb-0">Chi tiết Đánh giá</h2>
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
                                        <a href="{{ route('admin.reviews.index') }}" class="text-decoration-none">
                                            Đánh giá >
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
                                        <div class="avatar avatar-lg bg-warning">
                                            <i class="bx bx-star text-white" style="font-size: 20px;"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="card-title text-xl mb-1">Đánh giá #{{ $review->id }}</h4>
                                        <small class="text-muted">Đánh giá từ bệnh nhân
                                            {{ $review->patient->full_name ?? '---' }}</small>
                                    </div>
                                </div>
                                <div class="rating-badge">
                                    <span class="badge bg-warning rounded-pill px-3 py-2">
                                        <i class="bx bx-star me-1"></i> {{ $review->rating }} Sao
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-comment-detail text-primary me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Chi tiết Đánh giá</h5>
                                        </div>
                                        <div class="rounded p-3 bg-light-subtle">
                                            <p class="mb-2"><strong>Nội dung:</strong> {{ $review->comment }}</p>
                                            <p class="mb-0"><strong>Trạng thái:</strong>
                                                <span
                                                    class="badge {{ $review->is_visible ? 'bg-success' : 'bg-danger' }} rounded-pill px-2">
                                                    {{ $review->is_visible ? 'Hiển thị' : 'Ẩn' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-user-plus text-info me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Thông tin liên quan</h5>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="card border-left-primary">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-user text-primary me-2"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Bệnh nhân</small>
                                                                <strong>{{ $review->patient->full_name ?? '---' }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-left-info">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-user-md text-info me-2"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Bác sĩ</small>
                                                                <strong>{{ $review->doctor->user->full_name ?? '---' }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="card border-left-success">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-notepad text-success me-2"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Dịch vụ</small>
                                                                <strong>{{ $review->service->name ?? '---' }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                                <small class="text-muted d-block">Ngày đánh giá</small>
                                                <span
                                                    class="fw-medium">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="timeline-item">
                                                <small class="text-muted d-block">Cập nhật cuối</small>
                                                <span
                                                    class="fw-medium">{{ $review->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start">
                                        <a href="{{ route('admin.reviews.index') }}"
                                            class="btn btn-outline-secondary waves-effect">
                                            <i class="bx bx-arrow-back me-1"></i>Quay lại danh sách
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        .avatar {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
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

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        /* Responsive adjustments */
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
