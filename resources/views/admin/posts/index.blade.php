@extends('admin.dashboard')

@section('title', 'Quản lý Bài viết')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-edit text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Bài viết</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả bài viết trong hệ thống</p>
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
                                        Bài viết
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

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-check-circle font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $posts->where('status', 'published')->count() }}</h4>
                                    <small class="text-white">Đã xuất bản</small>
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
                                        <i class="bx bx-edit font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $posts->where('status', 'draft')->count() }}</h4>
                                    <small class="text-white">Bản nháp</small>
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
                                        <i class="bx bx-archive font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $posts->where('status', 'archived')->count() }}</h4>
                                    <small class="text-white">Lưu trữ</small>
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
                                        <i class="bx bx-list-ul font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $posts->total() }}</h4>
                                    <small class="text-white">Tổng bài viết</small>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Bài viết</h4>
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('admin.posts.create') }}" class="btn btn-light btn-sm">
                                <i class="bx bx-plus mr-1"></i>Thêm bài viết
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form method="GET" action="{{ route('admin.posts.index') }}" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tìm kiếm
                                    </label>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tìm theo tiêu đề..." value="{{ request('search') }}">
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-category mr-1 text-info"></i>Danh mục
                                    </label>
                                    <select name="category" class="form-control">
                                        <option value="">-- Tất cả danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-activity mr-1 text-success"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="">-- Tất cả trạng thái --</option>
                                        <option value="published"
                                            {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản
                                            nháp</option>
                                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>
                                            Lưu trữ</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
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
                                        <i class="bx bx-edit mr-1"></i>Tiêu đề
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-category mr-1"></i>Danh mục
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-activity mr-1"></i>Trạng thái
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-calendar mr-1"></i>Ngày tạo
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="bx bx-show mr-1"></i>Lượt xem
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="bx bx-cog mr-1"></i>Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                    <tr class="post-row" data-id="{{ $post->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input post-checkbox"
                                                    id="post-{{ $post->id }}" value="{{ $post->id }}">
                                                <label class="custom-control-label"
                                                    for="post-{{ $post->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="post-info">
                                                <h6 class="mb-0 font-weight-semibold">{{ $post->title }}</h6>
                                                {{-- Bỏ phần mô tả ngắn --}}
                                                {{-- @if ($post->excerpt)
            <small class="text-muted">{{ Str::limit($post->excerpt, 50) }}</small>
        @endif --}}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge badge-info badge-pill">
                                                {{ $post->serviceCategory->name ?? '(Không có)' }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($post->status)
                                                @case('published')
                                                    <span class="badge badge-success badge-pill">
                                                        <i class="bx bx-check-circle mr-1"></i>Đã xuất bản
                                                    </span>
                                                @break

                                                @case('draft')
                                                    <span class="badge badge-warning badge-pill">
                                                        <i class="bx bx-edit mr-1"></i>Bản nháp
                                                    </span>
                                                @break

                                                @case('archived')
                                                    <span class="badge badge-secondary badge-pill">
                                                        <i class="bx bx-archive mr-1"></i>Lưu trữ
                                                    </span>
                                                @break

                                                @default
                                                    <span class="badge badge-secondary badge-pill">
                                                        <i class="bx bx-question-mark mr-1"></i>Không xác định
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-calendar mr-1 text-muted"></i>
                                                <span
                                                    class="font-weight-semibold">{{ $post->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="view-count-wrapper">
                                                <span class="view-count-badge">
                                                    <i class="bx bx-show text-primary mr-1"></i>
                                                    <span
                                                        class="view-number">{{ number_format($post->view_count) }}</span>
                                                </span>
                                                @if ($post->view_count > 1000)
                                                    <small class="text-success d-block mt-1">
                                                        <i class="bx bx-trending-up"></i> Phổ biến
                                                    </small>
                                                @elseif($post->view_count > 100)
                                                    <small class="text-info d-block mt-1">
                                                        <i class="bx bx-bar-chart-alt-2"></i> Tốt
                                                    </small>
                                                @elseif($post->view_count > 0)
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="bx bx-trending-up"></i> Mới
                                                    </small>
                                                @else
                                                    <small class="text-secondary d-block mt-1">
                                                        <i class="bx bx-minus"></i> Chưa xem
                                                    </small>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div class="action-buttons d-flex justify-content-center align-items-center">
                                                <a href="{{ route('admin.posts.show', $post->id) }}"
                                                    class="btn btn-outline-info btn-sm action-btn" data-toggle="tooltip"
                                                    title="Xem chi tiết">
                                                    <i class="bx bx-show-alt"></i>
                                                </a>
                                                <a href="{{ route('admin.posts.edit', $post->id) }}"
                                                    class="btn btn-outline-warning btn-sm action-btn"
                                                    data-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm action-btn"
                                                    data-toggle="tooltip" title="Xóa"
                                                    onclick="deletePost({{ $post->id }})">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Hidden form for delete -->
                                            <form id="delete-form-{{ $post->id }}"
                                                action="{{ route('admin.posts.destroy', $post->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bx bx-edit text-muted" style="font-size: 48px;"></i>
                                                    <h5 class="mt-3 text-muted">Không có bài viết nào</h5>
                                                    <p class="text-muted">Chưa có bài viết nào được tạo hoặc không tìm thấy kết
                                                        quả phù hợp.</p>
                                                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                                                        <i class="bx bx-plus mr-1"></i>Tạo bài viết đầu tiên
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Enhanced Pagination -->
                        @if ($posts->hasPages())
                            <div class="pagination-wrapper bg-light p-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="pagination-info">
                                        <small class="text-muted">
                                            Hiển thị {{ $posts->firstItem() }} - {{ $posts->lastItem() }}
                                            trong tổng số {{ $posts->total() }} kết quả
                                        </small>
                                    </div>
                                    <div class="pagination-links">
                                        {{ $posts->links('pagination::bootstrap-4') }}
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

            .post-row:hover {
                background-color: rgba(102, 126, 234, 0.05);
            }

            .post-info h6 {
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

            /* Action Buttons Styling */
            .action-buttons {
                gap: 8px;
            }

            .action-btn {
                border-radius: 6px;
                padding: 0.375rem 0.75rem;
                transition: all 0.3s ease;
                border: 1.5px solid;
                min-width: 38px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .action-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .action-btn.btn-outline-info:hover {
                background-color: #17a2b8;
                border-color: #17a2b8;
                color: white;
            }

            .action-btn.btn-outline-warning:hover {
                background-color: #ffc107;
                border-color: #ffc107;
                color: #212529;
            }

            .action-btn.btn-outline-danger:hover {
                background-color: #dc3545;
                border-color: #dc3545;
                color: white;
            }

            .action-btn i {
                font-size: 1rem;
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

            /* Enhanced View Count Styles */
            .view-count-wrapper {
                padding: 0.5rem;
                border-radius: 8px;
                background: linear-gradient(135deg, #f8f9ff 0%, #e6f3ff 100%);
                border: 1px solid rgba(102, 126, 234, 0.1);
                transition: all 0.3s ease;
                min-width: 80px;
            }

            .view-count-wrapper:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
                background: linear-gradient(135deg, #e6f3ff 0%, #cce7ff 100%);
            }

            .view-count-badge {
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                color: #2c3e50;
                font-size: 0.9rem;
            }

            .view-number {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-weight: 700;
            }

            .view-count-wrapper small {
                font-size: 0.75rem;
                font-weight: 500;
            }

            .view-count-wrapper .bx-trending-up,
            .view-count-wrapper .bx-bar-chart-alt-2 {
                font-size: 0.8rem;
            }

            /* Responsive adjustments for view count and action buttons */
            @media (max-width: 768px) {
                .view-count-wrapper {
                    min-width: 60px;
                    padding: 0.3rem;
                }

                .view-count-badge {
                    font-size: 0.8rem;
                }

                .view-count-wrapper small {
                    font-size: 0.7rem;
                }

                .filter-form .row>div {
                    margin-bottom: 1rem;
                }

                .action-buttons {
                    flex-direction: column;
                    gap: 4px;
                }

                .action-btn {
                    margin-bottom: 4px;
                    width: 100%;
                    min-width: auto;
                }

                .table-modern {
                    font-size: 0.8rem;
                }

                .table-modern td {
                    padding: 0.5rem 0.25rem;
                }
            }

            /* Animation for view count hover */
            .view-count-wrapper .bx-show {
                transition: transform 0.2s ease;
            }

            .view-count-wrapper:hover .bx-show {
                transform: scale(1.1);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Select all checkboxes functionality
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.post-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Delete post function
            function deletePost(id) {
                Swal.fire({
                    title: 'Xóa bài viết',
                    text: 'Bạn chắc chắn muốn xóa bài viết này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
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
