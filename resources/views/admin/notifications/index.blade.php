@extends('admin.dashboard')

@section('title', 'Quản lý Thông báo')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3 ">
                                <i class="bx bx-bell text-white "></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Thông báo</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả thông báo trong hệ thống</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            </i>Trang chủ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                                        </i>Thông báo
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right">
                    <a href="{{ route('admin.notifications.create') }}"
                        class="btn btn-gradient-primary btn-lg waves-effect waves-light shadow-lg  text-white">
                        Tạo thông báo mới
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
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-x-circle mr-2"></i>
                        <strong>Lỗi! </strong> {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white   mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-send font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $statusCounts['sent'] }}</h4>
                                    <small class="text-white">Đã gửi</small>
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
                                        <i class="bx bx-calendar-check font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $statusCounts['scheduled'] }}</h4>
                                    <small class="text-white">Đã lên lịch</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-warning">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white   mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-loader  font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $statusCounts['sending'] }}</h4>
                                    <small class="text-white">Đang gửi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-danger">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white   mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-x-circle font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $statusCounts['failed'] }}</h4>
                                    <small class="text-white">Thất bại</small>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Thông báo</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $notifications->total() }} thông báo</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('admin.notifications.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tìm kiếm
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control border-left-0"
                                            placeholder="Tiêu đề, nội dung..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-tag mr-1 text-info"></i>Loại
                                    </label>
                                    <select name="type" class="form-control custom-select">
                                        <option value="">Tất cả loại</option>
                                        @foreach ($notificationTypes as $type)
                                            <option value="{{ $type }}"
                                                {{ request('type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-8 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-activity mr-1 text-success"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="">Tất cả trạng thái</option>
                                        @foreach ($notificationStatuses as $status)
                                            <option value="{{ $status }}"
                                                {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-50" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.notifications.index') }}"
                                            class="btn btn-outline-secondary">
                                            <i class="bx bx-refresh-cw mr-1"></i>Reset
                                        </a>
                                        {{-- <button type="button" class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" 
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" onclick="exportData('excel')">
                                                <i class="bx bx-download mr-2"></i>Xuất Excel
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="exportData('pdf')">
                                                <i class="bx bx-file-text mr-2"></i>Xuất PDF
                                            </a>
                                        </div> --}}
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
                                        <i class="mr-1"></i>Tiêu đề
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Loại
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Người nhận
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Trạng thái
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Thời gian
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifications as $notification)
                                    <tr class="notification-row" data-id="{{ $notification->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input notification-checkbox"
                                                    id="notification-{{ $notification->id }}"
                                                    value="{{ $notification->id }}">
                                                <label class="custom-control-label"
                                                    for="notification-{{ $notification->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-primary">#{{ $notification->id }}</td>
                                        <td>
                                            <div class="notification-title">
                                                <h6 class="mb-0 font-weight-semibold">
                                                    {{ Str::limit($notification->title, 40) }}</h6>
                                                <small
                                                    class="text-muted">{{ Str::limit(strip_tags($notification->content), 60) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-outline-primary">
                                                {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="recipient-info">
                                                @if ($notification->recipient_type == 'all')
                                                    <span class="badge badge-success">
                                                        <i class="bx bx-globe mr-1"></i>Tất cả người dùng
                                                    </span>
                                                @elseif ($notification->recipient_type == 'specific_users')
                                                    <span class="badge badge-warning">
                                                        <i
                                                            class="bx bx-user mr-1"></i>{{ count($notification->display_recipients ?? []) }}
                                                        người dùng
                                                    </span>
                                                @elseif ($notification->recipient_type == 'roles')
                                                    <span class="badge badge-info">
                                                        <i
                                                            class="bx bx-shield mr-1"></i>{{ implode(', ', array_map('ucfirst', $notification->display_recipients ?? [])) }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusConfig = [
                                                    'sent' => ['class' => 'success', 'icon' => 'check-circle'],
                                                    'scheduled' => ['class' => 'info', 'icon' => 'calendar-check'],
                                                    'sending' => ['class' => 'warning', 'icon' => 'loader'],
                                                    'failed' => ['class' => 'danger', 'icon' => 'x-circle'],
                                                    'draft' => ['class' => 'secondary', 'icon' => 'edit-3'],
                                                ];
                                                $config =
                                                    $statusConfig[$notification->status] ?? $statusConfig['draft'];
                                            @endphp
                                            <span class="badge badge-{{ $config['class'] }} badge-pill">
                                                <i class="bx bx-{{ $config['icon'] }} mr-1"></i>
                                                {{ ucfirst($notification->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="time-info">
                                                @if ($notification->sent_at)
                                                    <div class="d-flex align-items-center text-success">
                                                        <i class="bx bx-send mr-1"></i>
                                                        <div>
                                                            <small class="font-weight-semibold">Đã gửi</small><br>
                                                            <small
                                                                class="text-muted">{{ $notification->sent_at->format('d/m/Y H:i') }}</small>
                                                        </div>
                                                    </div>
                                                @elseif ($notification->scheduled_at)
                                                    <div class="d-flex align-items-center text-info">
                                                        <i class="bx bx-calendar mr-1"></i>
                                                        <div>
                                                            <small class="font-weight-semibold">Lên lịch</small><br>
                                                            <small
                                                                class="text-muted">{{ $notification->scheduled_at->format('d/m/Y H:i') }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center text-muted">
                                                        <i class="bx bx-clock mr-1"></i>
                                                        <small>Chưa gửi</small>
                                                    </div>
                                                @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.notifications.show', $notification) }}"
                                                    class="btn btn-outline-info" data-toggle="tooltip"
                                                    title="Xem chi tiết">
                                                    <i class='bx bx-show-alt'></i>
                                                </a>

                                                @if ($notification->status != 'sent' && $notification->status != 'sending')
                                                    <a href="{{ route('admin.notifications.edit', $notification) }}"
                                                        class="btn btn-outline-warning" data-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    @if ($notification->status != 'scheduled')
                                                        <button type="button" class="btn btn-outline-primary"
                                                            data-toggle="tooltip" title="Gửi ngay"
                                                            onclick="sendNotification({{ $notification->id }})">
                                                            <i class="bx bx-send"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-toggle="tooltip" title="Xóa"
                                                    onclick="deleteNotification({{ $notification->id }})">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-bell-off text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có thông báo nào</h5>
                                                <p class="text-muted">Chưa có thông báo nào được tạo hoặc không tìm thấy
                                                    kết quả phù hợp.</p>
                                                <a href="{{ route('admin.notifications.create') }}"
                                                    class="btn btn-primary">
                                                    <i class="bx bx-plus mr-1"></i>Tạo thông báo đầu tiên
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if ($notifications->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $notifications->firstItem() }} - {{ $notifications->lastItem() }}
                                        trong tổng số {{ $notifications->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $notifications->links('pagination::bootstrap-4') }}
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

        .table-modern {
            font-size: 0.9rem;
        }

        .table-modern td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        .notification-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .notification-title h6 {
            color: #2c3e50;
        }

        .badge-outline-primary {
            color: #667eea;
            border: 1px solid #667eea;
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
            const checkboxes = document.querySelectorAll('.notification-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Send notification function
        function sendNotification(id) {
            Swal.fire({
                title: 'Xác nhận gửi thông báo',
                text: 'Bạn có chắc chắn muốn gửi thông báo này ngay lập tức?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Gửi ngay',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/notifications/${id}/send-now`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Delete notification function
        function deleteNotification(id) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa thông báo này? Hành động này không thể hoàn tác!',
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
                    form.action = `/admin/notifications/${id}`;

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
        }

        // Export data function
        function exportData(format) {
            // Implement export logic here
            Swal.fire({
                title: 'Đang xuất dữ liệu...',
                text: `Đang xuất dữ liệu dạng ${format.toUpperCase()}`,
                icon: 'info',
                timer: 2000,
                showConfirmButton: false
            });
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
