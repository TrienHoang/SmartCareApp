@extends('admin.dashboard')

@section('title', 'Quản lý Lịch hẹn Khám')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-calendar-check text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Lịch hẹn Khám
                                </h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả lịch hẹn khám bệnh trong hệ thống</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                                        Lịch hẹn khám
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right">
                    <button class="btn btn-gradient-primary btn-lg waves-effect waves-light shadow-lg text-white"
                        onclick="window.location.reload()">
                        <i class="bx bx-refresh mr-2"></i>Làm mới
                    </button>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Enhanced Alert Messages -->
            {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle mr-2"></i>
                        <strong>Thành công! </strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-x-circle mr-2"></i>
                        <strong>Lỗi! </strong> {{ session('error') }}
                    </div>
                </div>
            @endif --}}

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-send font-medium-5"></i>
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
                    <div class="card gradient-card bg-gradient-info">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-calendar-check font-medium-5"></i>
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
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card gradient-card bg-gradient-warning">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-time font-medium-5"></i>
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
                    <div class="card gradient-card bg-gradient-primary">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-check font-medium-5"></i>
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
                    <div class="card gradient-card bg-gradient-secondary">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-check-double font-medium-5"></i>
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
                                        <i class="bx bx-x-circle font-medium-5"></i>
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
            </div>

            <!-- Main Content Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-list mr-2"></i>
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Lịch hẹn</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light text-dark">{{ $appointments->total() }} lịch hẹn</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('admin.appointments.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tìm kiếm bệnh nhân
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control border-left-0"
                                            placeholder="Tên, số điện thoại..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-activity mr-1 text-success"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ
                                            xác nhận</option>
                                        <option value="confirmed"
                                            {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="checked_in"
                                            {{ request('status') == 'checked_in' ? 'selected' : '' }}>Đã check in</option>
                                        <option value="completed"
                                            {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="cancelled"
                                            {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-user-voice mr-1 text-info"></i>Bác sĩ
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
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-building mr-1 text-warning"></i>Khoa
                                    </label>
                                    <select name="department_id" class="form-control custom-select">
                                        <option value="">Tất cả khoa</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-money mr-1 text-success"></i>Trạng thái thanh toán
                                    </label>
                                    <select name="payment_status" class="form-control custom-select">
                                        <option value="">Tất cả</option>
                                        <option value="paid"
                                            {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán
                                        </option>
                                        {{-- <option value="unpaid"
                                            {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán
                                        </option> --}}
                                        <option value="refunded"
                                            {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-danger"></i>Từ ngày
                                    </label>
                                    <input type="date" name="date_from" class="form-control"
                                        value="{{ old('date_from', request('date_from')) }}">
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-danger"></i>Đến ngày
                                    </label>
                                    <input type="date" name="date_to" class="form-control"
                                        value="{{ old('date_to', request('date_to')) }}">
                                </div>
                                <div class="col-lg-4 col-md-6 mb-2 d-flex align-items-end">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.appointments.index') }}"
                                            class="btn btn-outline-secondary mr-2">
                                            <i class="bx bx-refresh mr-1"></i>Reset
                                        </a>
                                        {{-- <select class="form-select form-select-sm ml-2"
                                            onchange="changePagination(this.value)" style="width: auto;">
                                            <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>
                                                15/trang</option>
                                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>
                                                25/trang</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>
                                                50/trang</option>
                                        </select> --}}
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
                                    {{-- <th class="border-top-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select-all">
                                            <label class="custom-control-label" for="select-all"></label>
                                        </div>
                                    </th> --}}
                                    <th class="border-top-0">STT</th>
                                    <th class="border-top-0">
                                        <i class="bx bx-user mr-1"></i>Bệnh nhân
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-user-voice mr-1"></i>Bác sĩ
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-building mr-1"></i>Phòng/Khoa
                                    </th>
                                    <th class="border-top-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'appointment_time', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark text-decoration-none">
                                            <i class="bx bx-time mr-1"></i>Thời gian
                                            @if (request('sort_by') == 'appointment_time')
                                                <i
                                                    class="bx bx-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-activity mr-1"></i>Trạng thái
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-money mr-1"></i>Thanh toán
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="bx bx-cog mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appointments as $key => $appointment)
                                    <tr class="appointment-row" data-id="{{ $appointment->id }}">
                                        {{-- <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input appointment-checkbox"
                                                    id="appointment-{{ $appointment->id }}"
                                                    value="{{ $appointment->id }}">
                                                <label class="custom-control-label"
                                                    for="appointment-{{ $appointment->id }}"></label>
                                            </div>
                                        </td> --}}
                                        <td class="font-weight-bold text-primary">{{ $appointments->firstItem() + $key }}
                                        </td>
                                        <td>
                                            <div class="patient-info">
                                                <h6 class="mb-0 font-weight-semibold">
                                                    {{ $appointment->patient->full_name ?? 'N/A' }}
                                                </h6>
                                                <small class="text-muted">{{ $appointment->patient->phone ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="doctor-info">
                                                <span
                                                    class="font-weight-semibold">{{ $appointment->doctor->user->full_name ?? 'N/A' }}</span>
                                                <br><small
                                                    class="text-muted">{{ $appointment->doctor->specialization ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="room-info">
                                                <span class="badge badge-outline-info">
                                                    {{ $appointment->doctor->room->name ?? 'N/A' }}
                                                </span>
                                                <br><small
                                                    class="text-muted">{{ $appointment->doctor->department->name ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="time-info">
                                                <div class="d-flex align-items-center text-info">
                                                    <i class="bx bx-calendar mr-1"></i>
                                                    <div>
                                                        <small
                                                            class="font-weight-semibold">{{ $appointment->formatted_time }}</small><br>
                                                        <small
                                                            class="text-muted">{{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                                            (Dự kiến)
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusConfig = [
                                                    'pending' => [
                                                        'class' => 'warning',
                                                        'icon' => 'time',
                                                        'text' => 'Chờ xác nhận',
                                                    ],
                                                    'confirmed' => [
                                                        'class' => 'info',
                                                        'icon' => 'check',
                                                        'text' => 'Đã xác nhận',
                                                    ],
                                                    'completed' => [
                                                        'class' => 'success',
                                                        'icon' => 'check-double',
                                                        'text' => 'Hoàn thành',
                                                    ],
                                                    'cancelled' => [
                                                        'class' => 'danger',
                                                        'icon' => 'x-circle',
                                                        'text' => 'Đã hủy',
                                                    ],
                                                    'checked_in' => [
                                                        'class' => 'primary',
                                                        'icon' => 'check-circle',
                                                        'text' => 'Đã check in',
                                                    ],
                                                ];
                                                $config = $statusConfig[$appointment->status] ?? [
                                                    'class' => 'secondary',
                                                    'icon' => 'help',
                                                    'text' => $appointment->status,
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $config['class'] }} badge-pill">
                                                <i class="bx bx-{{ $config['icon'] }} mr-1"></i>
                                                {{ $config['text'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $payment = optional($appointment->payment);
                                            @endphp

                                            @if ($payment && $payment->refund_status === 'completed')
                                                <span class="badge badge-success badge-pill">
                                                    <i class="bx bx-check-circle mr-1"></i>
                                                    Đã hoàn tiền
                                                </span>
                                            @else
                                                @switch($payment->status)
                                                    @case('paid')
                                                        <span class="badge badge-success badge-pill">
                                                            <i class="bx bx-check-circle mr-1"></i>
                                                            Đã thanh toán
                                                        </span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-danger badge-pill">
                                                            <i class="bx bx-x-circle mr-1"></i>
                                                            Chưa thanh toán
                                                        </span>
                                                @endswitch
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                                    class="btn btn-outline-info" data-toggle="tooltip"
                                                    title="Xem chi tiết">
                                                    <i class="bx bx-show"></i>
                                                </a>

                                                @if ($appointment->status != 'completed' && $appointment->status != 'cancelled')
                                                    <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                                        class="btn btn-outline-warning" data-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                @endif

                                                @if ($appointment->status === 'checked_in')
                                                    <button class="btn btn-outline-success"
                                                        onclick="updateStatus({{ $appointment->id }}, 'completed')"
                                                        data-toggle="tooltip" title="Hoàn thành">
                                                        <i class="bx bx-check-double"></i>
                                                    </button>
                                                @endif

                                                @if (optional($appointment->payment)->status !== 'paid')
                                                    @if ($appointment->status === 'confirmed')
                                                        <form
                                                            action="{{ route('admin.appointments.pay', $appointment->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button class="btn btn-outline-primary" data-toggle="tooltip"
                                                                title="Thanh toán">
                                                                <i class="bx bx-dollar"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                @if (in_array($appointment->status, ['pending', 'confirmed']))
                                                    <button class="btn btn-outline-danger"
                                                        onclick="showCancelModal({{ $appointment->id }})"
                                                        data-toggle="tooltip" title="Hủy lịch hẹn">
                                                        <i class="bx bx-x-circle"></i>
                                                    </button>
                                                @endif

                                                @if (in_array($appointment->status, ['cancelled']) &&
                                                        in_array(optional($appointment->payment)->status, ['paid']) &&
                                                        !optional($appointment->payment)->is_refunded)
                                                    <form
                                                        action="{{ route('admin.appointments.refund', $appointment->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Bạn chắc chắn muốn hoàn tiền? Hành động này không thể hoàn tác.')">
                                                        @csrf
                                                        <button class="btn btn-outline-secondary" data-toggle="tooltip"
                                                            title="Hoàn tiền VNPay">
                                                            <i class="bx bx-undo"></i>
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
                                                    <i class="bx bx-calendar-x text-muted" style="font-size: 48px;"></i>
                                                    <h5 class="mt-3 text-muted">Không có lịch hẹn nào</h5>
                                                    <p class="text-muted">Chưa có lịch hẹn nào được tạo hoặc không tìm thấy kết
                                                        quả phù hợp.</p>
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

        <!-- Modal cập nhật trạng thái -->
        <div class="modal fade" id="statusModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật trạng thái lịch hẹn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="statusForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <input type="hidden" name="current_status" id="currentStatusInput" value="">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái mới</label>
                                <select class="form-control" name="status" id="statusSelect" required>
                                    <option value="pending">Chờ xác nhận</option>
                                    <option value="confirmed">Đã xác nhận</option>
                                    <option value="completed">Hoàn thành</option>
                                    <option value="cancelled">Đã hủy</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú (tùy chọn)</label>
                                <textarea class="form-control" name="note" rows="3"
                                    placeholder="Nhập ghi chú về việc thay đổi trạng thái..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal hủy lịch hẹn --}}
        <div class="modal fade" id="cancelModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="cancelForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Xác nhận hủy lịch hẹn</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có chắc chắn muốn <strong>hủy lịch hẹn</strong> này không?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                            <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

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

            .badge-primary {
                background-color: #667eea;
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
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            }

            .bg-gradient-info {
                background: linear-gradient(135deg, #3ec6f2 0%, #60eafc 100%);
            }

            .bg-gradient-warning {
                background: linear-gradient(135deg, #fbc687 0%, #fbd786 100%);
            }

            .bg-gradient-danger {
                background: linear-gradient(135deg, #fc6076 0%, #ff9a44 100%);
            }

            .bg-gradient-secondary {
                background: linear-gradient(135deg, #56ccf2 0%, #2f80ed 100%);
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

            .patient-info h6 {
                color: #2c3e50;
            }

            .badge-outline-info {
                color: #00CFDD;
                border: 1px solid #00CFDD;
                background: transparent;
            }

            .time-info small {
                line-height: 1.2;
            }

            .filter-section {
                border-left: 4px solid #667eea;
            }

            .form-control:focus,
            .custom-select:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }

            .input-group-text {
                border-right: none;
            }

            .form-control.border-left-0 {
                border-left: none;
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
                width: 35px;
                height: 35px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .bg-rgba-white {
                background-color: rgba(255, 255, 255, 0.2);
            }

            .doctor-info,
            .patient-info,
            .room-info {
                line-height: 1.4;
            }

            .table-actions {
                display: flex;
                gap: 3px;
                align-items: center;
                flex-wrap: nowrap;
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
                    width: 100%;
                }

                .table-actions {
                    flex-direction: column;
                    gap: 2px;
                }

                .table-actions .btn {
                    width: 100%;
                    justify-content: center;
                }

                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 576px) {
                .content-header {
                    flex-direction: column;
                }

                .content-header-right {
                    text-align: center !important;
                    margin-top: 1rem;
                }

                .icon-circle {
                    width: 40px;
                    height: 40px;
                }

                .card-tools {
                    margin-top: 0.5rem;
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

            function updateStatus(id, status) {
                let swalOptions = {
                    title: 'Xác nhận cập nhật trạng thái',
                    text: `Bạn có chắc chắn muốn ${status === 'completed' ? 'hoàn thành' : 'cập nhật'} lịch hẹn này?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#667eea',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy',
                };

                // Nếu là hoàn thành thì yêu cầu nhập triệu chứng
                if (status === 'completed') {
                    swalOptions = {
                        ...swalOptions,
                        input: 'textarea',
                        inputLabel: 'Triệu chứng bệnh',
                        inputPlaceholder: 'Nhập triệu chứng của bệnh nhân...',
                        inputAttributes: {
                            'aria-label': 'Nhập triệu chứng'
                        },
                        inputValidator: (value) => {
                            if (!value || value.trim() === '') {
                                return 'Vui lòng nhập triệu chứng';
                            }
                        }
                    };
                }

                Swal.fire(swalOptions).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/appointments/${id}/update-status`;

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

                        const statusField = document.createElement('input');
                        statusField.type = 'hidden';
                        statusField.name = 'status';
                        statusField.value = status;
                        form.appendChild(statusField);

                        // Nếu là completed thì thêm field triệu chứng
                        if (status === 'completed' && result.value) {
                            const symptomsField = document.createElement('input');
                            symptomsField.type = 'hidden';
                            symptomsField.name = 'symptom_note'; // đổi thành symptom_note
                            symptomsField.value = result.value.trim();
                            form.appendChild(symptomsField);
                        }

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }


            // Show cancel modal
            function showCancelModal(id) {
                const form = document.getElementById('cancelForm');
                form.action = `/admin/appointments/${id}/cancel`;

                const statusField = form.querySelector('input[name="status"]') || document.createElement('input');
                statusField.type = 'hidden';
                statusField.name = 'status';
                statusField.value = 'cancelled';
                form.appendChild(statusField);

                $('#cancelModal').modal('show');
            }

            // Change pagination
            function changePagination(perPage) {
                const url = new URL(window.location);
                url.searchParams.set('per_page', perPage);
                window.location.href = url.toString();
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
