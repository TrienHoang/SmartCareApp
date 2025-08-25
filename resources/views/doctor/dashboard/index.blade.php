@extends('doctor.dashboard')

@section('title', 'Thống kê bác sĩ: ' . ($doctor->user->full_name ?? $doctor->name))

@section('content')
    <div class="container-fluid py-4 dashboard-scroll">
        <!-- Thông báo lỗi chung -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
            <div class="col-lg-4 col-md-6">
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
            <div class="col-lg-4 col-md-6">
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
            <div class="col-lg-4 col-md-6">
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
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-clock text-warning fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-0 text-warning fw-bold">{{ $appointments_pending ?? 0 }}</h4>
                                <small class="text-muted">Chờ xác nhận</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-hourglass-half text-primary fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-0 text-primary fw-bold">{{ $appointments_confirmed ?? 0 }}</h4>
                                <small class="text-muted">Lịch chờ khám</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-user-check text-success fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-0 text-success fw-bold">{{ $appointments_completed ?? 0 }}</h4>
                                <small class="text-muted">Đã khám xong</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-times-circle text-danger fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-0 text-danger fw-bold">{{ $appointments_cancelled ?? 0 }}</h4>
                                <small class="text-muted">Đã hủy lịch</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ lịch hẹn và tăng trưởng -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Biểu đồ lịch hẹn theo thời gian
                        </h5>
                        <form method="GET" class="d-flex align-items-end gap-3" style="flex-wrap: nowrap;"
                            id="filterForm">
                            <div class="d-flex flex-column">
                                <label class="form-label mb-1" style="font-size: 12px; font-weight: 600;">Lọc theo</label>
                                <select name="type" id="filterType" class="form-select form-select-sm"
                                    style="width: 100px; font-size: 13px;">
                                    <option value="month" {{ $type == 'month' ? 'selected' : '' }}>Theo tháng</option>
                                    <option value="year" {{ $type == 'year' ? 'selected' : '' }}>Theo năm</option>
                                    <option value="custom" {{ $type == 'custom' ? 'selected' : '' }}>Tùy chỉnh</option>
                                </select>
                                @error('type')
                                    <small class="text-danger invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-flex flex-column">
                                <label class="form-label mb-1" style="font-size: 12px; font-weight: 600;">Năm</label>
                                <input type="number" name="year" id="yearInput" class="form-control form-control-sm"
                                    value="{{ request('year', now()->year) }}" min="2000" max="{{ now()->year }}"
                                    style="width: 70px; font-size: 13px;" {{ $type == 'custom' ? 'disabled' : '' }}>
                                @error('year')
                                    <small class="text-danger invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-flex flex-column">
                                <label class="form-label mb-1" style="font-size: 12px; font-weight: 600;">Từ ngày</label>
                                <input type="date" name="start_date" id="startDate"
                                    class="form-control form-control-sm" value="{{ request('start_date') }}"
                                    style="width: 130px; font-size: 13px;" {{ $type != 'custom' ? 'disabled' : '' }}>
                                @error('start_date')
                                    <small class="text-danger invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-flex flex-column">
                                <label class="form-label mb-1" style="font-size: 12px; font-weight: 600;">Đến ngày</label>
                                <input type="date" name="end_date" id="endDate"
                                    class="form-control form-control-sm" value="{{ request('end_date') }}"
                                    style="width: 130px; font-size: 13px;" {{ $type != 'custom' ? 'disabled' : '' }}>
                                @error('end_date')
                                    <small class="text-danger invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary btn-sm"
                                    style="font-size: 13px; height: 31px; padding: 0 16px;">
                                    <i class="fas fa-filter me-1"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <canvas id="appointmentChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-trending-up text-success me-2"></i>
                            Tăng trưởng lịch hẹn
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <div class="mb-3">
                            <div class="p-4 rounded-3 bg-light mb-3">
                                <div class="mb-3">
                                    <span class="fw-semibold text-muted">Tăng trưởng số lịch hẹn</span>
                                </div>
                                <span
                                    class="display-4 fw-bold {{ $growthValue > 0 ? 'text-success' : ($growthValue < 0 ? 'text-danger' : 'text-secondary') }}">
                                    {{ $growthValue > 0 ? '+' : '' }}{{ $growthValue }}%
                                </span>
                                <div class="mt-2 text-muted">{{ $growthLabel }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng thống kê chi tiết & Hiệu suất hoạt động -->
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
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($statLabels as $i => $label)
                                        <tr>
                                            <td class="fw-medium">{{ $label }}</td>
                                            <td>
                                                <span class="badge bg-primary fs-6">{{ $statBookings[$i] ?? 0 }}</span>
                                            </td>
                                            <td>
                                                @if (($statBookings[$i] ?? 0) > 0)
                                                    <span class="badge bg-success">Có hoạt động</span>
                                                @else
                                                    <span class="badge bg-secondary">Không hoạt động</span>
                                                @endif
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
                                        <h3 class="fw-bold mb-0" style="color: #dc2626;">
                                            {{ $cancelRate }}%
                                        </h3>
                                        <small class="text-muted">
                                            {{ $cancelAppointments }} / {{ $totalAppointments }} lịch hẹn
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <!-- Tổng số lượt khám -->
                            <div class="col-12">
                                <div class="p-4 rounded-3 d-flex align-items-center" style="background: #e0f2fe;">
                                    <i class="fas fa-user-md fa-2x me-3" style="color: #0277bd;"></i>
                                    <div>
                                        <h6 class="mb-1" style="color: #0277bd;">Tổng lượt khám</h6>
                                        <h3 class="fw-bold mb-0" style="color: #0277bd;">
                                            {{ $totalAppointments }}
                                        </h3>
                                        <small class="text-muted">
                                            Tổng số lịch hẹn đã tạo
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
                border-radius: 4px;
            }

            .card:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            .rounded-3 {
                border-radius: 4px !important;
            }

            .is-invalid {
                border-color: #dc3545 !important;
            }

            .invalid-feedback {
                display: none;
                font-size: 12px;
                color: #dc3545;
            }

            .is-invalid~.invalid-feedback {
                display: block;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const filterForm = document.getElementById('filterForm');
                const filterType = document.getElementById('filterType');
                const yearInput = document.getElementById('yearInput');
                const startDate = document.getElementById('startDate');
                const endDate = document.getElementById('endDate');

                let customDateState = {
                    startDate: startDate.value,
                    endDate: endDate.value
                };

                function updateFilterType() {
                    if (filterType.value === 'custom') {
                        yearInput.disabled = true;
                        startDate.disabled = false;
                        endDate.disabled = false;
                        startDate.value = customDateState.startDate || '';
                        endDate.value = customDateState.endDate || '';
                    } else {
                        yearInput.disabled = false;
                        startDate.disabled = true;
                        endDate.disabled = true;
                        customDateState.startDate = startDate.value;
                        customDateState.endDate = endDate.value;
                    }
                }

                filterType.addEventListener('change', updateFilterType);
                startDate.addEventListener('change', function() {
                    customDateState.startDate = startDate.value;
                });
                endDate.addEventListener('change', function() {
                    customDateState.endDate = endDate.value;
                });
                updateFilterType();

                filterForm.addEventListener('submit', function(event) {
                    let errors = [];
                    let valid = true;

                    [filterType, yearInput, startDate, endDate].forEach(input => {
                        input.classList.remove('is-invalid');
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = '';
                        }
                    });

                    if (!['month', 'year', 'custom'].includes(filterType.value)) {
                        errors.push({
                            field: filterType,
                            message: 'Vui lòng chọn loại thống kê hợp lệ.'
                        });
                        valid = false;
                    }

                    if (filterType.value === 'month' || filterType.value === 'year') {
                        const year = parseInt(yearInput.value);
                        if (!yearInput.value || isNaN(year)) {
                            errors.push({
                                field: yearInput,
                                message: 'Vui lòng nhập năm hợp lệ.'
                            });
                            valid = false;
                        } else if (year < 2000 || year > {{ now()->year }}) {
                            errors.push({
                                field: yearInput,
                                message: 'Năm phải từ 2000 đến {{ now()->year }}.'
                            });
                            valid = false;
                        }
                    }

                    if (filterType.value === 'custom') {
                        if (!startDate.value) {
                            errors.push({
                                field: startDate,
                                message: 'Vui lòng chọn ngày bắt đầu.'
                            });
                            valid = false;
                        }
                        if (!endDate.value) {
                            errors.push({
                                field: endDate,
                                message: 'Vui lòng chọn ngày kết thúc.'
                            });
                            valid = false;
                        }
                        if (startDate.value && endDate.value) {
                            const start = new Date(startDate.value);
                            const end = new Date(endDate.value);
                            if (start > end) {
                                errors.push({
                                    field: startDate,
                                    message: 'Ngày bắt đầu không được lớn hơn ngày kết thúc.'
                                });
                                valid = false;
                            }
                            const diffDays = (end - start) / (1000 * 60 * 60 * 24);
                            if (diffDays > 62) {
                                errors.push({
                                    field: endDate,
                                    message: 'Khoảng thời gian tối đa là 62 ngày.'
                                });
                                valid = false;
                            }
                        }
                    }

                    if (!valid) {
                        event.preventDefault();
                        errors.forEach(error => {
                            error.field.classList.add('is-invalid');
                            const feedback = error.field.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = error.message;
                            }
                        });
                    } else {
                        if (filterType.value !== 'custom') {
                            customDateState.startDate = '';
                            customDateState.endDate = '';
                            startDate.value = '';
                            endDate.value = '';
                        }
                    }
                });

                // Biểu đồ lịch hẹn
                const appointmentCtx = document.getElementById('appointmentChart').getContext('2d');
                const appointmentChart = new Chart(appointmentCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($statLabels) !!},
                        datasets: [{
                            label: 'Lịch hẹn',
                            data: {!! json_encode($statBookings) !!},
                            borderColor: '#36A2EB',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#36A2EB',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Số lượng lịch hẹn'
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Thời gian'
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        elements: {
                            point: {
                                hoverBackgroundColor: '#36A2EB'
                            }
                        }
                    }
                });
            });
        </script>
    </div>
@endsection
