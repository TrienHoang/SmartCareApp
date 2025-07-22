@extends('reception.dashboard')

@section('title', 'Quản lý lịch hẹn')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý lịch hẹn</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả lịch hẹn khám bệnh</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('receptionist.dashboard') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                                        Lịch hẹn
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right">
                    <a href="{{ route('receptionist.appointments.create') }}"
                        class="btn btn-gradient-primary btn-lg waves-effect waves-light shadow-lg text-white">
                        <i class="fas fa-plus mr-2"></i>Tạo lịch hẹn mới
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Enhanced Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>Thành công!</strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Lỗi!</strong> {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card gradient-card bg-gradient-info">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="fas fa-calendar-alt font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['total'] }}</h4>
                                    <small class="text-white">Tổng số</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card gradient-card bg-gradient-warning">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="fas fa-clock font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['pending'] }}</h4>
                                    <small class="text-white">Chờ xác nhận</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card gradient-card bg-gradient-info">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="fas fa-check-circle font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['confirmed'] }}</h4>
                                    <small class="text-white">Đã xác nhận</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="fas fa-check-double font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['completed'] }}</h4>
                                    <small class="text-white">Hoàn thành</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card gradient-card bg-gradient-danger">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="fas fa-times-circle font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['cancelled'] }}</h4>
                                    <small class="text-white">Đã hủy</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card gradient-card bg-gradient-secondary">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="fas fa-calendar-day font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['today'] }}</h4>
                                    <small class="text-white">Hôm nay</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list mr-2"></i>
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách lịch hẹn</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $appointments->total() }} lịch hẹn</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('receptionist.appointments.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-2 col-md-4 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="fas fa-search mr-1 text-primary"></i>Tìm kiếm
                                    </label>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tên, SĐT bệnh nhân..." value="{{ request('search') }}">
                                </div>
                                <div class="col-lg-2 col-md-4 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="fas fa-tag mr-1 text-info"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Chờ xác nhận
                                        </option>
                                        <option value="confirmed"
                                            {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                            Đã xác nhận
                                        </option>
                                        <option value="completed"
                                            {{ request('status') == 'completed' ? 'selected' : '' }}>
                                            Hoàn thành
                                        </option>
                                        <option value="cancelled"
                                            {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                            Đã hủy
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="fas fa-user-md mr-1 text-success"></i>Bác sĩ
                                    </label>
                                    <select name="doctor_id" class="form-control custom-select">
                                        <option value="">Tất cả bác sĩ</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="fas fa-calendar mr-1 text-warning"></i>Từ ngày
                                    </label>
                                    <input type="date" name="date_from" class="form-control"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-lg-2 col-md-4 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="fas fa-calendar mr-1 text-warning"></i>Đến ngày
                                    </label>
                                    <input type="date" name="date_to" class="form-control"
                                        value="{{ request('date_to') }}">
                                </div>
                                <div class="col-lg-2 col-md-4 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('receptionist.appointments.index') }}"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-redo mr-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Enhanced Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-modern mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-top-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select-all">
                                            <label class="custom-control-label" for="select-all"></label>
                                        </div>
                                    </th>
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">
                                        <i class="fas fa-user mr-1"></i>Bệnh nhân
                                    </th>
                                    <th class="border-top-0">
                                        <i class="fas fa-user-md mr-1"></i>Bác sĩ
                                    </th>
                                    <th class="border-top-0">
                                        <i class="fas fa-stethoscope mr-1"></i>Dịch vụ
                                    </th>
                                    <th class="border-top-0">
                                        <i class="fas fa-clock mr-1"></i>Thời gian
                                    </th>
                                    <th class="border-top-0">
                                        <i class="fas fa-info-circle mr-1"></i>Trạng thái
                                    </th>
                                    <th class="border-top-0">
                                        <i class="fas fa-sticky-note mr-1"></i>Ghi chú
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="fas fa-cog mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    <tr class="appointment-row" data-id="{{ $appointment->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input appointment-checkbox"
                                                    id="appointment-{{ $appointment->id }}"
                                                    value="{{ $appointment->id }}">
                                                <label class="custom-control-label"
                                                    for="appointment-{{ $appointment->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-primary">#{{ $appointment->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center text-white mr-2">
                                                    {{ strtoupper(substr($appointment->patient->full_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-weight-semibold">
                                                        {{ $appointment->patient->full_name }}</h6>
                                                    <small class="text-muted">{{ $appointment->patient->phone }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center text-white mr-2">
                                                    {{ strtoupper(substr($appointment->doctor->user->full_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-weight-semibold">
                                                        {{ $appointment->doctor->user->full_name }}</h6>
                                                    <small
                                                        class="text-muted">{{ $appointment->doctor->specialization }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="service-info">
                                                <h6 class="mb-0 font-weight-semibold">{{ $appointment->service->name }}
                                                </h6>
                                                <small
                                                    class="text-muted">{{ number_format($appointment->service->price) }}
                                                    VND</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="time-info">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar mr-1 text-primary"></i>
                                                    <div>
                                                        <div class="font-weight-semibold">
                                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d/m/Y') }}
                                                        </div>
                                                        <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['class' => 'warning', 'icon' => 'clock'],
                                                    'confirmed' => ['class' => 'info', 'icon' => 'check-circle'],
                                                    'completed' => ['class' => 'success', 'icon' => 'check-double'],
                                                    'cancelled' => ['class' => 'danger', 'icon' => 'times-circle'],
                                                ];
                                                $config = $statusConfig[$appointment->status] ?? [
                                                    'class' => 'secondary',
                                                    'icon' => 'question',
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $config['class'] }} badge-pill">
                                                <i class="fas fa-{{ $config['icon'] }} mr-1"></i>
                                                {{ $appointment->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 100px;"
                                                title="{{ $appointment->notes ?? 'Không có ghi chú' }}">
                                                {{ $appointment->notes ?? 'Không có ghi chú' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('receptionist.appointments.show', $appointment) }}"
                                                    class="btn btn-outline-info" data-toggle="tooltip"
                                                    title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if ($appointment->status === 'pending')
                                                    <a href="{{ route('receptionist.appointments.edit', $appointment) }}"
                                                        class="btn btn-outline-warning" data-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-success"
                                                        data-toggle="tooltip" title="Xác nhận"
                                                        onclick="confirmAppointment({{ $appointment->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        data-toggle="tooltip" title="Hủy lịch hẹn"
                                                        onclick="cancelAppointment({{ $appointment->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif ($appointment->status === 'confirmed')
                                                    <button type="button" class="btn btn-outline-danger"
                                                        data-toggle="tooltip" title="Hủy lịch hẹn"
                                                        onclick="cancelAppointment({{ $appointment->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif

                                                @if ($appointment->payment && $appointment->payment->status !== 'paid')
                                                    <form method="POST"
                                                        action="{{ route('receptionist.appointments.confirm-payment', $appointment->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="btn btn-outline-success" data-toggle="tooltip"
                                                            title="Xác nhận đã thanh toán">
                                                            <i class="fas fa-money-check-alt"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-calendar-times text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có lịch hẹn nào</h5>
                                                <p class="text-muted">Chưa có lịch hẹn nào được tạo hoặc không tìm thấy kết
                                                    quả phù hợp.</p>
                                                <a href="{{ route('receptionist.appointments.create') }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-plus mr-1"></i>Tạo lịch hẹn đầu tiên
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if ($appointments->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $appointments->firstItem() }} - {{ $appointments->lastItem() }}
                                        trong tổng số {{ $appointments->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $appointments->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <!-- Custom Styles -->
        <style>
            .icon-circle {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .icon-circle i {
                margin: 0px auto
            }

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

            .badge-pill {
                border-radius: 10rem;
                padding: 0.25em 0.6em;
            }

            .bg-gradient-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .btn-gradient-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            }

            .btn-gradient-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            }

            .gradient-card {
                border: none;
                border-radius: 10px;
                transition: transform 0.3s ease;
            }

            .gradient-card:hover {
                transform: translateY(-2px);
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

            .bg-gradient-secondary {
                background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            }

            .table-modern {
                font-size: 0.9rem;
            }

            .table-modern td {
                vertical-align: middle;
                padding: 1rem 0.75rem;
            }

            .appointment-row:hover {
                background-color: rgba(102, 126, 234, 0.05);
            }

            .avatar-sm {
                width: 35px;
                height: 35px;
                font-size: 0.8rem;
            }

            .service-info h6 {
                color: #2c3e50;
            }

            .time-info {
                min-width: 120px;
            }

            .filter-section {
                border-left: 4px solid #667eea;
            }

            .form-control:focus,
            .custom-select:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }

            .empty-state {
                padding: 2rem;
            }

            .pagination-wrapper {
                background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
            }

            .btn-group-sm .btn {
                border-radius: 4px;
                margin-right: 2px;
            }

            .btn-group-sm .btn:last-child {
                margin-right: 0;
            }

            .avatar {
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .bg-rgba-white {
                background-color: rgba(255, 255, 255, 0.2);
            }

            @media (max-width: 768px) {
                .filter-form .row>div {
                    margin-bottom: 1rem;
                }

                .btn-group-sm {
                    flex-direction: column;
                }

                .btn-group-sm .btn {
                    margin-bottom: 2px;
                    margin-right: 0;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Select all checkboxes functionality
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.appointment-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            function confirmAppointment(id) {
                Swal.fire({
                    title: 'Xác nhận lịch hẹn',
                    text: 'Bạn có chắc chắn muốn xác nhận lịch hẹn này?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitAppointmentAction(id, 'confirm');
                    }
                });
            }

            function cancelAppointment(id) {
                Swal.fire({
                    title: 'Hủy lịch hẹn',
                    text: 'Bạn có chắc chắn muốn hủy lịch hẹn này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hủy lịch hẹn',
                    cancelButtonText: 'Đóng'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitAppointmentAction(id, 'cancel');
                    }
                });
            }

            function completeAppointment(id) {
                Swal.fire({
                    title: 'Hoàn thành lịch hẹn',
                    text: 'Bạn có chắc chắn muốn đánh dấu lịch hẹn này là đã hoàn thành?',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hoàn thành',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitAppointmentAction(id, 'complete');
                    }
                });
            }

            function submitAppointmentAction(id, action) {
                const form = document.createElement('form');
                form.method = 'POST';

                let actionUrl = '';
                switch (action) {
                    case 'confirm':
                        actionUrl = `/receptionist/appointments/${id}/update-status`;
                        break;
                    case 'cancel':
                        actionUrl = `/receptionist/appointments/${id}/cancel`;
                        break;
                }

                form.action = actionUrl;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }

            // Initialize tooltips
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();

                // Auto-hide alerts after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 5000);
            });
        </script>
    @endpush

@endsection
