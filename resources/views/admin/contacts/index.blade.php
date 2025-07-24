@extends('admin.dashboard')

@section('title', 'Quản lý Liên hệ')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-message-detail text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Liên hệ</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi tất cả liên hệ từ khách hàng</p>
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
                                        Liên hệ
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
                    <div class="card gradient-card bg-gradient-info">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-message-square-detail font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $contacts->total() }}</h4>
                                    <small class="text-white">Tổng liên hệ</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-envelope font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $contacts->where('email', '!=', null)->count() }}</h4>
                                    <small class="text-white">Có email</small>
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
                                        <i class="bx bx-phone font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $contacts->where('phone', '!=', null)->count() }}</h4>
                                    <small class="text-white">Có số điện thoại</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-primary">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-calendar font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $contacts->where('created_at', '>=', now()->startOfMonth())->count() }}</h4>
                                    <small class="text-white">Tháng này</small>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Liên hệ</h4>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form method="GET" action="{{ route('admin.contacts.index') }}" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-6 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tìm kiếm
                                    </label>
                                    <input type="text" name="keyword" class="form-control" 
                                        placeholder="Tìm theo tên/tiêu đề..." value="{{ request('keyword') }}">
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-activity mr-1 text-success"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="">-- Tất cả trạng thái --</option>
                                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Đã đọc</option>
                                        <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Đã trả lời</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
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
                                        <i class="bx bx-hash mr-1"></i>STT
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-user mr-1"></i>Họ tên
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-envelope mr-1"></i>Email
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-phone mr-1"></i>Số điện thoại
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-message-detail mr-1"></i>Tiêu đề
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
                                @forelse($contacts as $contact)
                                    <tr class="contact-row" data-id="{{ $contact->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input contact-checkbox"
                                                    id="contact-{{ $contact->id }}" value="{{ $contact->id }}">
                                                <label class="custom-control-label" for="contact-{{ $contact->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light badge-pill">
                                                {{ $contacts->total() - ($contacts->currentPage() - 1) * $contacts->perPage() - $loop->index }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <h6 class="mb-0 font-weight-semibold">{{ $contact->name }}</h6>
                                                <small class="text-muted">
                                                    <i class="bx bx-time mr-1"></i>
                                                    {{ $contact->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-envelope mr-2 text-primary"></i>
                                                <span class="font-weight-semibold">{{ $contact->email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($contact->phone)
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-phone mr-2 text-success"></i>
                                                    <span class="font-weight-semibold">{{ $contact->phone }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="bx bx-x mr-1"></i>Không có
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($contact->title)
                                                <span class="badge badge-info badge-pill">
                                                    {{ Str::limit($contact->title, 30) }}
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    <i class="bx bx-x mr-1"></i>Không có
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.contacts.updateStatus', $contact->id) }}" method="POST" class="status-form">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="form-control form-control-sm custom-select">
                                                    <option value="unread" {{ $contact->status === 'unread' ? 'selected' : '' }}>
                                                        Chưa đọc
                                                    </option>
                                                    <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>
                                                        Đã đọc
                                                    </option>
                                                    <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>
                                                        Đã trả lời
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group-sm" role="group">
                                                <a href="{{ route('admin.contacts.show', $contact->id) }}"
                                                    class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="bx bx-show-alt"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    data-toggle="tooltip" title="Xóa" onclick="deleteContact({{ $contact->id }})">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Hidden form for delete -->
                                            <form id="delete-form-{{ $contact->id }}" 
                                                action="{{ route('admin.contacts.destroy', $contact->id) }}" 
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-message-detail text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có liên hệ nào</h5>
                                                <p class="text-muted">Chưa có liên hệ nào từ khách hàng hoặc không tìm thấy kết quả phù hợp.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if ($contacts->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $contacts->firstItem() }} - {{ $contacts->lastItem() }}
                                        trong tổng số {{ $contacts->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $contacts->links('pagination::bootstrap-4') }}
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

        .badge-light {
            background-color: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
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

        .contact-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .contact-info h6 {
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

        .status-form .custom-select {
            min-width: 120px;
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

            .status-form .custom-select {
                min-width: 100px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Select all checkboxes functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.contact-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Delete contact function with SweetAlert
        function deleteContact(id) {
            Swal.fire({
                title: 'Xóa liên hệ',
                text: 'Bạn chắc chắn muốn xóa liên hệ này?',
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