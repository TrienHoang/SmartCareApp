@extends('admin.dashboard')
@section('content')
    <div class="container-fluid py-4">
        <!-- Tổng quan -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-coins fa-2x text-warning mb-2"></i>
                        <h6 class="text-muted">Tổng doanh thu</h6>
                        <h4 class="text-warning">{{ number_format($globalStat->total_revenue ?? 0, 0, ',', '.') }} đ</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-user-md fa-2x text-primary mb-2"></i>
                        <h6 class="text-muted">Tổng bác sĩ</h6>
                        <h4 class="text-primary">{{ $globalStat->total_doctors }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-success mb-2"></i>
                        <h6 class="text-muted">Tổng bệnh nhân</h6>
                        <h4 class="text-success">{{ $globalStat->total_patients }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="text-muted">Tổng lịch hẹn</h6>
                        <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                        <h4 class="text-dark">{{ $globalStat->total_appointments }}</h4>

                        <!-- Trạng thái: Chờ - Khám - Huỷ -->
                        <div class="row pt-2 small">
                            <div class="col-4 text-primary">
                                <i class="fas fa-hourglass-half me-1"></i> Chờ<br>
                                <strong>{{ $globalStat->appointments_confirmed }}</strong>
                            </div>
                            <div class="col-4 text-success">
                                <i class="fas fa-user-check me-1"></i> Khám<br>
                                <strong>{{ $globalStat->appointments_completed }}</strong>
                            </div>
                            <div class="col-4 text-danger">
                                <i class="fas fa-times-circle me-1"></i> Huỷ<br>
                                <strong>{{ $globalStat->appointments_cancelled }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Lịch hẹn hôm nay -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="text-muted text-uppercase mb-3">Lịch hẹn hôm nay</h5>
                        <div class="row pt-2">
                            <div class="col-md-3 text-dark">
                                <i class="fas fa-calendar-check fa-sm me-1"></i> Tổng<br>
                                <strong>{{ $dailyStat->total_appointments }}</strong>
                            </div>
                            <div class="col-md-3 text-primary">
                                <i class="fas fa-user-clock fa-sm me-1"></i> Chưa Khám<br>
                                <strong>{{ $dailyStat->appointments_confirmed }}</strong>
                            </div>
                            <div class="col-md-3 text-success">
                                <i class="fas fa-user-check fa-sm me-1"></i> Đã Khám<br>
                                <strong>{{ $dailyStat->appointments_completed }}</strong>
                            </div>
                            <div class="col-md-3 text-danger">
                                <i class="fas fa-times-circle fa-sm me-1"></i> Đã Huỷ<br>
                                <strong>{{ $dailyStat->appointments_cancelled }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Biểu đồ & tăng trưởng -->
        <div class="row mb-4">
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Biểu đồ đặt lịch & doanh thu dự kiến</h5>
                        <form method="GET" id="timeFrameForm">
                            <select name="type" id="timeFrameSelect" class="form-select form-select-sm w-auto"
                                onchange="document.getElementById('timeFrameForm').submit()">
                                <option value="day" {{ request('type') == 'day' ? 'selected' : '' }}>Ngày</option>
                                <option value="week" {{ request('type') == 'week' ? 'selected' : '' }}>Tuần</option>
                                <option value="month"
                                    {{ request('type') == 'month' || !request('type') ? 'selected' : '' }}>Tháng</option>
                                <option value="year" {{ request('type') == 'year' ? 'selected' : '' }}>Năm</option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body">
                        <canvas id="bookingRevenueChart" height="180"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <h6 class="text-muted">So với kỳ trước</h6>
                        <div>
                            @if ($bookingGrowthValue > 0)
                                <i class="fas fa-arrow-up text-success"></i>
                                <span class="text-success fw-bold">Tăng {{ $bookingGrowthValue }}%</span>
                            @elseif($bookingGrowthValue < 0)
                                <i class="fas fa-arrow-down text-danger"></i>
                                <span class="text-danger fw-bold">Giảm {{ abs($bookingGrowthValue) }}%</span>
                            @else
                                <span class="text-muted">Không đổi</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $bookingGrowthLabel ?? 'so với tháng trước' }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dịch vụ & bác sĩ -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Thống kê dịch vụ</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="serviceChart" height="250"></canvas>
                        <p class="mt-3 text-center">
                            <strong>Top dịch vụ:</strong>
                            {{ $topService['name'] ?? 'Không có dữ liệu' }} ({{ $topService['bookings'] ?? 0 }} lượt)
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card  h-100 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Thống kê theo bác sĩ</h5>
                    </div>
                    <div class="card-body" style="max-height: 450px; overflow-y: auto;">
                        @foreach (collect($doctorStats ?? [])->sortByDesc('total_appointments') as $doctor)
                            <div class="d-flex align-items-center mb-4 p-2 border rounded">
                                <img src="{{ $doctor['avatar_url'] ?? 'https://i.pravatar.cc/50' }}" alt="Avatar"
                                    class="rounded-circle me-3" width="50" height="50">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $doctor['name'] }}</div>
                                    <div class="text-muted small">{{ $doctor['specialization'] ?? 'Không rõ chuyên môn' }}
                                    </div>
                                </div>
                                <div class="text-end ms-3" style="min-width: 65px;">
                                    <div class="fw-bold text-dark">{{ $doctor['total_appointments'] }}</div>
                                    <div class="text-warning small">★ {{ number_format($doctor['average_rating'], 1) }}
                                    </div>
                                    <div class="text-muted small">Đã khám: {{ $doctor['completed_appointments'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Hiệu suất & bệnh nhân -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-primary mb-4">
                            <i class="bi bi-activity me-2"></i>Tỷ lệ & Hiệu suất
                        </h5>

                        <div class="row g-4">

                            <!-- Tỷ lệ hủy lịch -->
                            <div class="col-12">
                                <div class="border-start border-4 border-danger ps-3">
                                    <p class="text-muted mb-1">Tỷ lệ hủy lịch</p>
                                    <h4 class="fw-semibold text-danger">{{ $performanceStats['cancel_rate'] ?? '--' }}%
                                    </h4>
                                </div>
                            </div>

                            <!-- Tỷ lệ khám đúng hẹn -->
                            <div class="col-12">
                                <div class="border-start border-4 border-success ps-3">
                                    <p class="text-muted mb-1">Tỷ lệ khám đúng hẹn</p>
                                    <h4 class="fw-semibold text-success">{{ $performanceStats['on_time_rate'] ?? '--' }}%
                                    </h4>
                                </div>
                            </div>

                            <!-- Thời gian chờ trung bình -->
                            <div class="col-12">
                                <div class="border-start border-4 border-primary ps-3">
                                    <p class="text-muted mb-1">Thời gian chờ trung bình</p>
                                    <h4 class="fw-semibold text-primary">
                                        {{ $performanceStats['avg_waiting_time'] ?? '--' }} phút
                                    </h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header  text-white">
                        <h5 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i>Thống kê bệnh nhân</h5>
                    </div>
                    <form method="GET" class="mb-3 text-end">
                        <select name="patient_type" class="form-select form-select-sm w-auto d-inline-block"
                            onchange="this.form.submit()">
                            <option value="week" {{ request('patient_type') == 'week' ? 'selected' : '' }}>Tuần này
                            </option>
                            <option value="month" {{ request('patient_type') == 'month' ? 'selected' : '' }}>Tháng này
                            </option>
                        </select>
                    </form>

                    <div class="card-body">
                        <div class="mb-4">
                            <!-- Bệnh nhân mới -->
                            <div class="border-start border-4 border-success ps-3 mb-3">
                                <p class="text-muted mb-1">Bệnh nhân mới ({{ ucfirst(request('patient_type', 'tuần')) }})
                                </p>
                                <h4 class="fw-bold text-success">{{ $patientStats['new_this_week'] ?? '--' }}</h4>
                            </div>

                            <!-- Tỷ lệ quay lại -->
                            <div class="border-start border-4 border-info ps-3">
                                <p class="text-muted mb-1">Tỷ lệ quay lại khám</p>
                                <h4 class="fw-bold text-info">{{ $patientStats['return_rate'] ?? '--' }}%</h4>
                            </div>
                        </div>

                        <!-- Khu vực -->
                        <h6 class="fw-semibold text-muted mb-3"><i class="bi bi-geo-alt me-1"></i>Thống kê theo khu vực
                        </h6>

                        <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                            <table class="table table-striped table-sm align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Khu vực</th>
                                        <th class="text-end">Số bệnh nhân</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($patientStats['area'] ?? [] as $region => $count)
                                        <tr>
                                            <td>{{ ucfirst($region) }}</td>
                                            <td class="text-end"><strong>{{ $count }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-muted text-center">Không có dữ liệu khu vực.
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

    </div>
@endsection








<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', () => {

        // 🔵 Biểu đồ tổng trạng thái lịch hẹn
        const statusCtx = document.getElementById('appointmentStatusChart');
        if (statusCtx) {
            new Chart(statusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Chờ khám', 'Đã khám', 'Đã huỷ'],
                    datasets: [{
                        data: [
                            {{ $dailyStat->appointments_pending ?? 0 }},
                            {{ $dailyStat->appointments_completed ?? 0 }},
                            {{ $dailyStat->appointments_cancelled ?? 0 }}
                        ],
                        backgroundColor: ['#007bff', '#28a745', '#dc3545'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // 🟢 Biểu đồ lịch đặt theo ngày (line chart)
        const dailyCtx = document.getElementById('dailyBookingLineChart');
        if (dailyCtx) {
            const dailyLabels = {!! json_encode($dailyLabels ?? []) !!};
            const dailyData = {!! json_encode($dailyData ?? []) !!};

            new Chart(dailyCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Đặt lịch theo ngày',
                        data: dailyData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Đặt lịch theo ngày'
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số lượt đặt'
                            }
                        }
                    }
                }
            });
        }

        // 🔴 Biểu đồ đặt lịch & doanh thu (bar + line)
        const bookingCtx = document.getElementById('bookingRevenueChart');
        if (bookingCtx) {
            const timeLabels = {!! json_encode($timeLabels) !!};
            const timeBookings = {!! json_encode($timeBookings) !!};
            const timeRevenues = {!! json_encode($timeRevenues) !!}.map(val => parseFloat(val));

            new Chart(bookingCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: timeLabels,
                    datasets: [{
                            label: 'Số lượt đặt',
                            data: timeBookings,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Doanh thu dự kiến',
                            data: timeRevenues,
                            type: 'line',
                            yAxisID: 'y1',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.1)',
                            tension: 0.4,
                            fill: true
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
                            }
                        }
                    }
                }
            });
        }

        // 🟡 Biểu đồ thống kê theo dịch vụ
        const serviceCtx = document.getElementById('serviceChart');
        if (serviceCtx) {
            const serviceLabels = {!! json_encode($serviceStats->pluck('name')) !!};
            const serviceData = {!! json_encode($serviceStats->pluck('bookings')) !!};

            new Chart(serviceCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: serviceLabels,
                    datasets: [{
                        label: 'Lượt đặt dịch vụ',
                        data: serviceData,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Lượt đặt theo dịch vụ'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số lượt đặt'
                            }
                        }
                    }
                }
            });
        }
    });
    const ctx = document.getElementById('patientAreaChart').getContext('2d');
    const areaChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($patientStats['area'])) !!},
            datasets: [{
                label: 'Số lượng bệnh nhân',
                data: {!! json_encode(array_values($patientStats['area'])) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.6)',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
