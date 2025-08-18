@extends('admin.dashboard')

@section('title', 'Thùng rác Dịch vụ')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-recycle text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Thùng rác Dịch vụ</h2>
                                <p class="text-muted mb-0">Xem và khôi phục các dịch vụ đã xóa</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            Trang chủ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.services.index') }}" class="text-decoration-none">
                                            Dịch vụ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                                        Thùng rác
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
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

            <!-- Main Content Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-recycle mr-2"></i>
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Dịch vụ đã xóa</h4>
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('admin.services.index') }}" class="btn btn-light btn-sm">
                                <i class="bx bx-arrow-back mr-1"></i>Quay lại Danh sách Dịch vụ
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form method="GET" action="{{ route('admin.services.trash') }}" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tìm kiếm
                                    </label>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tìm theo tên, mô tả..." value="{{ request('search') }}">
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-category mr-1 text-info"></i>Danh mục
                                    </label>
                                    <select name="category" class="form-control custom-select">
                                        <option value="">-- Tất cả danh mục --</option>
                                        @foreach ($categories as $cate)
                                            <option value="{{ $cate->id }}"
                                                {{ request('category') == $cate->id ? 'selected' : '' }}>
                                                {{ $cate->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.services.trash') }}" class="btn btn-outline-secondary">
                                            <i class="bx bx-refresh mr-1"></i>Reset
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
                                    <th class="border-top-0">
                                        <i class="bx bx-service mr-1"></i>Tên dịch vụ
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-category mr-1"></i>Chuyên khoa
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-category mr-1"></i>Danh mục
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-money mr-1"></i>Giá
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-time mr-1"></i>Thời gian
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-calendar-x mr-1"></i>Ngày xóa
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="bx bx-cog mr-1"></i>Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                    <tr class="service-row" data-id="{{ $service->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input service-checkbox"
                                                    id="service-{{ $service->id }}" value="{{ $service->id }}">
                                                <label class="custom-control-label"
                                                    for="service-{{ $service->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="service-info">
                                                <h6 class="mb-0 font-weight-semibold">{{ $service->name }}</h6>
                                                @if ($service->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info badge-pill">
                                                {{ $service->department->name ?? '(Không có)' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info badge-pill">
                                                {{ $service->category->name ?? '(Không có)' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="price-info">
                                                <span class="font-weight-bold text-success">
                                                    {{ number_format($service->price, 0, ',', '.') }}₫
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-timer mr-1 text-warning"></i>
                                                <span class="font-weight-semibold">{{ $service->duration }} phút</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="font-weight-semibold">
                                                {{ $service->deleted_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    data-toggle="tooltip" title="Khôi phục"
                                                    onclick="restoreService({{ $service->id }})">
                                                    <i class="bx bx-recycle"></i>
                                                </button>
                                            </div>

                                            <!-- Hidden form for restore -->
                                            <form id="restore-form-{{ $service->id }}"
                                                action="{{ route('admin.services.restore', $service->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-recycle text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Thùng rác trống</h5>
                                                <p class="text-muted">Chưa có dịch vụ nào bị xóa hoặc không tìm thấy kết quả phù hợp.</p>
                                                <a href="{{ route('admin.services.index') }}" class="btn btn-primary">
                                                    <i class="bx bx-arrow-back mr-1"></i>Quay lại Danh sách Dịch vụ
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if ($services->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $services->firstItem() }} - {{ $services->lastItem() }}
                                        trong tổng số {{ $services->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $services->links('pagination::bootstrap-5') }}
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

        .service-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .service-info h6 {
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
            const checkboxes = document.querySelectorAll('.service-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Restore service function
        function restoreService(id) {
            Swal.fire({
                title: 'Khôi phục dịch vụ',
                text: 'Bạn chắc chắn muốn khôi phục dịch vụ này?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Khôi phục',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restore-form-' + id).submit();
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
        });
    </script>
@endpush