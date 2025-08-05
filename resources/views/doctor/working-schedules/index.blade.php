@extends('doctor.dashboard')

@section('title', 'Lịch làm việc của bác sĩ')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-calendar text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Lịch làm việc
                                </h2>
                                <p class="text-muted mb-0">Lịch làm việc và ca trực của bác sĩ</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('doctor.dashboard') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                                        Lịch làm việc
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right mb-3">
                    <a href="{{ route('doctor.working_schedules.create') }}"
                        class="btn btn-primary btn-lg waves-effect waves-light shadow-lg text-white">
                        <i class="bx bx-plus mr-2"></i>
                        Thêm lịch làm việc
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Enhanced Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle mr-2"></i>
                        <strong>Thành công! </strong> {{ session('success') }}
                    </div>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-x-circle mr-2"></i>
                        <strong>Lỗi! </strong> {{ session('error') }}
                    </div>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-x-circle mr-2"></i>
                        <strong>Lỗi! </strong>
                    </div>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-calendar-alt font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $workingSchedules->total() }}</h4>
                                    <small class="text-white">Tổng lịch làm việc</small>
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
                                        <i class="bx bx-time-five font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">
                                        {{ $workingSchedules->where('status', 'Chờ xét duyệt')->count() }}
                                    </h4>
                                    <small class="text-white">Chờ xét duyệt</small>
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
                                        <i class="bx bx-check-circle font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">
                                        {{ $workingSchedules->where('status', 'Đã xét duyệt')->count() }}
                                    </h4>
                                    <small class="text-white">Đã xét duyệt</small>
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
                                        <i class="bx bx-calendar-week font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">
                                        {{ $workingSchedules->where('day', '>=', now()->startOfWeek())->where('day', '<=', now()->endOfWeek())->count() }}
                                    </h4>
                                    <small class="text-white">Tuần này</small>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách lịch làm việc</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $workingSchedules->total() }} lịch</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form method="GET" action="{{ route('doctor.working_schedules.index') }}" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-primary"></i>Từ ngày
                                    </label>
                                    <input type="date" name="from_date" class="form-control"
                                        value="{{ request('from_date') }}">
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-primary"></i>Đến ngày
                                    </label>
                                    <input type="date" name="to_date" class="form-control"
                                        value="{{ request('to_date') }}">
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-info-circle mr-1 text-info"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="Chờ xét duyệt"
                                            {{ request('status') == 'Chờ xét duyệt' ? 'selected' : '' }}>
                                            Chờ xét duyệt
                                        </option>
                                        <option value="Đã xét duyệt" {{ request('status') == 'Đã xét duyệt' ? 'selected' : '' }}>
                                            Đã xét duyệt
                                        </option>
                                        <option value="Từ chối" {{ request('status') == 'Từ chối' ? 'selected' : '' }}>
                                            Từ chối
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-time-five mr-1 text-success"></i>Ca trực
                                    </label>
                                    <select name="shift_id" class="form-control custom-select">
                                        <option value="">Tất cả ca trực</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}" {{ request('shift_id') == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 mt-2">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('doctor.working_schedules.index') }}"
                                            class="btn btn-outline-secondary">
                                            <i class="bx bx-refresh-cw mr-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Enhanced Table -->
                    <div class="table-responsive">
                        @if ($workingSchedules->count() > 0)
                            <table class="table table-hover table-modern mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-top-0">
                                            <i class="bx bx-hash mr-1"></i>STT
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-calendar mr-1"></i>Ngày làm việc
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-time mr-1"></i>Ca làm việc
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-time-five mr-1"></i>Thời gian
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-info-circle mr-1"></i>Trạng thái
                                        </th>
                                        <th class="border-top-0 text-center">
                                            <i class="bx bx-cog mr-1"></i>Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workingSchedules as $index => $schedule)
                                        <tr class="schedule-row">
                                            <td class="font-weight-bold text-primary">
                                                {{ $workingSchedules->firstItem() + $index }}
                                            </td>
                                            <td>
                                                <div class="date-info d-flex align-items-center">
                                                    <div class="date-icon-modern bg-gradient-info text-white mr-3">
                                                        <i class="bx bx-calendar-alt"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 font-weight-semibold">
                                                            {{ $schedule->day ? \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') : 'Chưa có ngày' }}
                                                        </h6>
                                                        <small class="text-muted">
                                                            {{ $schedule->day ? \Carbon\Carbon::parse($schedule->day)->locale('vi')->translatedFormat('l') : '' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="shift-info">
                                                    <div class="d-flex align-items-center">
                                                        <div class="shift-icon-modern bg-gradient-success text-white mr-2">
                                                            <i class="bx bx-time"></i>
                                                        </div>
                                                        <div>
                                                            <div class="font-weight-semibold">
                                                                {{ $schedule->shift?->name ?? 'Chưa xác định' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="time-info bg-light p-2 rounded">
                                                    <div class="d-flex align-items-center text-success">
                                                        <i class="bx bx-time mr-1"></i>
                                                        <div>
                                                            <small class="font-weight-semibold">
                                                                {{ $schedule->shift?->start_time ? \Carbon\Carbon::parse($schedule->shift->start_time)->format('H:i') : 'Chưa có' }}
                                                                -
                                                                {{ $schedule->shift?->end_time ? \Carbon\Carbon::parse($schedule->shift->end_time)->format('H:i') : 'Chưa có' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = '';
                                                    $statusIcon = '';
                                                    switch ($schedule->status) {
                                                        case 'Chờ xét duyệt':
                                                            $statusClass = 'badge-warning';
                                                            $statusIcon = 'bx-time';
                                                            break;
                                                        case 'Đã xét duyệt':
                                                            $statusClass = 'badge-success';
                                                            $statusIcon = 'bx-check-circle';
                                                            break;
                                                        case 'Từ chối':
                                                            $statusClass = 'badge-danger';
                                                            $statusIcon = 'bx-x-circle';
                                                            break;
                                                        default:
                                                            $statusClass = 'badge-secondary';
                                                            $statusIcon = 'bx-info-circle';
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusClass }} badge-pill">
                                                    <i class="bx {{ $statusIcon }} mr-1"></i>
                                                    {{ $schedule->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($schedule->status === 'Chờ xét duyệt')
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('doctor.working_schedules.edit', $schedule->id) }}"
                                                            class="btn btn-outline-warning" data-toggle="tooltip"
                                                            title="Chỉnh sửa">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger delete-btn"
                                                            data-schedule-id="{{ $schedule->id }}"
                                                            data-schedule-date="{{ $schedule->day ? \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') : 'Chưa có ngày' }}"
                                                            data-toggle="tooltip" title="Xóa">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-muted font-italic">
                                                        <i class="bx bx-lock-alt mr-1"></i>
                                                        Không khả dụng
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Enhanced Pagination -->
                            @if ($workingSchedules->hasPages())
                                <div class="pagination-wrapper bg-light p-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="pagination-info">
                                            <small class="text-muted">
                                                Hiển thị {{ $workingSchedules->firstItem() }} đến
                                                {{ $workingSchedules->lastItem() }}
                                                trong tổng số {{ $workingSchedules->total() }} lịch làm việc
                                            </small>
                                        </div>
                                        <div class="pagination-links">
                                            {{ $workingSchedules->appends(request()->query())->links() }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bx bx-calendar-x text-muted" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 text-muted">Chưa có lịch làm việc nào</h5>
                                    <p class="text-muted mb-4">Hãy tạo lịch làm việc đầu tiên của bạn</p>
                                    <a href="{{ route('doctor.working_schedules.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus mr-2"></i> Thêm lịch làm việc
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="bx bx-error-circle text-warning mr-2"></i>
                        Xác nhận xóa lịch làm việc
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa lịch làm việc ngày <strong id="scheduleDate"></strong>?</p>
                    <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bx bx-trash mr-2"></i>Xóa
                        </button>
                    </form>
                </div>



                
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
            margin: 0px auto;
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

        .badge-light {
            background-color: #f8f9fa;
            color: #212529 !important;
        }

        .bx {
            font-family: 'boxicons' !important;
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            speak: none;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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

        .table-modern {
            font-size: 0.9rem;
        }

        .table-modern td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        .schedule-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .date-info h6 {
            color: #2c3e50;
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

        .date-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .shift-icon-modern {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .time-info {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 3px solid #39DA8A;
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

            .date-info {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .date-icon-modern {
                margin-bottom: 8px;
            }

            .content-header-right {
                text-align: left !important;
                margin-top: 1rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Xử lý xóa lịch làm việc
            $('.delete-btn').click(function() {
                const scheduleId = $(this).data('schedule-id');
                const scheduleDate = $(this).data('schedule-date');

                Swal.fire({
                    title: 'Xác nhận xóa',
                    html: `Bạn có chắc chắn muốn xóa lịch làm việc ngày <strong>${scheduleDate}</strong> không?<br><small class="text-danger">Hành động này không thể hoàn tác!</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/doctor/working_schedules/${scheduleId}`;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Auto-submit form on filter change
            $('.filter-form select').on('change', function() {
                $('.filter-form').submit();
            });

            // Enhanced table interactions
            $('.schedule-row').hover(
                function() {
                    $(this).addClass('table-active');
                },
                function() {
                    $(this).removeClass('table-active');
                }
            );

            // Responsive table scroll indicator
            const tableContainer = $('.table-responsive');
            if (tableContainer.length) {
                tableContainer.on('scroll', function() {
                    const scrollLeft = $(this).scrollLeft();
                    const scrollWidth = $(this)[0].scrollWidth;
                    const clientWidth = $(this)[0].clientWidth;

                    if (scrollLeft > 0) {
                        $(this).addClass('scrolled-left');
                    } else {
                        $(this).removeClass('scrolled-left');
                    }

                    if (scrollLeft < scrollWidth - clientWidth) {
                        $(this).addClass('scrolled-right');
                    } else {
                        $(this).removeClass('scrolled-right');
                    }
                });
            }

            // Date range validation
            $('input[name="from_date"], input[name="to_date"]').on('change', function() {
                const fromDate = $('input[name="from_date"]').val();
                const toDate = $('input[name="to_date"]').val();

                if (fromDate && toDate && fromDate > toDate) {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Ngày bắt đầu không thể lớn hơn ngày kết thúc!',
                        icon: 'error',
                        confirmButtonText: 'Đóng'
                    });
                    $(this).val('');
                }
            });

            // Quick filter buttons
            $('.quick-filter').on('click', function(e) {
                e.preventDefault();
                const filter = $(this).data('filter');

                switch (filter) {
                    case 'today':
                        const today = new Date().toISOString().split('T')[0];
                        $('input[name="from_date"]').val(today);
                        $('input[name="to_date"]').val(today);
                        break;
                    case 'this_week':
                        const now = new Date();
                        const startOfWeek = new Date(now.setDate(now.getDate() - now.getDay()));
                        const endOfWeek = new Date(now.setDate(now.getDate() - now.getDay() + 6));
                        $('input[name="from_date"]').val(startOfWeek.toISOString().split('T')[0]);
                        $('input[name="to_date"]').val(endOfWeek.toISOString().split('T')[0]);
                        break;
                    case 'this_month':
                        const date = new Date();
                        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
                        const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                        $('input[name="from_date"]').val(firstDay.toISOString().split('T')[0]);
                        $('input[name="to_date"]').val(lastDay.toISOString().split('T')[0]);
                        break;
                }

                $('.filter-form').submit();
            });
        });

        // Additional utility functions
        function formatDate(dateString) {
            if (!dateString) return 'Chưa có ngày';
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN');
        }

        function formatTime(timeString) {
            if (!timeString) return 'Chưa có';
            const time = new Date(`2000-01-01T${timeString}`);
            return time.toLocaleTimeString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Loading state for buttons
        function setButtonLoading(button, loading = true) {
            if (loading) {
                button.prop('disabled', true);
                button.find('i').removeClass().addClass('bx bx-loader-alt bx-spin');
            } else {
                button.prop('disabled', false);
                button.find('i').removeClass('bx-loader-alt bx-spin');
            }
        }

        // Status badge color helper
        function getStatusBadgeClass(status) {
            switch (status) {
                case 'Chờ xét duyệt':
                    return 'badge-warning';
                case 'Đã xét duyệt':
                    return 'badge-success';
                case 'Từ chối':
                    return 'badge-danger';
                default:
                    return 'badge-secondary';
            }
        }

        // Confirm before form submission for critical actions
        $('.critical-action').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const action = $(this).data('action') || 'thực hiện hành động này';

            Swal.fire({
                title: 'Xác nhận',
                text: `Bạn có chắc chắn muốn ${action}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
