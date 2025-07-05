@extends('admin.dashboard')

@section('title', 'Quản lý Lịch làm việc bác sĩ')

@section('content')
    <div class="content-wrapper">
        <!-- Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-calendar text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Lịch làm việc bác sĩ</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi lịch làm việc của các bác sĩ trong hệ thống</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb bg-transparent p-0">
                                <li>
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">Trang chủ ></a>
                                </li>
                                <li class="breadcrumb-item active text-primary font-weight-semibold">Lịch làm việc bác sĩ</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right">
                    <a href="{{ route('admin.schedules.create') }}" class="btn btn-gradient-primary btn-lg waves-effect waves-light shadow-lg text-white">
                        Thêm lịch làm việc
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content"><i class="bx bx-user font-medium-5"></i></div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['total_doctors'] ?? 0 }}</h4>
                                    <small class="text-white">Tổng bác sĩ</small>
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
                                    <div class="avatar-content"><i class="bx bx-calendar font-medium-5"></i></div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['total_schedules'] ?? 0 }}</h4>
                                    <small class="text-white">Tổng lịch làm việc</small>
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
                                    <div class="avatar-content"><i class="bx bx-calendar-check font-medium-5"></i></div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['today_schedules'] ?? 0 }}</h4>
                                    <small class="text-white">Lịch hôm nay</small>
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
                                    <div class="avatar-content"><i class="bx bx-calendar-x font-medium-5"></i></div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $stats['no_schedule_doctors'] ?? 0 }}</h4>
                                    <small class="text-white">Bác sĩ chưa có lịch</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle mr-2"></i>
                        <strong>Thành công!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-x-circle mr-2"></i>
                        <strong>Lỗi!</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Main Content Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-list mr-2"></i>
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Lịch làm việc</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $schedules->total() ?? 0 }} lịch làm việc</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('admin.schedules.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-4 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tên bác sĩ
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control border-left-0"
                                               placeholder="Tìm theo tên..." value="{{ request('keyword') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-info"></i>Thứ
                                    </label>
                                    <select name="day_of_week" class="form-control custom-select">
                                        <option value="">Tất cả</option>
                                        @foreach(['Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy', 'Chủ nhật'] as $thu)
                                            <option value="{{ $thu }}" {{ request('day_of_week') === $thu ? 'selected' : '' }}>
                                                {{ $thu }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-filter mr-1"></i>Lọc
                                    </button>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="bx bx-refresh-cw mr-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-modern mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#ID</th>
                                    <th>Bác sĩ</th>
                                    <th>Ngày</th>
                                    <th>Thứ</th>
                                    <th>Giờ bắt đầu</th>
                                    <th>Giờ kết thúc</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td class="font-weight-bold text-primary">#{{ $schedule->id }}</td>
                                        <td class="text-left">{{ $schedule->doctor->user->full_name ?? 'Không rõ' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge badge-info badge-pill">
                                                <i class="bx bx-calendar mr-1"></i>{{ $schedule->day_of_week }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success badge-pill">
                                                <i class="bx bx-time mr-1"></i>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning badge-pill">
                                                <i class="bx bx-time-five mr-1"></i>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.schedules.show', $schedule->id) }}"
                                                   class="btn btn-outline-info" data-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="bx bx-show-alt"></i>
                                                </a>
                                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                                                   class="btn btn-outline-warning" data-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST"
                                                      class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xoá lịch này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger" data-toggle="tooltip" title="Xóa">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-calendar-off text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có lịch làm việc nào</h5>
                                                <p class="text-muted">Chưa có lịch nào được tạo hoặc không tìm thấy kết quả phù hợp.</p>
                                                <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                                                    <i class="bx bx-plus mr-1"></i>Thêm lịch đầu tiên
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($schedules->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $schedules->firstItem() ?? 0 }} - {{ $schedules->lastItem() ?? 0 }}
                                        trong tổng số {{ $schedules->total() ?? 0 }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $schedules->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .badge-success { background-color: #39DA8A; color: #fff; }
        .badge-info { background-color: #00CFDD; color: #fff; }
        .badge-warning { background-color: #FDAC41; color: #212529; }
        .badge-danger { background-color: #FF5B5C; color: #fff; }
        .badge-secondary { background-color: #6c757d; color: #fff; }
        .badge-pill { border-radius: 10rem; padding: 0.25em 0.6em; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
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
        .gradient-card:hover { transform: translateY(-2px); }
        .bg-gradient-success { background: linear-gradient(135deg, #00cec9 0%, #55a3ff 100%); }
        .bg-gradient-info { background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); }
        .bg-gradient-danger { background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%); }
        .table-modern { font-size: 0.95rem; }
        .table-modern td { vertical-align: middle; padding: 1rem 0.75rem; }
        .table-modern tbody tr:hover { background-color: rgba(102, 126, 234, 0.05); }
        .badge-outline-primary { color: #667eea; border: 1px solid #667eea; background: transparent; }
        .filter-section { border-left: 4px solid #667eea; }
        .form-control:focus, .custom-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .empty-state { padding: 2rem; }
        .pagination-wrapper { background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%); }
        .btn-group-sm .btn { border-radius: 4px; margin-right: 2px; }
        .btn-group-sm .btn:last-child { margin-right: 0; }
        @media (max-width: 768px) {
            .filter-form .row > div { margin-bottom: 1rem; }
            .btn-group-sm { flex-direction: column; }
            .btn-group-sm .btn { margin-bottom: 2px; margin-right: 0; }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush
