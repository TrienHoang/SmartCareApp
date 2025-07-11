@extends('admin.dashboard')
@section('title', 'Chi tiết Đơn hàng')

@section('content')
    <div class="content-wrapper">
        <!-- Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-receipt me-2 text-primary" style="font-size: 24px;"></i>
                            <h2 class="content-header-title mb-0">Chi tiết Đơn hàng</h2>
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
                                        <a href="{{ route('orders.index') }}" class="text-decoration-none">
                                            Đơn hàng > 
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
            {{-- Thông báo --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <!-- Order Card -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper me-3">
                                        <div class="avatar avatar-lg bg-primary">
                                            <i class="bx bx-receipt text-white" style="font-size: 20px;"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="card-title text-xl mb-1">Đơn hàng #{{ $order->id }}</h4>
                                        <small class="text-muted">{{ $order->user->full_name }}</small>
                                    </div>
                                </div>
                                <div class="status-badge">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Chờ xác nhận',
                                            'paid' => 'Đã thanh toán',
                                            'completed' => 'Hoàn tất',
                                            'cancelled' => 'Đã hủy',
                                        ];
                                    @endphp
                                    <span class="badge
                                        @if ($order->status == 'completed') bg-success
                                        @elseif ($order->status == 'paid') bg-info 
                                        @elseif ($order->status == 'pending') bg-warning
                                        @elseif ($order->status == 'cancelled') bg-danger
                                        @else bg-dark @endif
                                        rounded-pill px-3 py-2">
                                        <i class="bx 
                                            @if ($order->status == 'completed') bx-check-circle
                                            @elseif ($order->status == 'paid') bx-credit-card
                                            @elseif ($order->status == 'pending') bx-time-five
                                            @elseif ($order->status == 'cancelled') bx-x-circle
                                            @else bx-help-circle @endif
                                            me-1"></i>
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Left Column -->
                                <div class="col-lg-8">
                                    <!-- Services Section -->
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-list-ul text-primary me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Dịch vụ đã chọn</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="60">#</th>
                                                        <th>Tên dịch vụ</th>
                                                        <th width="150" class="text-end">Giá</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->services as $index => $service)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="bx bx-cog me-2 text-muted"></i>
                                                                    {{ $service->name }}
                                                                </div>
                                                            </td>
                                                            <td class="text-end">
                                                                <span class="fw-medium">{{ number_format($service->price, 0, ',', '.') }}đ</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <td colspan="2" class="text-end fw-bold">Tổng tiền:</td>
                                                        <td class="text-end">
                                                            <span class="fw-bold text-primary fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Status Update Section -->
                                    @php
                                        $statusTransitions = [
                                            'pending' => ['paid', 'cancelled'],
                                            'paid' => ['completed', 'cancelled'],
                                            'completed' => [],
                                            'cancelled' => [],
                                        ];
                                        $nextStatuses = $statusTransitions[$order->status] ?? [];
                                    @endphp

                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-refresh text-primary me-2" style="font-size: 18px;"></i>
                                            <h5 class="mb-0">Cập nhật trạng thái</h5>
                                        </div>
                                        
                                        @if (empty($nextStatuses))
                                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                                <i class="bx bx-info-circle me-2"></i>
                                                <span>Đơn hàng đã {{ $order->status === 'completed' ? 'hoàn tất' : 'bị hủy' }}. Không thể thay đổi trạng thái.</span>
                                            </div>
                                        @else
                                            <form method="POST" action="{{ route('orders.updateStatus', $order) }}" class="card p-3 bg-light">
                                                @csrf
                                                <div class="row align-items-end g-3">
                                                    <div class="col-md-6">
                                                        <label for="status" class="form-label">Chọn trạng thái mới:</label>
                                                        <select name="status" id="status" class="form-select">
                                                            @foreach ($nextStatuses as $status)
                                                                <option value="{{ $status }}">
                                                                    {{ $statusLabels[$status] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="submit" class="btn btn-success w-100">
                                                            <i class="bx bx-save me-1"></i>Cập nhật trạng thái
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-lg-4">
                                    <!-- Information Cards -->
                                    <div class="row g-3">
                                        <!-- Customer Card -->
                                        <div class="col-12">
                                            <div class="card border-left-primary">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-user text-primary me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Khách hàng</small>
                                                            <strong>{{ $order->user->full_name }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Total Amount Card -->
                                        <div class="col-12">
                                            <div class="card border-left-success">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-money text-success me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Tổng tiền</small>
                                                            <strong class="text-success">{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Order Time Card -->
                                        <div class="col-12">
                                            <div class="card border-left-info">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-calendar text-info me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Thời gian đặt</small>
                                                            <strong>{{ $order->ordered_at }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Services Count Card -->
                                        <div class="col-12">
                                            <div class="card border-left-warning">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-layer text-warning me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Số dịch vụ</small>
                                                            <strong>{{ $order->services->count() }} dịch vụ</strong>
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
                                                <span class="fw-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="timeline-item">
                                                <small class="text-muted d-block">Cập nhật cuối</small>
                                                <span class="fw-medium">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('orders.index') }}" 
                                           class="btn btn-outline-secondary waves-effect">
                                            <i class="bx bx-arrow-back me-1"></i>Quay lại danh sách
                                        </a>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('orders.exportPdf', $order) }}" class="btn btn-outline-danger">
                                                <i class="bx bxs-file-pdf me-1"></i>Xuất PDF
                                            </a>
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
        
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .table th {
            font-weight: 600;
            color: #5a5c69;
            border-top: none;
        }
        
        .table tfoot td {
            border-top: 2px solid #dee2e6;
            font-weight: 600;
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