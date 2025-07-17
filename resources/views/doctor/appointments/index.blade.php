@extends('doctor.dashboard')

@section('title', 'Lịch khám của tôi')

@section('content')
    <div class="container mt-4">
        <!-- Tiêu đề -->
        <div class="mb-4">
            <h2 class="text-primary fw-bold">Lịch khám</h2>
            <p class="text-muted">Danh sách các lịch khám đã được đặt.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Chưa giải quyết -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card gradient-card bg-gradient-secondary">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-white mr-2">
                                <div class="avatar-content">
                                    <i class="fas fa-question-circle font-medium-5"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white mb-0">{{ $appointments_pending ?? 0 }}</h4>
                                <small class="text-white">Chưa giải quyết</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đã xác nhận -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card gradient-card bg-gradient-info">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-white mr-2">
                                <div class="avatar-content">
                                    <i class="fas fa-calendar-check font-medium-5"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white mb-0">{{ $appointments_confirmed ?? 0 }}</h4>
                                <small class="text-white">Đã xác nhận</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đã khám xong -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card gradient-card bg-gradient-success">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-white mr-2">
                                <div class="avatar-content">
                                    <i class="fas fa-user-check font-medium-5"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white mb-0">{{ $appointments_completed ?? 0 }}</h4>
                                <small class="text-white">Đã khám xong</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đã hủy -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card gradient-card bg-gradient-danger">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-rgba-white mr-2">
                                <div class="avatar-content">
                                    <i class="fas fa-times-circle font-medium-5"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white mb-0">{{ $appointments_cancelled ?? 0 }}</h4>
                                <small class="text-white">Đã hủy lịch</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông báo -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <strong>Lỗi!</strong> {{ session('error') }}
            </div>
        @endif

        <!-- Bộ lọc lịch khám -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body bg-light">
                <form action="{{ route('doctor.appointments.index') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-primary font-weight-semibold">
                                <i class="bx bx-user mr-1"></i> Tên bệnh nhân
                            </label>
                            <input type="text" name="patient_name" class="form-control border-left-primary"
                                value="{{ request('patient_name') }}" placeholder="Nhập tên bệnh nhân...">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label text-warning font-weight-semibold">
                                <i class="bx bx-bar-chart-alt mr-1"></i> Trạng thái
                            </label>
                            <select name="status" class="form-control custom-select">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                    Chờ
                                    xác nhận
                                </option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác
                                    nhận</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn
                                    tất</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100 mr-2">
                                <i class="bx bx-filter mr-1"></i> Lọc
                            </button>
                            <a href="{{ route('doctor.appointments.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bx bx-refresh mr-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách lịch khám -->
        @forelse ($appointments as $appointment)
            <div class="card gradient-card shadow-sm mb-3 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="avatar bg-light-primary text-primary me-3">
                            <div class="avatar-content">
                                <i class="fas fa-notes-medical fa-lg"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <h5 class="mb-2 fw-bold">Dịch vụ: {{ $appointment->service->name ?? 'N/A' }}
                            </h5>
                            <p class="mb-1 text-muted"><strong>Bệnh nhân:</strong>
                                {{ $appointment->patient->full_name ?? 'Ẩn danh' }}</p>
                            <p class="mb-1 text-muted"><strong>Thời gian:</strong>
                                {{ $appointment->appointment_time->format('d/m/Y H:i') }}</p>
                            <p class="mb-1 text-muted"><strong>Trạng thái:</strong>
                                {{ ucfirst($appointment->status) }}</p>
                            @if ($appointment->reason)
                                <p class="mb-0 text-muted"><strong>Lý do đặt:</strong>
                                    {{ $appointment->reason }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle me-1"></i> Không có lịch hẹn nào.
            </div>
        @endforelse

        <div class="mt-3">
            {{ $appointments->links() }}
        </div>
    </div>
@endsection
