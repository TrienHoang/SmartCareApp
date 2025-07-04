@extends('admin.dashboard')

@section('title', '🧾 Lịch sử thanh toán')

@section('content')
<div class="content-wrapper">
    <!-- Enhanced Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-8 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary mr-3 ">
                            <i class="bx bx-history text-white"></i>
                        </div>
                        <div>
                            <h2 class="content-header-title mb-0 text-primary font-weight-bold">Lịch sử thanh toán</h2>
                            <p class="text-muted mb-0">Quản lý và theo dõi tất cả giao dịch thanh toán trong hệ thống</p>
                        </div>
                    </div>
                    {{-- Thống kê nhanh --}}
                    {{-- End thống kê nhanh --}}
                    <div class="breadcrumb-wrapper col-12 mt-2">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0">
                                <li class="">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                        Trang chủ >
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-primary font-weight-semibold">
                                    Lịch sử thanh toán
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-4 col-12 text-md-right">
            <div class="form-group breadcrum-right">
                {{-- Có thể thêm nút xuất file hoặc tạo mới nếu cần --}}
            </div>
        </div>
    </div>

    <div class="content-body">
        {{-- Thông báo --}}
        @foreach (['success', 'error'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx {{ $msg == 'success' ? 'bx-check-circle' : 'bx-x-circle' }} mr-2"></i>
                        <strong>{{ $msg == 'success' ? 'Thành công!' : 'Lỗi!' }}</strong> {{ session($msg) }}
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card gradient-card bg-gradient-success">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-white mr-2">
                                <div class="avatar-content">
                                    <i class="bx bx-check-double font-medium-5"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white mb-0">
                                    {{ $stat['paid_count'] ?? ($stat['paid'] ?? 0) }}
                                </h4>
                                <small class="text-white">Đã thanh toán</small>
                                <div>
                                    <small>Tổng tiền: <span class="fw-bold">
                                        {{ number_format($stat['paid_amount'] ?? 0, 0, ',', '.') }}₫
                                    </span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card gradient-card bg-gradient-warning">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-white mr-2">
                                <div class="avatar-content">
                                    <i class="bx bx-time-five font-medium-5"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white mb-0">
                                    {{ $stat['pending_count'] ?? ($stat['pending'] ?? 0) }}
                                </h4>
                                <small class="text-white">Chờ xử lý</small>
                                <div>
                                    <small>Tổng tiền: <span class="fw-bold">
                                        {{ number_format($stat['pending_amount'] ?? 0, 0, ',', '.') }}₫
                                    </span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card gradient-card bg-gradient-danger">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-white mr-2">
                                <div class="avatar-content">
                                    <i class="bx bx-x font-medium-5"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white mb-0">
                                    {{ $stat['failed_count'] ?? ($stat['failed'] ?? 0) }}
                                </h4>
                                <small class="text-white">Thất bại</small>
                                <div>
                                    <small>Tổng tiền: <span class="fw-bold">
                                        {{ number_format($stat['failed_amount'] ?? 0, 0, ',', '.') }}₫
                                    </span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card gradient-card bg-gradient-info">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-white mr-2">
                                <div class="avatar-content">
                                    <i class="bx bx-receipt font-medium-5"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white mb-0">{{ $histories->total() }}</h4>
                                <small class="text-white">Tổng giao dịch</small>
                                <div>
                                    <small>Tổng tiền: <span class="fw-bold">
                                        {{ number_format($stat['total_amount'] ?? 0, 0, ',', '.') }}₫
                                    </span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form lọc --}}
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.payment_histories.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Tên bệnh nhân</label>
                        <input type="text" name="patient_name" class="form-control" value="{{ request('patient_name') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Dịch vụ</label>
                        <select name="service_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Bác sĩ</label>
                        <select name="doctor_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-filter-alt"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bảng dữ liệu --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-primary text-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-list mr-2"></i>
                        <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách giao dịch</h4>
                    </div>
                    <div class="card-tools">
                        <span class="badge badge-light">{{ $histories->total() }} giao dịch</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center mb-0 table-modern">
                        <thead class="table-light text-dark">
                            <tr>
                                <th>#</th>
                                <th>Mã</th>
                                <th class="text-start">Bệnh nhân</th>
                                <th>Dịch vụ</th>
                                <th class="d-none d-md-table-cell">Bác sĩ</th>
                                <th>Ngày</th>
                                <th>Trạng thái</th>
                                <th>Số tiền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $history)
                                @php
                                    $status = $history->payment->status ?? null;
                                    $statusMap = [
                                        'paid'    => ['label' => 'Đã thanh toán', 'class' => 'bg-success'],
                                        'pending' => ['label' => 'Chờ xử lý',      'class' => 'bg-warning text-dark'],
                                        'failed'  => ['label' => 'Thất bại',       'class' => 'bg-danger'],
                                    ];
                                    $statusLabel = $statusMap[$status] ?? ['label' => 'Không xác định', 'class' => 'bg-secondary'];
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}</td>
                                    <td><span class="badge bg-label-primary">#{{ $history->payment->id }}</span></td>
                                    <td class="text-start">
                                        <strong>{{ optional($history->payment->appointment->patient)->full_name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ optional($history->payment->appointment->patient)->phone }}</small>
                                    </td>
                                    <td>{{ optional($history->payment->appointment->service)->name ?? 'N/A' }}</td>
                                    <td class="d-none d-md-table-cell">{{ optional($history->payment->appointment->doctor->user)->full_name ?? 'N/A' }}</td>
                                    <td>{{ optional($history->payment_date)->format('d/m/Y H:i') ?? 'Chưa TT' }}</td>
                                    <td><span class="badge {{ $statusLabel['class'] }}">{{ $statusLabel['label'] }}</span></td>
                                    <td><strong class="text-success">{{ number_format($history->amount, 0, ',', '.') }}₫</strong></td>
                                    <td>
                                        <a href="{{ route('admin.payment_histories.show', $history->id) }}" class="btn btn-sm btn-outline-info" title="Chi tiết">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-muted py-4">Không có dữ liệu phù hợp.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Phân trang --}}
                @if($histories->hasPages())
                    <div class="pagination-wrapper bg-light p-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="pagination-info">
                                <small class="text-muted">
                                    Hiển thị {{ $histories->firstItem() }} - {{ $histories->lastItem() }} trong tổng số {{ $histories->total() }} kết quả
                                </small>
                            </div>
                            <div class="pagination-links">
                                {{ $histories->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- ===== CSS & Responsive giữ nguyên hoặc bổ sung thêm nếu muốn ===== --}}
<style>
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-circle i { margin: 0px auto }
    .badge-success { background-color: #39DA8A; color: #fff; }
    .badge-info { background-color: #00CFDD; color: #fff; }
    .badge-warning { background-color: #FDAC41; color: #212529; }
    .badge-danger { background-color: #FF5B5C; color: #fff; }
    .badge-secondary { background-color: #6c757d; color: #fff; }
    .badge-pill { border-radius: 10rem; padding: 0.25em 0.6em; }
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .gradient-card { border: none; border-radius: 10px; transition: transform 0.3s ease; }
    .gradient-card:hover { transform: translateY(-2px); }
    .bg-gradient-success { background: linear-gradient(135deg, #00cec9 0%, #55a3ff 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%); }
    .table-modern { font-size: 0.9rem; }
    .table-modern td { vertical-align: middle; padding: 1rem 0.75rem; }
    .pagination-wrapper { background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%); }
    .avatar { border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .bg-rgba-white { background-color: rgba(255, 255, 255, 0.2); }
    @media (max-width: 768px) {
        .table th, .table td { font-size: 13px !important; padding: 8px; }
        .d-none.d-md-table-cell { display: none !important; }
        h4 { font-size: 18px; }
    }
</style>
@endsection
