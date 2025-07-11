@extends('admin.dashboard')
@section('content')
    <div class="container-fluid py-4 dashboard-scroll" style="height:991.98px;">
        <div class="d-flex justify-content-end mb-3 gap-2">
            <button class="btn btn-success btn-sm" onclick="exportData('excel')">
                <i class="fas fa-file-excel me-1"></i> Xuất Excel
            </button>
            <button class="btn btn-danger btn-sm" onclick="exportData('pdf')">
                <i class="fas fa-file-pdf me-1"></i> Xuất PDF
            </button>
        </div>

        <!-- Tổng quan thống kê chính -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow bg-gradient-warning text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-75 mb-2">Tổng doanh thu</h6>
                                <h3 class="mb-0 fw-bold">{{ number_format($globalStat->total_revenue ?? 0, 0, ',', '.') }}đ
                                </h3>
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
                                <h6 class="text-white-75 mb-2">Tổng bác sĩ</h6>
                                <h3 class="mb-0 fw-bold">{{ $globalStat->total_doctors }}</h3>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-user-md fa-2x opacity-75"></i>
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
                                <h6 class="text-white-75 mb-2">Tổng bệnh nhân</h6>
                                <h3 class="mb-0 fw-bold">{{ $globalStat->total_patients }}</h3>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-users fa-2x opacity-75"></i>
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
                                <h6 class="text-white-75 mb-2">Tổng lịch hẹn</h6>
                                <h3 class="mb-0 fw-bold">{{ $globalStat->total_appointments }}</h3>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trạng thái lịch hẹn chi tiết -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-clock text-warning fa-2x me-2"></i>
                            <div>
                                <h4 class="mb-0 text-warning fw-bold">{{ $globalStat->appointments_pending }}</h4>
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
                                <h4 class="mb-0 text-primary fw-bold">{{ $globalStat->appointments_confirmed }}</h4>
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
                                <h4 class="mb-0 text-success fw-bold">{{ $globalStat->appointments_completed }}</h4>
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
                                <h4 class="mb-0 text-danger fw-bold">{{ $globalStat->appointments_cancelled }}</h4>
                                <small class="text-muted">Đã hủy lịch</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lịch hẹn hôm nay -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-calendar-day text-primary me-2"></i>
                            Lịch hẹn hôm nay
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-lg-3 col-md-6">
                                <div class="text-center p-3 bg-light rounded-3">
                                    <i class="fas fa-calendar-check text-dark fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-dark mb-1">{{ $dailyStat->total_appointments }}</h4>
                                    <small class="text-muted">Tổng lịch hẹn</small>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="text-center p-3 bg-warning bg-opacity-10 rounded-3">
                                    <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-warning mb-1">{{ $dailyStat->appointments_pending }}</h4>
                                    <small class="text-muted">Chờ xác nhận</small>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="text-center p-3 bg-info bg-opacity-10 rounded-3">
                                    <i class="fas fa-user-clock text-primary fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-primary mb-1">{{ $dailyStat->appointments_confirmed }}</h4>
                                    <small class="text-muted">Chờ khám</small>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="text-center p-3 bg-success bg-opacity-10 rounded-3">
                                    <i class="fas fa-user-check text-success fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-success mb-1">{{ $dailyStat->appointments_completed }}</h4>
                                    <small class="text-muted">Đã khám</small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="text-center p-3 bg-danger bg-opacity-10 rounded-3">
                                    <i class="fas fa-times-circle text-danger fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-danger mb-1">{{ $dailyStat->appointments_cancelled }}</h4>
                                    <small class="text-muted">Đã hủy</small>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="text-center p-3 bg-primary bg-opacity-10 rounded-3">
                                    <i class="fas fa-money-bill-wave text-success fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-success mb-1">
                                        {{ number_format($dailyStat->total_revenue ?? 0, 0, ',', '.') }}đ</h4>
                                    <small class="text-muted">Doanh thu hôm nay</small>
                                </div>
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
                        <div class="d-flex gap-2">
                            <form method="GET" id="timeFrameForm" class="d-inline-block">
                                <select name="type" id="timeFrameSelect"
                                    class="form-select form-select-sm border-1 bg-light"
                                    onchange="toggleCustomDateRange()">
                                    <option value="day" {{ request('type') == 'day' ? 'selected' : '' }}>Theo ngày
                                    </option>
                                    <option value="week" {{ request('type') == 'week' ? 'selected' : '' }}>Theo tuần
                                    </option>
                                    <option value="month"
                                        {{ request('type') == 'month' || !request('type') ? 'selected' : '' }}>Theo tháng
                                    </option>
                                    <option value="year" {{ request('type') == 'year' ? 'selected' : '' }}>Theo năm
                                    </option>
                                    <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Tùy chọn
                                    </option>
                                </select>
                            </form>

                            <!-- Custom Date Range -->
                            <div id="customDateRange" class="d-flex gap-2"
                                style="{{ request('type') == 'custom' ? '' : 'display: none;' }}">
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="form-control form-control-sm" form="timeFrameForm">
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="form-control form-control-sm" form="timeFrameForm">
                                <button type="submit" form="timeFrameForm" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
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
                            @if ($bookingGrowthValue > 0)
                                <div class=" bg-opacity-10 p-4 rounded-3 mb-3">
                                    <i class="fas fa-arrow-up text-success fa-3x mb-2"></i>
                                    <h3 class="text-success fw-bold mb-0">+{{ $bookingGrowthValue }}%</h3>
                                </div>
                                <p class="text-success fw-semibold mb-1">Tăng trưởng tích cực</p>
                            @elseif($bookingGrowthValue < 0)
                                <div class="bg-danger bg-opacity-10 p-4 rounded-3 mb-3">
                                    <i class="fas fa-arrow-down text-danger fa-3x mb-2"></i>
                                    <h3 class="text-danger fw-bold mb-0">{{ $bookingGrowthValue }}%</h3>
                                </div>
                                <p class="text-danger fw-semibold mb-1">Giảm so với kỳ trước</p>
                            @else
                                <div class="bg-secondary bg-opacity-10 p-4 rounded-3 mb-3">
                                    <i class="fas fa-minus text-secondary fa-3x mb-2"></i>
                                    <h3 class="text-secondary fw-bold mb-0">0%</h3>
                                </div>
                                <p class="text-secondary fw-semibold mb-1">Không thay đổi</p>
                            @endif
                            <small class="text-muted">{{ $bookingGrowthLabel ?? 'so với tháng trước' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng thống kê chi tiết -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow">
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
                                        <th>Lượt đặt lịch</th>
                                        <th>Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($statTable as $stat)
                                        <tr>
                                            <td>{{ $stat['label'] }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $stat['bookings'] }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success fw-bold">
                                                    {{ number_format($stat['revenue'], 0, ',', '.') }}đ
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Thống kê dịch vụ và bác sĩ -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-pie text-warning me-2"></i>
                            Thống kê dịch vụ
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="serviceChart" height="200"></canvas>
                        <div class="text-center mt-3 p-3 bg-light rounded-3">
                            <strong class="text-muted">Dịch vụ phổ biến nhất:</strong>
                            <div class="mt-2">
                                <span class="badge bg-primary fs-6">{{ $topService['name'] ?? 'Không có dữ liệu' }}</span>
                                <div class="text-muted small mt-1">{{ $topService['bookings'] ?? 0 }} lượt đặt</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user-md text-info me-2"></i>
                            Thống kê bác sĩ
                        </h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @foreach ($doctorStats as $doctor)
                            <div class="d-flex align-items-center p-3 mb-3 bg-light rounded-3 hover-shadow">
                                <img src="{{ $doctor['avatar'] != 'default.png' ? asset('storage/' . $doctor['avatar']) : 'https://i.pravatar.cc/60' }}"
                                    alt="Avatar" class="rounded-circle me-3 border border-2 border-white shadow"
                                    width="60" height="60">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $doctor['name'] }}</h6>
                                    <p class="text-muted small mb-1">{{ $doctor['specialization'] }}</p>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Đã khám: {{ $doctor['completed_appointments'] }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="badge bg-primary fs-6 mb-2">{{ $doctor['total_appointments'] }}</div>
                                    <div class="text-warning small">
                                        <i class="fas fa-star"></i> {{ $doctor['average_rating'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Hiệu suất và bệnh nhân -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-bar text-success me-2"></i>
                            Hiệu suất hoạt động
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Tỷ lệ hủy lịch -->
                            <div class="col-12">
                                <div class="p-4 bg-danger bg-opacity-10 rounded-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-times-circle text-danger fa-2x me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="text-muted mb-1">Tỷ lệ hủy lịch</h6>
                                            <h3 class="fw-bold text-danger mb-0">
                                                {{ $performanceStats['cancel_rate'] ?? '--' }}%</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tỷ lệ khám đúng hẹn -->
                            <div class="col-12">
                                <div class="p-4 bg-success bg-opacity-10 rounded-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-success fa-2x me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="text-muted mb-1">Tỷ lệ khám đúng hẹn</h6>
                                            <h3 class="fw-bold text-success mb-0">
                                                {{ $performanceStats['on_time_rate'] ?? '--' }}%</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-users text-primary me-2"></i>
                            Thống kê bệnh nhân
                        </h5>
                        <form method="GET">
                            <input type="hidden" name="type" value="{{ request('type') }}">
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                            <select name="patient_type" class="form-select form-select-sm border-0 bg-light"
                                onchange="this.form.submit()">
                                <option value="week" {{ request('patient_type') == 'week' ? 'selected' : '' }}>Tuần này
                                </option>
                                <option value="month" {{ request('patient_type') == 'month' ? 'selected' : '' }}>Tháng
                                    này</option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <!-- Bệnh nhân mới -->
                            <div class="col-6">
                                <div class="text-center p-3 bg-success bg-opacity-10 rounded-3">
                                    <i class="fas fa-user-plus text-success fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-success mb-1">{{ $patientStats['new_this_week'] ?? '--' }}
                                    </h4>
                                    <small class="text-muted">Bệnh nhân mới</small>
                                </div>
                            </div>

                            <!-- Tỷ lệ quay lại -->
                            <div class="col-6">
                                <div class="text-center p-3 bg-info bg-opacity-10 rounded-3">
                                    <i class="fas fa-redo-alt text-info fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-info mb-1">{{ $patientStats['return_rate'] ?? '--' }}%</h4>
                                    <small class="text-muted">Tỷ lệ quay lại</small>
                                </div>
                            </div>
                        </div>

                        <!-- Thống kê theo khu vực -->
                        <div class="bg-light rounded-3 p-3">
                            <h6 class="fw-semibold text-muted mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Phân bố theo khu vực
                            </h6>
                            <div class="area-scroll" style="max-height: 200px; overflow-y: auto;">
                                @forelse ($patientStats['area'] ?? [] as $region => $count)
                                    <div
                                        class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                        <span class="text-muted">{{ ucfirst(trim($region)) }}</span>
                                        <span class="badge bg-primary">{{ $count }}</span>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle me-2"></i>Không có dữ liệu khu vực
                                    </div>
                                @endforelse
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
            // Biểu đồ Đặt lịch & Doanh thu
            const bookingRevenueCtx = document.getElementById('bookingRevenueChart').getContext('2d');
            const bookingRevenueChart = new Chart(bookingRevenueCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($timeLabels) !!},
                    datasets: [{
                            type: 'bar',
                            label: 'Lượt đặt',
                            data: {!! json_encode($timeBookings) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: '#36A2EB',
                            borderWidth: 1
                        },
                        {
                            type: 'line',
                            label: 'Doanh thu (đ)',
                            data: {!! json_encode($timeRevenues) !!},
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
                                text: 'Lượt đặt'
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


            // Biểu đồ Dịch vụ
            const serviceCtx = document.getElementById('serviceChart').getContext('2d');
            const serviceChart = new Chart(serviceCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($serviceStats->pluck('name')) !!},
                    datasets: [{
                        label: 'Lượt đặt',
                        data: {!! json_encode($serviceStats->pluck('bookings')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
    <script>
        function exportData(type) {
            let params = new URLSearchParams(window.location.search);
            if (type === 'excel') {
                window.open('/admin/dashboard/export-excel?' + params.toString(), '_blank');
            } else if (type === 'pdf') {
                window.open('/admin/dashboard/export-pdf?' + params.toString(), '_blank');
            }
        }
    </script>
@endsection
