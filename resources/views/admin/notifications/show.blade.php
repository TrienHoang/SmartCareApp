@extends('admin.dashboard')
@section('title', 'Chi tiết Thông báo')

@section('content')
    <div class="content-wrapper">
        <!-- Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-bell me-2 text-primary" style="font-size: 24px;"></i>
                            <h2 class="content-header-title mb-0">Chi tiết Thông báo</h2>
                        </div>
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                           </i>Trang chủ > 
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
                                            </i>Thông báo > 
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
                    <!-- Notification Card -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper me-3">
                                        <div class="avatar avatar-lg bg-primary">
                                            <i class="bx bx-bell text-white" style="font-size: 20px;"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="card-title text-xl mb-1">Thông báo #{{ $notification->id }}</h4>
                                        <small class="text-muted">{{ $notification->title }}</small>
                                    </div>
                                </div>
                                <div class="status-badge">
                                    <span class="badge
                                        @if ($notification->status == 'sent') bg-success
                                        @elseif ($notification->status == 'scheduled') bg-info 
                                        @elseif ($notification->status == 'sending') bg-warning
                                        @elseif ($notification->status == 'failed') bg-danger
                                        @else bg-dark @endif
                                        rounded-pill px-3 py-2">
                                        <i class="bx 
                                            @if ($notification->status == 'sent') bx-check-circle
                                            @elseif ($notification->status == 'scheduled') bx-time-five
                                            @elseif ($notification->status == 'sending') bx-loader-alt bx-spin
                                            @elseif ($notification->status == 'failed') bx-x-circle
                                            @else bx-help-circle @endif
                                            me-1"></i>
                                        {{ ucfirst($notification->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Left Column -->
                                <div class="col-lg-8">
                                    <!-- Content Section -->
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-file-blank text-primary me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Nội dung thông báo</h5>
                                        </div>
                                        <div class=" rounded p-3">
                                            <h4 class="text-primary text-xl mb-2">{{ $notification->title }}</h4>
                                            <div class="notification-content">
                                                {!! $notification->content !!}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Recipients Section -->
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-group text-primary me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Người nhận</h5>
                                        </div>
                                        <div class="recipients-list">
                                            @if ($notification->recipient_type === 'all')
                                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                                    <i class="bx bx-world me-2"></i>
                                                    <span>Tất cả người dùng trong hệ thống</span>
                                                </div>
                                            @elseif ($notification->recipient_type === 'specific_users' && !empty($notification->display_recipients))
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($notification->display_recipients as $recipient)
                                                        <span class="badge bg-label-info rounded-pill px-3 py-2">
                                                            <i class="bx bx-user me-1"></i>{{ $recipient }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @elseif ($notification->recipient_type === 'roles' && !empty($notification->display_recipients))
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($notification->display_recipients as $recipient)
                                                        <span class="badge bg-label-warning rounded-pill px-3 py-2">
                                                            <i class="bx bx-shield me-1"></i>{{ $recipient }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="bx bx-error me-2"></i>
                                                    <span>Không xác định</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-lg-4">
                                    <!-- Information Cards -->
                                    <div class="row g-3">
                                        <!-- Type Card -->
                                        <div class="col-12">
                                            <div class="card border-left-primary">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-category text-primary me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Loại thông báo</small>
                                                            <strong>{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sender Card -->
                                        <div class="col-12">
                                            <div class="card border-left-info">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-user-circle text-info me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Người gửi</small>
                                                            {{-- <strong>
                                                                {{ $notification->sender_id ? $notification->sender->name : 'Hệ thống' }}
                                                            </strong> --}}
                                                            @if ($notification->sender_id)
                                                                <strong >Hệ thống</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Schedule Card -->
                                        <div class="col-12">
                                            <div class="card border-left-warning">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-calendar text-warning me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Lên lịch</small>
                                                            <strong>
                                                                {{ $notification->scheduled_at ? $notification->scheduled_at->format('d/m/Y H:i') : 'Gửi ngay' }}
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sent Time Card -->
                                        <div class="col-12">
                                            <div class="card border-left-success">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-send text-success me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Thời gian gửi</small>
                                                            <strong>
                                                                {{ $notification->sent_at ? $notification->sent_at->format('d/m/Y H:i') : 'Chưa gửi' }}
                                                            </strong>
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
                                                <span class="fw-medium">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="timeline-item">
                                                <small class="text-muted d-block">Cập nhật cuối</small>
                                                <span class="fw-medium">{{ $notification->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('admin.notifications.index') }}" 
                                           class="btn btn-outline-secondary waves-effect">
                                            <i class="bx bx-arrow-back me-1"></i>Quay lại danh sách
                                        </a>
                                        <div class="btn-group" role="group">
                                            @if($notification->status !== 'sent')
                                                <a class="btn btn-outline-primary" href="{{ route('admin.notifications.edit', $notification) }}">
                                                    <i class="bx bx-edit-alt me-1"></i>Chỉnh sửa
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-outline-info">
                                                <i class="bx bx-share me-1"></i>Chia sẻ
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
        
        .notification-content {
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
        
        .recipients-list .badge {
            font-size: 0.85rem;
            font-weight: 500;
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
        
        .alert {
            border: none;
            border-radius: 0.5rem;
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

