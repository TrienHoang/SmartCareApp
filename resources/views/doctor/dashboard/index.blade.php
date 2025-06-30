@extends('doctor.dashboard')

@section('title', 'Thống kê bác sĩ: ' . ($doctor->user->full_name ?? $doctor->name))

@section('content')
<div class="container-fluid py-4 dashboard-scroll">
    <div class="d-flex justify-content-end mb-3 gap-2">
      <a href="{{ route('doctor.dashboard.stats-excel', ['doctor' => $doctor->id]) }}" class="btn btn-success btn-sm">

        <i class="fas fa-file-excel me-1"></i> Xuất Excel
    </a>

    <a href="{{ route('doctor.dashboard.stats-pdf', ['doctor' => $doctor->id]) }}" class="btn btn-danger btn-sm">
        <i class="fas fa-file-pdf me-1"></i> Xuất PDF
    </a>
    </div>

    <!-- Tổng quan thống kê chính -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow bg-gradient-warning text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-75 mb-2">Tổng doanh thu</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}đ</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-coins fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow bg-gradient-primary text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-75 mb-2">Tổng bệnh nhân</h6>
                            <h3 class="mb-0 fw-bold">{{ $totalPatients ?? 0 }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow bg-gradient-success text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-75 mb-2">Lịch hẹn hôm nay</h6>
                            <h3 class="mb-0 fw-bold">{{ $todayAppointments ?? 0 }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-calendar-check fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow bg-gradient-info text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-75 mb-2">Đánh giá TB</h6>
                            <h3 class="mb-0 fw-bold">{{ $doctor->average_rating ?? '-' }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-star fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Thống kê trạng thái lịch hẹn hôm nay -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow h-100">
                <div class="card-body p-4 text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-clock text-warning fa-2x me-2"></i>
                        <div>
                            <h4 class="mb-0 text-warning fw-bold">{{ $appointments_pending ?? 0 }}</h4>
                            <small class="text-muted">Chờ xác nhận</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow h-100">
                <div class="card-body p-4 text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-hourglass-half text-primary fa-2x me-2"></i>
                        <div>
                            <h4 class="mb-0 text-primary fw-bold">{{ $appointments_confirmed ?? 0 }}</h4>
                            <small class="text-muted">Lịch chờ khám</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow h-100">
                <div class="card-body p-4 text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-user-check text-success fa-2x me-2"></i>
                        <div>
                            <h4 class="mb-0 text-success fw-bold">{{ $appointments_completed ?? 0 }}</h4>
                            <small class="text-muted">Đã khám xong</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow h-100">
                <div class="card-body p-4 text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-times-circle text-danger fa-2x me-2"></i>
                        <div>
                            <h4 class="mb-0 text-danger fw-bold">{{ $appointments_cancelled ?? 0 }}</h4>
                            <small class="text-muted">Đã hủy lịch</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ và tăng trưởng -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Biểu đồ đặt lịch & doanh thu
                    </h5>
                    <form method="GET" id="timeFrameForm" class="d-inline-block">
                        <select name="type" id="timeFrameSelect"
                            class="form-select form-select-sm border-1 bg-light"
                            onchange="this.form.submit()">
                            <option value="month" {{ $type == 'month' ? 'selected' : '' }}>Theo tháng</option>
                            <option value="year" {{ $type == 'year' ? 'selected' : '' }}>Theo năm</option>
                            <option value="custom" {{ $type == 'custom' ? 'selected' : '' }}>Tùy chọn</option>
                        </select>
                        @if($type == 'month')
                            <input type="number" name="year" class="form-control form-control-sm d-inline-block ms-2" value="{{ request('year', now()->year) }}" min="2000" max="{{ now()->year }}">
                        @endif
                        @if($type == 'custom')
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                        @endif
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="bookingRevenueChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-trending-up text-success me-2"></i>
                        Tăng trưởng
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <div class="mb-3">
                        @php
                            // Tính toán tăng trưởng lịch hẹn
                            $growthValue = 0;
                            $growthLabel = '';
                            if (isset($statBookings) && count($statBookings) > 1) {
                                $last = end($statBookings);
                                $prev = prev($statBookings);
                                if ($prev > 0) {
                                    $growthValue = round((($last - $prev) / $prev) * 100, 1);
                                }
                                $growthLabel = $growthValue > 0 ? 'Tăng' : ($growthValue < 0 ? 'Giảm' : 'Không đổi');
                            }
                            // Tính toán tăng trưởng doanh thu
                            $revenueGrowth = 0;
                            $revenueLabel = '';
                            if (isset($statRevenue) && count($statRevenue) > 1) {
                                $lastR = end($statRevenue);
                                $prevR = prev($statRevenue);
                                if ($prevR > 0) {
                                    $revenueGrowth = round((($lastR - $prevR) / $prevR) * 100, 1);
                                }
                                $revenueLabel = $revenueGrowth > 0 ? 'Tăng' : ($revenueGrowth < 0 ? 'Giảm' : 'Không đổi');
                            }
                        @endphp
                        <div class="p-3 rounded-3 bg-light mb-2">
                            <div class="mb-2">
                                <span class="fw-semibold text-muted">Tăng trưởng số lịch hẹn</span>
                            </div>
                            <span class="fs-2 fw-bold {{ $growthValue > 0 ? 'text-success' : ($growthValue < 0 ? 'text-danger' : 'text-secondary') }}">
                                {{ $growthValue > 0 ? '+' : '' }}{{ $growthValue }}%
                            </span>
                            <div class="small text-muted">{{ $growthLabel }}</div>
                        </div>
                        <div class="p-3 rounded-3 bg-light">
                            <div class="mb-2">
                                <span class="fw-semibold text-muted">Tăng trưởng doanh thu</span>
                            </div>
                            <span class="fs-2 fw-bold {{ $revenueGrowth > 0 ? 'text-success' : ($revenueGrowth < 0 ? 'text-danger' : 'text-secondary') }}">
                                {{ $revenueGrowth > 0 ? '+' : '' }}{{ $revenueGrowth }}%
                            </span>
                            <div class="small text-muted">{{ $revenueLabel }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng thống kê chi tiết & Hiệu suất hoạt động song song -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-table text-info me-2"></i>
                        Bảng thống kê chi tiết
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Lịch hẹn</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Nếu có biến $statTable (từ controller), ưu tiên dùng, nếu không thì dùng $statLabels/$statBookings/$statRevenue
                                    $hasStatTable = isset($statTable) && count($statTable);
                                @endphp
                                @if($hasStatTable)
                                    @foreach($statTable as $row)
                                        <tr>
                                            <td>{{ $row['label'] }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $row['bookings'] ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success fw-bold">
                                                    {{ number_format($row['revenue'] ?? 0, 0, ',', '.') }}đ
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @forelse($statLabels as $i => $label)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $statBookings[$i] ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success fw-bold">
                                                    {{ number_format($statRevenue[$i] ?? 0, 0, ',', '.') }}đ
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Không có dữ liệu
                                            </td>
                                        </tr>
                                    @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hiệu suất hoạt động -->
        <div class="col-lg-5">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-primary border-0 py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="fas fa-chart-bar me-2"></i>
                        Hiệu suất hoạt động
                    </h5>
                </div>
                <div class="card-body" style="background: #f8fafc;">
                    <div class="row g-4">
                        <!-- Tỷ lệ khám thành công -->
                        <div class="col-12">
                            <div class="p-4 rounded-3 d-flex align-items-center" style="background: #e3fcec;">
                                <i class="fas fa-check-circle fa-2x me-3" style="color: #16a34a;"></i>
                                <div>
                                    <h6 class="mb-1" style="color: #16a34a;">Tỷ lệ khám thành công</h6>
                                    @php
                                        $totalAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)->count();
                                        $successAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)->where('status', 'completed')->count();
                                        $successRate = $totalAppointments > 0 ? round($successAppointments / $totalAppointments * 100, 1) : 0;
                                    @endphp
                                    <h3 class="fw-bold mb-0" style="color: #16a34a;">
                                        {{ $successRate }}%
                                    </h3>
                                    <small class="text-muted">
                                        {{ $successAppointments }} / {{ $totalAppointments }} lịch hẹn
                                    </small>
                                </div>
                            </div>
                        </div>
                        <!-- Tỷ lệ hủy lịch khám -->
                        <div class="col-12">
                            <div class="p-4 rounded-3 d-flex align-items-center" style="background: #fee2e2;">
                                <i class="fas fa-times-circle fa-2x me-3" style="color: #dc2626;"></i>
                                <div>
                                    <h6 class="mb-1" style="color: #dc2626;">Tỷ lệ hủy lịch khám</h6>
                                    @php
                                        $cancelAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)->where('status', 'cancelled')->count();
                                        $cancelRate = $totalAppointments > 0 ? round($cancelAppointments / $totalAppointments * 100, 1) : 0;
                                    @endphp
                                    <h3 class="fw-bold mb-0" style="color: #dc2626;">
                                        {{ $cancelRate }}%
                                    </h3>
                                    <small class="text-muted">
                                        {{ $cancelAppointments }} / {{ $totalAppointments }} lịch hẹn
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(45deg, #11998e 0%, #38ef7d 100%);
        }
        .bg-gradient-warning {
            background: linear-gradient(45deg, #ff8a00 0%, #e52e71 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(45deg, #43cea2 0%, #185a9d 100%);
        }
        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .rounded-3 {
            border-radius: 12px !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const bookingRevenueCtx = document.getElementById('bookingRevenueChart').getContext('2d');
            const bookingRevenueChart = new Chart(bookingRevenueCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($statLabels) !!},
                    datasets: [
                        {
                            type: 'bar',
                            label: 'Lịch hẹn',
                            data: {!! json_encode($statBookings) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: '#36A2EB',
                            borderWidth: 1
                        },
                        {
                            type: 'line',
                            label: 'Doanh thu (đ)',
                            data: {!! json_encode($statRevenue) !!},
                            borderColor: '#FF6384',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            tension: 0.3,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Lịch hẹn'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Doanh thu (đ)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' đ';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>
</div>
@endsection
