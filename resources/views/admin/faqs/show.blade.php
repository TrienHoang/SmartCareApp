@extends('admin.dashboard')
@section('title', 'Chi tiết Câu hỏi')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-question-mark me-2 text-primary" style="font-size: 24px;"></i>
                            <h2 class="content-header-title mb-0">Chi tiết Câu hỏi</h2>
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
                                        <a href="{{ route('admin.faqs.index') }}" class="text-decoration-none">
                                            Câu hỏi thường gặp >
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
                                            <i class="bx bx-question-mark text-white" style="font-size: 20px;"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="card-title text-xl mb-1">Câu hỏi #{{ $faq->id }}</h4>
                                        <small class="text-muted">{{ $faq->question }}</small>
                                    </div>
                                </div>
                                <div class="status-badge">
                                    <span
                                        class="badge {{ $faq->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                                        <i class="bx {{ $faq->is_active ? 'bx-check-circle' : 'bx-hide' }} me-1"></i>
                                        {{ $faq->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-chat text-primary me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Nội dung Câu hỏi & Trả lời</h5>
                                        </div>
                                        <div class="rounded p-3 bg-light-subtle">
                                            <p class="text-primary text-xl mb-2"><strong>Câu hỏi:</strong>
                                                {{ $faq->question }}</p>
                                            <div class="faq-answer-content">
                                                <strong>Câu trả lời:</strong>
                                                <div>{{ $faq->answer }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="card border-left-info">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-category text-info me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Danh mục</small>
                                                            <strong>{{ $categoryName ?? 'Không xác định' }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bx bx-time me-2"></i>Thông tin thời gian
                                            </h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="timeline-item mb-2">
                                                <small class="text-muted d-block">Ngày tạo</small>
                                                <span class="fw-medium">{{ $faq->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="timeline-item">
                                                <small class="text-muted d-block">Ngày cập nhật</small>
                                                <span class="fw-medium">{{ $faq->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start">
                                        <a href="{{ route('admin.faqs.index') }}"
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

        .faq-answer-content {
            line-height: 1.6;
            font-size: 0.95rem;
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
