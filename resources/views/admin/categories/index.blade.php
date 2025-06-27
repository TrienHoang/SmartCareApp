@extends('admin.dashboard')

@section('title', 'Quản lý Danh mục')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-category text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Danh mục</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả danh mục trong hệ thống</p>
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
                                        Danh mục
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right">
                    <a href="{{ route('admin.categories.create') }}"
                        class="btn btn-gradient-primary btn-lg waves-effect waves-light shadow-lg text-white">
                        Tạo danh mục mới
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
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-check font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $categories->where('status', 'active')->count() }}</h4>
                                    <small class="text-white">Hoạt động</small>
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
                                        <i class="bx bx-pause font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $categories->where('status', 'inactive')->count() }}</h4>
                                    <small class="text-white">Không hoạt động</small>
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
                                        <i class="bx bx-category font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $categories->count() }}</h4>
                                    <small class="text-white">Tổng số</small>
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
                                        <i class="bx bx-time font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">
                                        {{ $categories->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Danh mục</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $categories->total() }} danh mục</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('admin.categories.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tìm kiếm
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control border-left-0"
                                            placeholder="Tên danh mục..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-activity mr-1 text-success"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                            Hoạt động
                                        </option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                            Không hoạt động
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-sort mr-1 text-info"></i>Sắp xếp
                                    </label>
                                    <select name="sort" class="form-control custom-select">
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                            Tên A-Z
                                        </option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                            Tên Z-A
                                        </option>
                                        <option value="created_desc"
                                            {{ request('sort') == 'created_desc' ? 'selected' : '' }}>
                                            Mới nhất
                                        </option>
                                        <option value="created_asc"
                                            {{ request('sort') == 'created_asc' ? 'selected' : '' }}>
                                            Cũ nhất
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-50" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.categories.index') }}"
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
                                        <i class="mr-1"></i>Tên danh mục
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Mô tả
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Slug
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Trạng thái
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Ngày tạo
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr class="category-row" data-id="{{ $category->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input category-checkbox"
                                                    id="category-{{ $category->id }}" value="{{ $category->id }}">
                                                <label class="custom-control-label"
                                                    for="category-{{ $category->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-primary">#{{ $category->id }}</td>
                                        <td>
                                            <div class="category-title">
                                                <h6 class="mb-0 font-weight-semibold">
                                                    {{ $category->name }}
                                                </h6>
                                                @if ($category->parent)
                                                    <small class="text-muted">
                                                        <i class="bx bx-subdirectory-right mr-1"></i>
                                                        Con của: {{ $category->parent->name }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ Str::limit($category->description ?? 'Không có mô tả', 50) }}
                                            </small>
                                        </td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $category->slug }}</code>
                                        </td>
                                        <td>
                                            @if ($category->status == 'active')
                                                <span class="badge badge-success badge-pill">
                                                    <i class="bx bx-check mr-1"></i>
                                                    Hoạt động
                                                </span>
                                            @else
                                                <span class="badge badge-secondary badge-pill">
                                                    <i class="bx bx-pause mr-1"></i>
                                                    Không hoạt động
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="time-info">
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="bx bx-calendar mr-1"></i>
                                                    <div>
                                                        <small
                                                            class="font-weight-semibold">{{ $category->created_at->format('d/m/Y') }}</small><br>
                                                        <small
                                                            class="text-muted">{{ $category->created_at->format('H:i') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <!-- Xem chi tiết -->
                                                <a href="{{ route('admin.categories.show', $category) }}"
                                                    class="btn btn-outline-info" data-toggle="tooltip"
                                                    title="Xem chi tiết">
                                                    <i class='bx bx-show-alt'></i>
                                                </a>

                                                <!-- Chỉnh sửa -->
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                    class="btn btn-outline-warning" data-toggle="tooltip"
                                                    title="Chỉnh sửa">
                                                    <i class="bx bx-edit"></i>
                                                </a>

                                                <!-- Toggle trạng thái -->
                                                <form
                                                    action="{{ route('admin.categories.toggle-status', $category->id) }}"
                                                    method="POST" class="d-inline-block"
                                                    onsubmit="return confirm('Bạn có chắc muốn thay đổi trạng thái danh mục này?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-outline-{{ $category->status == 'active' ? 'secondary' : 'success' }}"
                                                        data-toggle="tooltip"
                                                        title="{{ $category->status == 'active' ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                                        <i
                                                            class="bx bx-{{ $category->status == 'active' ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>

                                                <!-- Xóa -->
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-toggle="tooltip" title="Xóa"
                                                    onclick="deleteCategory({{ $category->id }})">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-category text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có danh mục nào</h5>
                                                <p class="text-muted">Chưa có danh mục nào được tạo hoặc không tìm thấy
                                                    kết quả phù hợp.</p>
                                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                                    <i class="bx bx-plus mr-1"></i>Tạo danh mục đầu tiên
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if ($categories->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $categories->firstItem() }} - {{ $categories->lastItem() }}
                                        trong tổng số {{ $categories->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $categories->links('pagination::bootstrap-4') }}
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

        .category-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .category-title h6 {
            color: #2c3e50;
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

        code {
            font-size: 0.85em;
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
            const checkboxes = document.querySelectorAll('.category-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Toggle status function
        function toggleStatus(id, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const statusText = newStatus === 'active' ? 'kích hoạt' : 'vô hiệu hóa';

            Swal.fire({
                title: `Xác nhận ${statusText}`,
                text: `Bạn có chắc chắn muốn ${statusText} danh mục này?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: newStatus === 'active' ? '#28a745' : '#6c757d',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `${statusText.charAt(0).toUpperCase() + statusText.slice(1)}`,
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/categories/${id}/toggle-status`;

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
            });
        }

        // Delete category function
        function deleteCategory(id) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa danh mục này? Hành động này không thể hoàn tác!',
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
                    form.action = `/admin/categories/${id}`;

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
        });
    </script>
@endpush
