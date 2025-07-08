@extends('admin.dashboard')
@section('title', 'Chi tiết Lịch hẹn khám')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Chi tiết Lịch hẹn khám</h1>
                <p class="text-muted mb-0">Mã lịch hẹn: #{{ $appointment->id }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Main Info Card -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-calendar-check text-primary me-2"></i>
                            Thông tin lịch hẹn
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Patient Info -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-circle bg-primary-subtle text-primary">
                                            <i class="bx bx-user"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Bệnh nhân</h6>
                                        <p class="text-dark mb-1">{{ $appointment->patient->full_name ?? 'N/A' }}</p>
                                        <small class="text-muted">
                                            <i class="bx bx-phone me-1"></i>
                                            {{ $appointment->patient->phone ?? 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Doctor Info -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-circle bg-success-subtle text-success">
                                            <i class="bx bx-user-check"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Bác sĩ</h6>
                                        <p class="text-dark mb-1">{{ $appointment->doctor->user->full_name ?? 'N/A' }}</p>
                                        <small class="text-muted">
                                            <i class="bx bx-map-pin me-1"></i>
                                            {{ $appointment->doctor->room->name ?? 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Info -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-circle bg-info-subtle text-info">
                                            <i class="bx bx-health"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Dịch vụ khám</h6>
                                        <p class="text-dark mb-0">{{ $appointment->service->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Time -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-circle bg-warning-subtle text-warning">
                                            <i class="bx bx-time"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Ngày giờ khám</h6>
                                        <p class="text-dark mb-0">{{ $appointment->formatted_time }}</p>
                                        @php
                                            use Carbon\Carbon;
                                            $now = Carbon::now();
                                            $appointmentTime = Carbon::parse($appointment->appointment_time);
                                            $shouldShowEndTime = $appointmentTime->isPast() || $appointment->status === 'completed';
                                        @endphp
                                        @if ($shouldShowEndTime)
                                            <small class="text-muted">
                                                Kết thúc: {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        @if($appointment->reason)
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="mb-2">
                                <i class="bx bx-note text-muted me-2"></i>
                                Ghi chú
                            </h6>
                            <p class="mb-0 text-muted">{{ $appointment->reason }}</p>
                        </div>
                        @endif

                        <!-- Cancel Reason -->
                        @if($appointment->cancel_reason)
                        <div class="mt-3 p-3 bg-danger-subtle rounded">
                            <h6 class="mb-2 text-danger">
                                <i class="bx bx-x-circle me-2"></i>
                                Lí do hủy
                            </h6>
                            <p class="mb-0 text-danger">{{ $appointment->cancel_reason }}</p>
                        </div>
                        @endif

                        <!-- Completion Notes -->
                        @if($appointment->status === 'completed')
                        @php
                            $completionNote = optional($appointment->logs->where('status_after', 'completed')->sortByDesc('change_time')->first())->note;
                        @endphp
                        @if($completionNote)
                        <div class="mt-3 p-3 bg-success-subtle rounded">
                            <h6 class="mb-2 text-success">
                                <i class="bx bx-check-circle me-2"></i>
                                Ghi chú sau khi hoàn thành
                            </h6>
                            <p class="mb-0 text-success">{{ $completionNote }}</p>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status & Payment Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-info-circle text-info me-2"></i>
                            Trạng thái & Thanh toán
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Trạng thái lịch hẹn</label>
                            <div>
                                @php
                                    $statusConfig = [
                                        'pending' => ['color' => 'warning', 'text' => 'Chờ xác nhận', 'icon' => 'bx-time'],
                                        'confirmed' => ['color' => 'info', 'text' => 'Đã xác nhận', 'icon' => 'bx-check'],
                                        'completed' => ['color' => 'success', 'text' => 'Hoàn thành', 'icon' => 'bx-check-double'],
                                        'cancelled' => ['color' => 'danger', 'text' => 'Đã hủy', 'icon' => 'bx-x'],
                                    ];
                                    $config = $statusConfig[$appointment->status] ?? ['color' => 'secondary', 'text' => $appointment->status, 'icon' => 'bx-help'];
                                @endphp
                                <span class="badge bg-{{ $config['color'] }} badge-lg">
                                    <i class="bx {{ $config['icon'] }} me-1"></i>
                                    {{ $config['text'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Thanh toán</label>
                            <div>
                                @if ($appointment->payment && $appointment->payment->status === 'paid')
                                    <span class="badge bg-success badge-lg">
                                        <i class="bx bx-check-circle me-1"></i>
                                        Đã thanh toán
                                    </span>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bx bx-time me-1"></i>
                                            {{ $appointment->payment->paid_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                @else
                                    <span class="badge bg-danger badge-lg">
                                        <i class="bx bx-x-circle me-1"></i>
                                        Chưa thanh toán
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tổng tiền</label>
                            <div class="h4 text-primary mb-0">
                                {{ number_format($appointment->payment->amount ?? 0, 0, ',', '.') }}đ
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="border-top pt-3">
                            <div class="row g-3">
                                <div class="col-12">
                                    <small class="text-muted d-block">Ngày tạo</small>
                                    <span class="text-dark">{{ $appointment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted d-block">Cập nhật lần cuối</small>
                                    <span class="text-dark">{{ $appointment->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-history text-secondary me-2"></i>
                            Lịch sử cập nhật
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($appointment->logs->isEmpty())
                            <div class="text-center py-4">
                                <i class="bx bx-history text-muted" style="font-size: 48px;"></i>
                                <p class="text-muted mt-2 mb-0">Không có lịch sử cập nhật.</p>
                            </div>
                        @else
                            <div class="timeline">
                                @foreach ($appointment->logs->sortByDesc('change_time') as $log)
                                    <div class="timeline-item">
                                        <div class="timeline-marker">
                                            <i class="bx bx-user"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <h6 class="mb-0">{{ $log->user->full_name ?? 'N/A' }}</h6>
                                                <small class="text-muted">
                                                    <i class="bx bx-time me-1"></i>
                                                    {{ $log->change_time->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            @if($log->note)
                                                <div class="timeline-body mt-2">
                                                    <div class="bg-light p-3 rounded">
                                                        <pre class="mb-0 text-muted">{{ $log->note }}</pre>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .badge-lg {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 16px;
        }

        .timeline-header {
            display: flex;
            justify-content: between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .timeline-body pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: inherit;
            font-size: 0.875rem;
            line-height: 1.4;
        }

        .card {
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1) !important;
        }

        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .text-gray-800 {
            color: #2d3748 !important;
        }

        @media (max-width: 768px) {
            .timeline {
                padding-left: 20px;
            }
            
            .timeline-marker {
                left: -20px;
                width: 30px;
                height: 30px;
                font-size: 14px;
            }
        }
    </style>
@endsection