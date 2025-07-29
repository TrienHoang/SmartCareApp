@extends('doctor.dashboard')

@section('title', 'Quản lý File Tải lên')

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
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý File Tải lên</h2>
                                <p class="text-muted mb-0">Quản lý các file đã tải lên cho bệnh nhân</p>
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
                                        Quản lý File
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right mb-3">
                    <a href="{{ route('doctor.files.create') }}"
                        class="btn btn-primary btn-lg waves-effect waves-light shadow-lg text-white mr-2">
                        <i class="bx bx-plus mr-2"></i>
                        Tải lên File
                    </a>
                    <a href="{{ route('doctor.files.trash') }}"
                        class="btn btn-danger btn-lg waves-effect waves-light shadow-lg text-white">
                        <i class="bx bx-trash mr-2"></i>
                        File đã xóa
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
                                    <h4 class="text-white mb-0">{{ $files->total() }}</h4>
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
                    <div class="card gradient-card bg-gradient-warning">
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
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-danger">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-user font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $files->groupBy('appointment.patient_id')->count() }}
                                    </h4>
                                    <small class="text-white">Bệnh nhân</small>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách File</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $files->total() }} file</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form method="GET" action="{{ route('doctor.files.index') }}" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tìm theo tên file
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control border-left-0"
                                            value="{{ request('search') }}" placeholder="Nhập tên file...">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-category mr-1 text-info"></i>Danh mục
                                    </label>
                                    <select name="category" class="form-control custom-select">
                                        <option value="">Tất cả danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}"
                                                {{ request('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-calendar-check mr-1 text-success"></i>Cuộc hẹn
                                    </label>
                                    <select name="appointment_id" class="form-control custom-select">
                                        <option value="">Tất cả cuộc hẹn</option>
                                        @foreach ($appointments as $appointment)
                                            <option value="{{ $appointment->id }}"
                                                {{ request('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                                {{ $appointment->patient->full_name }} -
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d/m/Y H:i') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('doctor.files.index') }}" class="btn btn-outline-secondary">
                                            <i class="bx bx-refresh-cw mr-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Enhanced Table -->
                    <div class="table-responsive">
                        @if ($files->count() > 0)
                            <table class="table table-hover table-modern mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-top-0">
                                            <i class="bx bx-hash mr-1"></i>STT
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-file mr-1"></i>Thông tin file
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-user mr-1"></i>Bệnh nhân
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-category mr-1"></i>Danh mục
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-time mr-1"></i>Ngày tải lên
                                        </th>
                                        <th class="border-top-0">
                                            <i class="bx bx-calendar mr-1"></i>Cuộc hẹn
                                        </th>
                                        <th class="border-top-0 text-center">
                                            <i class="bx bx-cog mr-1"></i>Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($files as $index => $file)
                                        <tr class="file-row">
                                            <td class="font-weight-bold text-primary">
                                                {{ $files->firstItem() + $index }}
                                            </td>
                                            <td>
                                                <div class="file-info d-flex align-items-center">
                                                    <div class="file-icon-modern bg-gradient-primary text-white mr-3">
                                                        <i class="bx bx-file"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 font-weight-semibold"
                                                            title="{{ $file->file_name }}">
                                                            {{ Str::limit($file->file_name, 30) }}
                                                        </h6>
                                                        <small class="text-muted">
                                                            <i class="bx bx-weight mr-1"></i>
                                                            {{ number_format(($file->file_size ?? 0) / 1024, 1) }} KB
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="user-info d-flex align-items-center">
                                                    <div class="user-avatar-modern bg-gradient-info text-white mr-2">
                                                        {{ substr($file->appointment->patient->full_name ?? 'N', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-semibold">
                                                            {{ $file->appointment->patient->full_name ?? 'N/A' }}
                                                        </div>
                                                        <small class="text-muted">
                                                            ID: {{ $file->appointment->patient->id ?? 'N/A' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-outline-primary category-badge"
                                                    data-file-id="{{ $file->id }}"
                                                    data-category="{{ $file->file_category }}" style="cursor: pointer;"
                                                    title="Click để chỉnh sửa">
                                                    {{ $file->file_category }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="time-info">
                                                    <div class="d-flex align-items-center text-success">
                                                        <i class="bx bx-time mr-1"></i>
                                                        <div>
                                                            <small class="font-weight-semibold">
                                                                {{ \Carbon\Carbon::parse($file->uploaded_at)->format('d/m/Y') }}
                                                            </small><br>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($file->uploaded_at)->format('H:i') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="appointment-info">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-calendar-check mr-1 text-info"></i>
                                                        <div>
                                                            <small class="font-weight-semibold">
                                                                {{ \Carbon\Carbon::parse($file->appointment->appointment_time)->format('d/m/Y') }}
                                                            </small><br>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($file->appointment->appointment_time)->format('H:i') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('doctor.files.show', $file->id) }}" target="_blank"
                                                        class="btn btn-outline-info" data-toggle="tooltip"
                                                        title="Xem chi tiết">
                                                        <i class="bx bx-show-alt"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.files.download', $file->id) }}" download
                                                        class="btn btn-outline-success" data-toggle="tooltip"
                                                        title="Tải xuống">
                                                        <i class="bx bx-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger delete-btn"
                                                        data-file-id="{{ $file->id }}"
                                                        data-file-name="{{ $file->file_name }}" data-toggle="tooltip"
                                                        title="Xóa">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Enhanced Pagination -->
                            @if ($files->hasPages())
                                <div class="pagination-wrapper bg-light p-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="pagination-info">
                                            <small class="text-muted">
                                                Hiển thị {{ $files->firstItem() }} đến {{ $files->lastItem() }}
                                                trong tổng số {{ $files->total() }} file
                                            </small>
                                        </div>
                                        <div class="pagination-links">
                                            {{ $files->appends(request()->query())->links() }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bx bx-file-medical text-muted" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 text-muted">Chưa có file nào được tải lên</h5>
                                    <p class="text-muted mb-4">Hãy tải lên file đầu tiên của bạn</p>
                                    <a href="{{ route('doctor.files.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus mr-2"></i> Tải lên File
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
                        Xác nhận xóa file
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa file <strong id="fileName"></strong>?</p>
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

    <!-- Modal chỉnh sửa category -->
    {{-- <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="bx bx-edit text-info mr-2"></i>
                        Chỉnh sửa danh mục
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="categoryForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="newCategory" class="font-weight-semibold">Danh mục mới</label>
                            <input type="text" id="newCategory" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check mr-2"></i>Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

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
            // Xử lý xóa file
            $('.delete-btn').click(function() {
                const fileId = $(this).data('file-id');
                const fileName = $(this).data('file-name');

                Swal.fire({
                    title: 'Xác nhận xóa',
                    html: `Bạn có chắc chắn muốn xóa file <strong>${fileName}</strong> không?<br>`,
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
                        form.action = `/doctor/files/${fileId}`;

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

            // Xử lý chỉnh sửa category
            let currentFileId = null;
            $('.category-badge').click(function() {
                currentFileId = $(this).data('file-id');
                const currentCategory = $(this).data('category');

                $('#newCategory').val(currentCategory);
                $('#categoryModal').modal('show');
            });

            // Submit form category
            $('#categoryForm').submit(function(e) {
                e.preventDefault();

                const newCategory = $('#newCategory').val();

                $.ajax({
                    url: '/doctor/files/' + currentFileId + '/category',
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        file_category: newCategory
                    },
                    success: function(response) {
                        if (response.success) {
                            // Cập nhật badge
                            $(`[data-file-id="${currentFileId}"]`).text(newCategory).data(
                                'category', newCategory);
                            $('#categoryModal').modal('hide');

                            // Hiển thị thông báo success
                            Swal.fire({
                                title: 'Thành công!',
                                text: response.message ||
                                    'Cập nhật danh mục thành công!',
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Có lỗi xảy ra khi cập nhật danh mục!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            title: 'Lỗi!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'Đóng'
                        });
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
            $('.file-row').hover(
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
        });

        // Additional utility functions
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function truncateText(text, maxLength) {
            if (text.length <= maxLength) return text;
            return text.substr(0, maxLength) + '...';
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
    </script>
@endpush
