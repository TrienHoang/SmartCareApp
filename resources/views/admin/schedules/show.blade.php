@extends('admin.dashboard')
@section('title', 'Chi tiết Lịch làm việc')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar me-2 text-primary" style="font-size: 24px;"></i>
                        <h2 class="content-header-title mb-0">Chi tiết Lịch làm việc</h2>
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
                                    <a href="{{ route('admin.schedules.index') }}" class="text-decoration-none">
                                        Lịch làm việc >
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
                <!-- Schedule Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar-wrapper me-3">
                                    <div class="avatar avatar-lg bg-primary">
                                        <i class="bx bx-calendar text-white" style="font-size: 20px;"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="card-title text-xl mb-1">Lịch làm việc #{{ $schedule->id }}</h4>
                                    <small class="text-muted">Bác sĩ: {{ $schedule->doctor->user->full_name ?? 'Không rõ tên' }}</small>
                                </div>
                            </div>
                            <div class="status-badge">
                                <span class="badge
                                    @if ($schedule->status == 'active') bg-success
                                    @elseif ($schedule->status == 'inactive') bg-secondary
                                    @else bg-info @endif
                                    rounded-pill px-3 py-2 text-capitalize">
                                    <i class="bx
                                        @if ($schedule->status == 'active') bx-check-circle
                                        @elseif ($schedule->status == 'inactive') bx-x-circle
                                        @else bx-info-circle @endif
                                        me-1"></i>
                                    {{ $schedule->status ?? 'Không xác định' }}
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
                                        <i class="bx bx-calendar-event text-primary me-2" style="font-size: 18px;"></i>
                                        <h5 class="mb-0">Thông tin lịch làm việc</h5>
                                    </div>
                                    <div class="rounded p-3">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4">Ngày làm việc:</dt>
                                            <dd class="col-sm-8">{{ \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') }} ({{ $schedule->day_of_week }})</dd>

                                            <dt class="col-sm-4">Giờ bắt đầu:</dt>
                                            <dd class="col-sm-8">{{ $schedule->start_time }}</dd>

                                            <dt class="col-sm-4">Giờ kết thúc:</dt>
                                            <dd class="col-sm-8">{{ $schedule->end_time }}</dd>

                                            <dt class="col-sm-4">Ghi chú:</dt>
                                            <dd class="col-sm-8">{{ $schedule->note ?? 'Không có ghi chú' }}</dd>
                                        </dl>
                                    </div>
                                </div>

                                <!-- Doctor Section -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bx bx-user text-primary me-2" style="font-size: 18px;"></i>
                                        <h5 class="mb-0">Thông tin bác sĩ</h5>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($schedule->doctor->user->avatar)
                                            <img src="{{ Storage::url($schedule->doctor->user->avatar) }}" class="img-thumbnail rounded-circle shadow-sm" style="width: 70px; height: 70px;" alt="Avatar">
                                        @else
                                            <div class="bg-light border rounded-circle d-flex justify-content-center align-items-center" style="width: 70px; height: 70px;">
                                                <i class="bx bx-user text-muted" style="font-size: 32px;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $schedule->doctor->user->full_name ?? 'Không rõ tên' }}</div>
                                            <div class="text-muted small">{{ $schedule->doctor->specialization ?? 'Chuyên môn không rõ' }}</div>
                                            <div class="text-muted small">Phòng: {{ $schedule->doctor->room->name ?? 'Không rõ' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <!-- Information Cards -->
                                <div class="row g-3">
                                    <!-- ID Card -->
                                    <div class="col-12">
                                        <div class="card border-left-primary">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-hash text-primary me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Mã lịch</small>
                                                        <strong>#{{ $schedule->id }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Day of week Card -->
                                    <div class="col-12">
                                        <div class="card border-left-info">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-calendar-week text-info me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Thứ</small>
                                                        <strong>{{ $schedule->day_of_week }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Start/End Time Card -->
                                    <div class="col-12">
                                        <div class="card border-left-warning">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-time text-warning me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Thời gian</small>
                                                        <strong>{{ $schedule->start_time }} - {{ $schedule->end_time }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Status Card -->
                                    <div class="col-12">
                                        <div class="card border-left-success">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-info-circle text-success me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Trạng thái</small>
                                                        <strong class="text-capitalize">{{ $schedule->status ?? 'Không xác định' }}</strong>
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
                                            <span class="fw-medium">
                                                {{ $schedule->created_at ? \Carbon\Carbon::parse($schedule->created_at)->format('d/m/Y H:i') : 'Không xác định' }}
                                            </span>
                                        </div>
                                        <div class="timeline-item">
                                            <small class="text-muted d-block">Cập nhật cuối</small>
                                            <span class="fw-medium">
                                                {{ $schedule->updated_at ? \Carbon\Carbon::parse($schedule->updated_at)->format('d/m/Y H:i') : 'Không xác định' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('admin.schedules.index') }}"
                                       class="btn btn-outline-secondary waves-effect">
                                        <i class="bx bx-arrow-back me-1"></i>Quay lại danh sách
                                    </a>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-outline-primary" href="{{ route('admin.schedules.edit', $schedule->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i>Chỉnh sửa
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
</div>
@endsection

@push('styles')
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
@endpush

@push('scripts')
<script>
    // No extra JS needed, but you can add tooltip or feather icon init here if needed
</script>
@endpush
