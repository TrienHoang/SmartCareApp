@extends('admin.dashboard')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-user-circle text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Người dùng</h2>
                                <p class="text-muted mb-0">Quản lý và theo dõi thông tin người dùng trong hệ thống</p>
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
                                        Người dùng
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
            @foreach (['success', 'error'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-{{ $msg == 'success' ? 'check-circle' : 'x-circle' }} mr-2"></i>
                            <strong>{{ $msg == 'success' ? 'Thành công!' : 'Lỗi!' }} </strong> {{ session($msg) }}
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card gradient-card bg-gradient-success">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-user font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $users->total() }}</h4>
                                    <small class="text-white">Tổng số</small>
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
                                        <i class="bx bx-user-check font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $users->where('status', 'online')->count() }}</h4>
                                    <small class="text-white">Đang hoạt động</small>
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
                                        <i class="bx bx-user-x font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $users->where('status', 'offline')->count() }}</h4>
                                    <small class="text-white">Không hoạt động</small>
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
                                        <i class="bx bx-user-minus font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">0</h4>
                                    <small class="text-white">Bị khóa</small>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Người dùng</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $users->total() }} người dùng</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section bg-light p-4 border-bottom">
                        <form action="{{ route('admin.users.index') }}" method="GET" class="filter-form">
                            <div class="row align-items-end">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-search mr-1 text-primary"></i>Tìm kiếm
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control border-left-0"
                                            placeholder="Tên, username..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-shield mr-1 text-info"></i>Vai trò
                                    </label>
                                    <select name="role_id" class="form-control custom-select">
                                        <option value="all">Tất cả vai trò</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label font-weight-semibold">
                                        <i class="bx bx-activity mr-1 text-success"></i>Trạng thái
                                    </label>
                                    <select name="status" class="form-control custom-select">
                                        <option value="all">Tất cả trạng thái</option>
                                        <option value="online" {{ request('status') == 'online' ? 'selected' : '' }}>Đang hoạt động</option>
                                        <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="btn-group w-50" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter mr-1"></i>Lọc
                                        </button>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
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
                                        <i class="mr-1"></i>Avatar
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Thông tin
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Giới tính
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Liên hệ
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Vai trò
                                    </th>
                                    <th class="border-top-0">
                                        <i class="mr-1"></i>Trạng thái
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="user-row" data-id="{{ $user->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input user-checkbox"
                                                    id="user-{{ $user->id }}" value="{{ $user->id }}">
                                                <label class="custom-control-label" for="user-{{ $user->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-primary">
                                            #{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                        </td>
                                        <td class="text-center">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                                     class="rounded-circle shadow" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-light d-flex justify-content-center align-items-center mx-auto"
                                                     style="width: 50px; height: 50px;">
                                                    <i class="bx bx-user text-muted fs-4"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <h6 class="mb-0 font-weight-semibold text-primary">{{ $user->full_name }}</h6>
                                                <small class="text-muted">@{{ $user->username }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ strtolower($user->gender) == 'nam' ? 'primary' : 'warning' }} badge-pill">
                                                <i class="bx bx-{{ strtolower($user->gender) == 'nam' ? 'male' : 'female' }} mr-1"></i>
                                                {{ ucfirst($user->gender) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="bx bx-phone mr-1"></i>
                                                    <small>{{ $user->phone ?: 'Chưa có' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-success badge-pill">
                                                <i class="bx bx-shield mr-1"></i>
                                                {{ $user->role->name ?? 'Chưa có' }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusConfig = [
                                                    'online' => ['class' => 'success', 'icon' => 'check-circle'],
                                                    'offline' => ['class' => 'secondary', 'icon' => 'x-circle'],
                                                ];
                                                $config = $statusConfig[$user->status] ?? $statusConfig['offline'];
                                            @endphp
                                            <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-{{ $config['class'] }} badge-pill">
                                                    <i class="bx bx-{{ $config['icon'] }} mr-1"></i>
                                                    {{ ucfirst($user->status) }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.users.show', $user->id) }}"
                                                   class="btn btn-outline-info" data-toggle="tooltip" title="Chi tiết">
                                                    <i class="bx bx-show-alt"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                   class="btn btn-outline-warning" data-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                        data-toggle="tooltip" title="Xóa" onclick="deleteUser({{ $user->id }})">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-user-x text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có người dùng nào</h5>
                                                <p class="text-muted">Chưa có người dùng nào được tạo hoặc không tìm thấy kết quả phù hợp.</p>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                                    <i class="bx bx-plus mr-1"></i>Tạo người dùng đầu tiên
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if($users->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $users->firstItem() }} - {{ $users->lastItem() }}
                                        trong tổng số {{ $users->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Create User Modal --}}
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white border-0">
                    <h5 class="modal-title" id="createUserModalLabel">
                        <i class="bx bx-user-plus me-2"></i>Tạo người dùng mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label font-weight-semibold">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label font-weight-semibold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label font-weight-semibold">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label font-weight-semibold">Giới tính</label>
                                <select class="form-control custom-select" id="gender" name="gender">
                                    <option value="nam">Nam</option>
                                    <option value="nữ">Nữ</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role_id" class="form-label font-weight-semibold">Vai trò <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="role_id" name="role_id" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="avatar" class="form-label font-weight-semibold">Avatar</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="password" class="form-label font-weight-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x mr-1"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="bx bx-check mr-1"></i>Tạo người dùng
                        </button>
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

        .badge-primary {
            background-color: #667eea;
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

        .user-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .user-info h6 {
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
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Delete user function
        function deleteUser(id) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/users/${id}`;

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

        // Initialize tooltips and other functionality
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Preview avatar in modal
            $('#avatar').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // You can add preview functionality here
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
