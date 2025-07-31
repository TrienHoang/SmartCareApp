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
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-history text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Lịch sử thanh toán</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả giao dịch thanh toán trong hệ thống
                                </p>
                            </div>
                        </div>
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
                    <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show border-0 shadow-3d alert-3d"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bx {{ $msg == 'success' ? 'bx-check-circle' : 'bx-x-circle' }} mr-2"></i>
                            <strong>{{ $msg == 'success' ? 'Thành công!' : 'Lỗi!' }}</strong> {{ session($msg) }}
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12 mb-3">
                    <div class="card gradient-card bg-gradient-success card-3d">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-3 avatar-3d">
                                    <div class="avatar-content">
                                        <i class="bx bx-check-double font-medium-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="text-white mb-1 counter-number">
                                        {{ $stat['paid_count'] ?? ($stat['paid'] ?? 0) }}
                                    </h4>
                                    <small class="text-white d-block mb-1">Đã thanh toán</small>
                                    <small class="text-white opacity-75">
                                        Tổng: <span
                                            class="fw-bold">{{ number_format($stat['paid_amount'] ?? 0, 0, ',', '.') }}₫</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-3">
                    <div class="card gradient-card bg-gradient-warning card-3d">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-3 avatar-3d">
                                    <div class="avatar-content">
                                        <i class="bx bx-time-five font-medium-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="text-white mb-1 counter-number">
                                        {{ $stat['pending_count'] ?? ($stat['pending'] ?? 0) }}
                                    </h4>
                                    <small class="text-white d-block mb-1">Chờ xử lý</small>
                                    <small class="text-white opacity-75">
                                        Tổng: <span
                                            class="fw-bold">{{ number_format($stat['pending_amount'] ?? 0, 0, ',', '.') }}₫</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-3">
                    <div class="card gradient-card bg-gradient-danger card-3d">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-3 avatar-3d">
                                    <div class="avatar-content">
                                        <i class="bx bx-x font-medium-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="text-white mb-1 counter-number">
                                        {{ $stat['failed_count'] ?? ($stat['failed'] ?? 0) }}
                                    </h4>
                                    <small class="text-white d-block mb-1">Thất bại</small>
                                    <small class="text-white opacity-75">
                                        Tổng: <span
                                            class="fw-bold">{{ number_format($stat['failed_amount'] ?? 0, 0, ',', '.') }}₫</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-3">
                    <div class="card gradient-card bg-gradient-info card-3d">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-3 avatar-3d">
                                    <div class="avatar-content">
                                        <i class="bx bx-receipt font-medium-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="text-white mb-1 counter-number">{{ $histories->total() }}</h4>
                                    <small class="text-white d-block mb-1">Tổng giao dịch</small>
                                    <small class="text-white opacity-75">
                                        Tổng: <span
                                            class="fw-bold">{{ number_format($stat['total_amount'] ?? 0, 0, ',', '.') }}₫</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bộ lọc tìm kiếm --}}
            <div class="card border-0 shadow-3d mb-4 card-3d">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bx bx-filter-alt mr-2 text-primary"></i>
                        Bộ lọc tìm kiếm
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment_histories.index') }}" method="GET"
                        class="row g-3 align-items-end">
                        {{-- Tên bệnh nhân --}}
                        <div class="col-12 col-md-3">
                            <label for="patient_name" class="form-label fw-semibold">Tên bệnh nhân</label>
                            <input type="text" name="patient_name" id="patient_name" class="form-control input-3d"
                                value="{{ request('patient_name') }}" placeholder="Nhập tên bệnh nhân">
                        </div>

                        {{-- Từ ngày --}}
                        <div class="col-6 col-md-2">
                            <label for="date_from" class="form-label fw-semibold">Từ ngày</label>
                            <input type="date" name="date_from" id="date_from" class="form-control input-3d"
                                max="{{ now()->toDateString() }}" value="{{ request('date_from') }}">
                        </div>

                        {{-- Đến ngày --}}
                        <div class="col-6 col-md-2">
                            <label for="date_to" class="form-label fw-semibold">Đến ngày</label>
                            <input type="date" name="date_to" id="date_to" class="form-control input-3d"
                                max="{{ now()->toDateString() }}" value="{{ request('date_to') }}">
                        </div>

                        {{-- Dịch vụ --}}
                        <div class="col-6 col-md-2">
                            <label for="service_id" class="form-label fw-semibold">Dịch vụ</label>
                            <select name="service_id" id="service_id" class="form-select input-3d">
                                <option value="">Tất cả</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Bác sĩ --}}
                        <div class="col-6 col-md-2">
                            <label for="doctor_id" class="form-label fw-semibold">Bác sĩ</label>
                            <select name="doctor_id" id="doctor_id" class="form-select input-3d">
                                <option value="">Tất cả</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Phương thức thanh toán --}}
                        <div class="col-6 col-md-2">
                            <label for="payment_method" class="form-label fw-semibold">Phương thức</label>
                            <select name="payment_method" id="payment_method" class="form-select input-3d">
                                <option value="">Tất cả</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method }}" {{ request('payment_method') == $method ? 'selected' : '' }}>
                                        {{ ucfirst($method) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="col-6 col-md-2">
                            <label for="payment_status" class="form-label fw-semibold">Trạng thái</label>
                            <select name="payment_status" id="payment_status" class="form-select input-3d">
                                <option value="">Tất cả</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán
                                </option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chờ xử
                                    lý</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thất bại
                                </option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Hoàn
                                    tiền</option>
                            </select>
                        </div>

                        {{-- Nút lọc --}}
                        <div class="col-6 col-md-1">
                            <button type="submit" class="btn btn-primary w-100 btn-3d" title="Lọc">
                                <i class="bx bx-filter-alt"></i>
                            </button>
                        </div>

                        {{-- Nút reset --}}
                        <div class="col-6 col-md-1">
                            <a href="{{ route('admin.payment_histories.index') }}"
                                class="btn btn-outline-secondary w-100 btn-3d" title="Xóa lọc">
                                <i class="bx bx-x"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bảng dữ liệu --}}
            <div class="card shadow-3d border-0 card-3d">
                <div class="card-header bg-gradient-primary text-white border-0 header-3d">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-list mr-2"></i>
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách giao dịch</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light badge-3d">{{ $histories->total() }} giao dịch</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-center mb-0 table-modern table-3d">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th class="th-3d">#</th>
                                    <th class="th-3d">Mã</th>
                                    <th class="text-start th-3d">Bệnh nhân</th>
                                    <th class="th-3d">Dịch vụ</th>
                                    <th class="d-none d-md-table-cell th-3d">Bác sĩ</th>
                                    <th class="th-3d">Ngày</th>
                                    <th class="th-3d">Trạng thái</th>
                                    <th class="th-3d">Hoàn tiền</th>
                                    <th class="th-3d">Số tiền</th>
                                    <th class="th-3d">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                    @php
                                        $status = $history->payment->status ?? null;
                                        $refundStatus = $history->refund_status ?? 'none'; // Lấy từ chính $history

                                        $statusMap = [
                                            'paid' => ['label' => 'Đã thanh toán', 'class' => 'bg-success'],
                                            'pending' => ['label' => 'Chờ xử lý', 'class' => 'bg-warning text-dark'],
                                            'failed' => ['label' => 'Thất bại', 'class' => 'bg-danger'],
                                            'refunded' => ['label' => 'Đã hoàn tiền', 'class' => 'bg-secondary'],
                                        ];

                                        $refundMap = [
                                            'none' => ['label' => 'Chưa hoàn', 'class' => 'bg-light text-dark'],
                                            'pending' => ['label' => 'Chờ hoàn', 'class' => 'bg-warning text-dark'],
                                            'completed' => ['label' => 'Đã hoàn', 'class' => 'bg-info text-white'],
                                            'failed' => ['label' => 'Hoàn thất bại', 'class' => 'bg-danger'],
                                        ];

                                        $statusLabel = $statusMap[$status] ?? ['label' => 'Không xác định', 'class' => 'bg-dark'];
                                        $refundLabel = $refundMap[$refundStatus] ?? ['label' => 'Không xác định', 'class' => 'bg-dark'];
                                    @endphp
                                    <tr class="row-3d">
                                        <td>{{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}
                                        </td>
                                        <td>
                                            <span class="badge bg-label-primary badge-3d">
                                                #{{ $history->payment->id ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="text-start">
                                            <strong>{{ optional($history->payment->appointment->patient)->full_name ?? 'N/A' }}</strong>
                                            <small class="text-muted d-block">
                                                {{ optional($history->payment->appointment->patient)->phone ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td>{{ optional($history->payment->appointment->service)->name ?? 'N/A' }}</td>
                                        <td class="d-none d-md-table-cell">
                                            {{ optional($history->payment->appointment->doctor->user)->full_name ?? 'N/A' }}
                                        </td>
                                        <td>{{ optional($history->payment_date)->format('d/m/Y H:i') ?? 'Chưa TT' }}</td>
                                        <td>
                                            <span class="badge {{ $statusLabel['class'] }} badge-3d">
                                                {{ $statusLabel['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $refundLabel['class'] }} badge-3d">
                                                {{ $refundLabel['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success amount-3d">
                                                {{ number_format($history->amount, 0, ',', '.') }}₫
                                            </strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.payment_histories.show', $history->id) }}"
                                                class="btn btn-sm btn-outline-info btn-3d" title="Chi tiết">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-muted py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-search-alt-2 display-4 text-muted"></i>
                                                <p class="mt-2">Không có dữ liệu phù hợp.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                    {{-- Phân trang --}}
                    @if($histories->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top pagination-3d">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $histories->firstItem() }} - {{ $histories->lastItem() }} trong tổng số
                                        {{ $histories->total() }} kết quả
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


            <style>
                /* 3D Effects Base */
                .shadow-3d {
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
                }

                .card-3d {
                    border-radius: 15px;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    transform: translateZ(0);
                    backface-visibility: hidden;
                }

                .card-3d:hover {
                    transform: translateY(-5px) scale(1.02);
                    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15), 0 5px 15px rgba(0, 0, 0, 0.1);
                }

                /* Icon Circle 3D */
                .icon-circle {
                    width: 55px;
                    height: 55px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                    transition: all 0.3s ease;
                }

                .icon-circle:hover {
                    transform: rotateY(15deg) scale(1.1);
                }

                /* Avatar 3D */
                .avatar-3d {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                    transition: all 0.3s ease;
                }

                .avatar-3d:hover {
                    transform: rotateY(20deg) scale(1.1);
                }

                /* Gradient Cards 3D */
                .gradient-card {
                    border: none;
                    border-radius: 15px;
                    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    position: relative;
                    overflow: hidden;
                }

                .gradient-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
                    transition: opacity 0.3s ease;
                    opacity: 0;
                }

                .gradient-card:hover::before {
                    opacity: 1;
                }

                .gradient-card:hover {
                    transform: translateY(-8px) rotateX(5deg);
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
                }

                /* Gradients giữ nguyên màu gốc */
                .bg-gradient-primary {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }

                .bg-gradient-success {
                    background: linear-gradient(135deg, #00cec9 0%, #55a3ff 100%);
                }

                .bg-gradient-info {
                    background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
                }

                .bg-gradient-warning {
                    background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
                }

                .bg-gradient-danger {
                    background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
                }

                /* Counter Animation */
                .counter-number {
                    font-size: 2.2rem;
                    font-weight: 700;
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                }

                /* Input 3D */
                .input-3d {
                    border-radius: 10px;
                    border: 2px solid #e9ecef;
                    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
                    transition: all 0.3s ease;
                }

                .input-3d:focus {
                    border-color: #667eea;
                    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25), inset 0 2px 4px rgba(0, 0, 0, 0.05);
                    transform: translateY(-2px);
                }

                /* Button 3D */
                .btn-3d {
                    border-radius: 10px;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                }

                .btn-3d:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                }

                .btn-3d:active {
                    transform: translateY(0);
                }

                /* Badge 3D */
                .badge-3d {
                    border-radius: 8px;
                    padding: 0.4em 0.8em;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                    transition: all 0.3s ease;
                }

                .badge-3d:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                }

                /* Table 3D */
                .table-3d {
                    border-radius: 0;
                    overflow: hidden;
                }

                .th-3d {
                    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                    border-bottom: 3px solid #dee2e6;
                    font-weight: 600;
                    text-transform: uppercase;
                    font-size: 0.8rem;
                    letter-spacing: 0.5px;
                }

                .row-3d {
                    transition: all 0.3s ease;
                }

                .row-3d:hover {
                    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                    transform: translateX(5px);
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                }

                /* Header 3D */
                .header-3d {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 15px 15px 0 0;
                    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                }

                /* Alert 3D */
                .alert-3d {
                    border-radius: 12px;
                    border: none;
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
                    transition: all 0.3s ease;
                }

                .alert-3d:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                }

                /* Patient Info 3D */
                .patient-info {
                    transition: all 0.3s ease;
                }

                .patient-info:hover {
                    transform: translateX(3px);
                }

                /* Amount 3D */
                .amount-3d {
                    font-size: 1.1rem;
                    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                }

                /* Pagination 3D */
                .pagination-3d {
                    background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
                    border-radius: 0 0 15px 15px;
                }

                /* Empty State */
                .empty-state {
                    padding: 3rem 0;
                    text-align: center;
                }

                .empty-state i {
                    opacity: 0.3;
                }

                /* Responsive */
                @media (max-width: 768px) {
                    .card-3d:hover {
                        transform: translateY(-3px) scale(1.01);
                    }

                    .gradient-card:hover {
                        transform: translateY(-5px);
                    }

                    .counter-number {
                        font-size: 1.8rem;
                    }

                    .table th,
                    .table td {
                        font-size: 13px !important;
                        padding: 12px 8px;
                    }

                    .d-none.d-md-table-cell {
                        display: none !important;
                    }
                }

                /* Animation Keyframes */
                @keyframes float {
                    0% {
                        transform: translateY(0px);
                    }

                    50% {
                        transform: translateY(-10px);
                    }

                    100% {
                        transform: translateY(0px);
                    }
                }

                .gradient-card {
                    animation: float 6s ease-in-out infinite;
                }

                .gradient-card:nth-child(2) {
                    animation-delay: -1s;
                }

                .gradient-card:nth-child(3) {
                    animation-delay: -2s;
                }

                .gradient-card:nth-child(4) {
                    animation-delay: -3s;
                }

                /* Badge Colors */
                .badge-success {
                    background-color: #39DA8A;
                    color: #fff;
                }

                .badge-info {
                    background-color: #00CFDD;
                    color: #fff;
                }

                .badge-warning {
                    background-color: #FDAC41;
                    color: #212529;
                }

                .badge-danger {
                    background-color: #FF5B5C;
                    color: #fff;
                }

                .badge-secondary {
                    background-color: #6c757d;
                    color: #fff;
                }

                .bg-rgba-white {
                    background-color: rgba(255, 255, 255, 0.2);
                }

                /* Modern Table */
                .table-modern {
                    font-size: 0.9rem;
                }

                .table-modern td {
                    vertical-align: middle;
                    padding: 1rem 0.75rem;
                }
            </style>
@endsection

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const fromInput = document.getElementById('date_from');
                    const toInput = document.getElementById('date_to');

                    // Khi chọn "Từ ngày" thì đặt min cho "Đến ngày"
                    fromInput.addEventListener('change', function () {
                        if (fromInput.value) {
                            toInput.min = fromInput.value;
                        } else {
                            toInput.removeAttribute('min');
                        }
                    });

                    // Khi chọn "Đến ngày" thì đặt max cho "Từ ngày"
                    toInput.addEventListener('change', function () {
                        if (toInput.value) {
                            fromInput.max = toInput.value;
                        } else {
                            fromInput.setAttribute('max', '{{ now()->toDateString() }}');
                        }
                    });

                    // Khởi tạo giá trị ban đầu nếu có
                    if (fromInput.value) {
                        toInput.min = fromInput.value;
                    }
                    if (toInput.value) {
                        fromInput.max = toInput.value;
                    }
                });
            </script>
        @endpush

