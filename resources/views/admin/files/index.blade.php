@extends('admin.dashboard')

@section('title', 'Quản lý Tài liệu Y tế')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-file text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Tài liệu Y tế
                                </h2>
                                <p class="text-muted mb-0">Theo dõi và quản lý tất cả tài liệu y tế trong hệ thống</p>
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
                                        Tài liệu Y tế
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right">
                    <a href="{{ route('admin.files.trash') }}"
                        class="btn btn-gradient-primary btn-lg waves-effect waves-light shadow-lg text-white">
                        <i class="bx bx-trash mr-2"></i>
                        Thùng rác
                    </a>
                </div>
                <form id="export-form" action="{{ route('admin.files.export') }}" method="GET">
                    @csrf
                    <input type="hidden" name="ids" id="export-ids">
                    <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                    <input type="hidden" name="uploader_type" value="{{ request('uploader_type') }}">
                    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    <input type="hidden" name="file_category" value="{{ request('file_category') }}">
                    <button type="submit" class="btn btn-success">Xuất Excel</button>
                </form>
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
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-file font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ number_format($totalFiles) }}</h4>
                                    <small class="text-white">Tổng số file</small>
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
                                        <i class="bx bx-hdd font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ number_format($totalSize / 1024 / 1024, 1) }}GB</h4>
                                    <small class="text-white">Dung lượng</small>
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
                                        <i class="bx bx-calendar-alt font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $files->where('uploaded_at', '>=', today())->count() }}
                                    </h4>
                                    <small class="text-white">Hôm nay</small>
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
                                        <i class="bx bx-bar-chart-alt-2 font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">
                                        {{ $files->where('uploaded_at', '>=', now()->startOfWeek())->count() }}</h4>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Tài liệu Y tế</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $files->total() }} tài liệu</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('admin.files.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tìm kiếm
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control border-left-0"
                                            placeholder="Tên file, mô tả..." value="{{ request('keyword') }}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-user mr-1 text-info"></i>Người tải
                                    </label>
                                    <select name="uploader_type" class="form-control custom-select">
                                        <option value="">Tất cả</option>
                                        <option value="doctor"
                                            {{ request('uploader_type') == 'doctor' ? 'selected' : '' }}>
                                            Bác sĩ
                                        </option>
                                        <option value="patient"
                                            {{ request('uploader_type') == 'patient' ? 'selected' : '' }}>
                                            Bệnh nhân
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-success"></i>Từ ngày
                                    </label>
                                    <input type="date" name="date_from" class="form-control"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar mr-1 text-success"></i>Đến ngày
                                    </label>
                                    <input type="date" name="date_to" class="form-control"
                                        value="{{ request('date_to') }}">
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-category mr-1 text-warning"></i>Danh mục
                                    </label>
                                    <select name="file_category" class="form-control custom-select">
                                        <option value="">Tất cả danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}"
                                                {{ request('file_category') == $category ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('-', ' ', $category)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.files.index') }}" class="btn btn-outline-secondary">
                                            <i class="bx bx-refresh-cw mr-1"></i>Reset
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
                                    <th class="border-top-0">STT</th>
                                    <th class="border-top-0">
                                        <i class="bx bx-file mr-1"></i>Thông tin file
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-user mr-1"></i>Người tải lên
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-category mr-1"></i>Danh mục
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-time mr-1"></i>Thời gian
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-calendar mr-1"></i>Lịch hẹn
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-note mr-1"></i>Ghi chú
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="bx bx-cog mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($files as $file)
                                    <tr class="file-row" data-id="{{ $file->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input file-checkbox"
                                                    id="file-{{ $file->id }}" value="{{ $file->id }}">
                                                <label class="custom-control-label"
                                                    for="file-{{ $file->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-primary">
                                            {{ $loop->iteration + ($files->currentPage() - 1) * $files->perPage() }}</td>
                                        <td>
                                            <div class="file-info d-flex align-items-center">
                                                <div class="file-icon-modern bg-gradient-primary text-white mr-3">
                                                    <i class="bx bx-file"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-weight-semibold">
                                                        {{ Str::limit($file->file_name, 30) }}</h6>
                                                    <small class="text-muted">
                                                        <i class="bx bx-weight mr-1"></i>
                                                        {{ number_format($file->file_size / 1024, 1) }} KB
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="user-info d-flex align-items-center">
                                                <div class="user-avatar-modern bg-gradient-info text-white mr-2">
                                                    {{ substr($file->user?->full_name ?? 'N', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-weight-semibold">
                                                        {{ $file->user?->full_name ?? 'Không xác định' }}</div>
                                                    <small class="text-muted">ID: {{ $file->user_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($file->file_category)
                                                <span class="badge badge-outline-primary">
                                                    {{ ucfirst(str_replace('-', ' ', $file->file_category)) }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Không có</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="time-info">
                                                <div class="d-flex align-items-center text-success">
                                                    <i class="bx bx-time mr-1"></i>
                                                    <div>
                                                        <small
                                                            class="font-weight-semibold">{{ $file->uploaded_at?->format('d/m/Y') ?? 'Chưa rõ' }}</small><br>
                                                        <small
                                                            class="text-muted">{{ $file->uploaded_at?->format('H:i') ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="appointment-info">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-calendar-check mr-1 text-info"></i>
                                                    <div>
                                                        <small
                                                            class="font-weight-semibold">#{{ $file->appointment_id }}</small>
                                                        @if ($file->appointment)
                                                            <br><small
                                                                class="text-muted">{{ $file->appointment->appointment_time->format('d/m/Y H:i') }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="note-content" style="max-width: 120px;">
                                                <span class="text-truncate d-block" title="{{ $file->note }}">
                                                    {{ $file->note ?? '-' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.files.show', $file->id) }}" target="_blank"
                                                    class="btn btn-outline-info" data-toggle="tooltip" title="Xem file">
                                                    <i class="bx bx-show-alt"></i>
                                                </a>
                                                <a href="{{ route('admin.files.download', $file->id) }}" download
                                                    class="btn btn-outline-success" data-toggle="tooltip"
                                                    title="Tải xuống">
                                                    <i class="bx bx-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-toggle="tooltip" title="Xóa file"
                                                    onclick="deleteFile({{ $file->id }}, '{{ addslashes($file->file_name) }}')">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-file-medical text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có tài liệu nào</h5>
                                                <p class="text-muted">Chưa có tài liệu y tế nào được tải lên hệ thống hoặc
                                                    không tìm thấy kết quả phù hợp.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if ($files->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $files->firstItem() }} - {{ $files->lastItem() }}
                                        trong tổng số {{ $files->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $files->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="bx bx-error-circle text-warning mr-2"></i>
                        Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa tài liệu này không? Hành động này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="bx bx-trash mr-2"></i>
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
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

        .file-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .file-info h6 {
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

        .file-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .user-avatar-modern {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .appointment-info {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 3px solid #00CFDD;
        }

        .note-content {
            background: #f8f9fa;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
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

            .file-info {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .file-icon-modern {
                margin-bottom: 8px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Select all checkboxes functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.file-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        document.getElementById('export-form').addEventListener('submit', function(e) {
            const checked = Array.from(document.querySelectorAll('.file-checkbox:checked'))
                .map(cb => cb.value);

            document.getElementById('export-ids').value = checked.join(',');
        });


        // Delete file function
        function deleteFile(id, fileName) {
            Swal.fire({
                title: 'Xác nhận xóa',
                html: `Bạn có chắc chắn muốn xóa tài liệu <strong>${fileName}</strong> không?<br>Hành động này không thể hoàn tác!`,
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
                    form.action = `/admin/files/${id}`;

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

        // Initialize tooltips
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Auto-submit form on filter change
            $('#filterForm select').on('change', function() {
                $('#filterForm').submit();
            });
        });
    </script>
@endpush
