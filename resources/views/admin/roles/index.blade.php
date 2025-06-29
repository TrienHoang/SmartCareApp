@extends('admin.dashboard')

@section('title', 'Quản lý Vai trò')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="bx bx-shield text-white"></i>
                            </div>
                            <div>
                                <h2 class="content-header-title mb-0 text-primary font-weight-bold">Quản lý Vai trò</h2>
                                <p class="text-muted mb-0">Quản lý và phân quyền cho các vai trò trong hệ thống</p>
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
                                        Vai trò
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <div class="form-group breadcrum-right">
                    <a href="{{ route('admin.roles.create') }}"
                        class="btn btn-gradient-primary btn-lg waves-effect waves-light shadow-lg text-white">
                        <i class="bx bx-plus mr-1"></i>Thêm vai trò mới
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
                    <div class="card gradient-card bg-gradient-primary">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-rgba-white mr-2">
                                    <div class="avatar-content">
                                        <i class="bx bx-shield font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $roles->count() }}</h4>
                                    <small class="text-white">Tổng vai trò</small>
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
                                        <i class="bx bx-user-check font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">{{ $roles->where('name', '!=', 'admin')->count() }}</h4>
                                    <small class="text-white">Vai trò tùy chỉnh</small>
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
                                        <i class="bx bx-key font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">
                                        {{ $roles->getCollection()->flatMap->permissions->unique('id')->count() }}
                                    </h4>
                                    <small class="text-white">Tổng quyền</small>
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
                                        <i class="bx bx-lock font-medium-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-0">1</h4>
                                    <small class="text-white">Vai trò mặc định</small>
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
                            <h4 class="card-title mb-0 text-white font-weight-bold">Danh sách Vai trò</h4>
                        </div>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $roles->count() }} vai trò</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
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
                                        <i class="bx bx-shield mr-1"></i>Tên vai trò
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-key mr-1"></i>Quyền hạn
                                    </th>
                                    <th class="border-top-0">
                                        <i class="bx bx-time mr-1"></i>Trạng thái
                                    </th>
                                    <th class="border-top-0 text-center">
                                        <i class="bx bx-cog mr-1"></i>Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr class="role-row" data-id="{{ $role->id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input role-checkbox"
                                                    id="role-{{ $role->id }}"
                                                    value="{{ $role->id }}"
                                                    {{ $role->name === 'admin' ? 'disabled' : '' }}>
                                                <label class="custom-control-label"
                                                    for="role-{{ $role->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-primary">#{{ $role->id }}</td>
                                        <td>
                                            <div class="role-info">
                                                <div class="d-flex align-items-center">
                                                    @if ($role->name === 'admin')
                                                        <div class="avatar bg-gradient-danger mr-2">
                                                            <i class="bx bx-crown text-white"></i>
                                                        </div>
                                                    @else
                                                        <div class="avatar bg-gradient-primary mr-2">
                                                            <i class="bx bx-user text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 font-weight-semibold">{{ $role->name }}</h6>
                                                        <small class="text-muted">
                                                            {{ $role->name === 'admin' ? 'Vai trò hệ thống' : 'Vai trò tùy chỉnh' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="permissions-display">
                                                @php
                                                    $permissions = $role->permissions->pluck('name')->toArray();
                                                    $displayPermissions = array_slice($permissions, 0, 2);
                                                    $remaining = count($permissions) - count($displayPermissions);
                                                @endphp

                                                @foreach ($displayPermissions as $perm)
                                                    <span class="badge badge-outline-info mr-1 mb-1">
                                                        <i class="bx bx-check-circle mr-1"></i>
                                                        {{ getPermissionLabel($perm) }}
                                                    </span>
                                                @endforeach

                                                @if ($remaining > 0)
                                                    <a href="{{ route('admin.roles.show', $role->id) }}"
                                                        class="badge badge-secondary text-decoration-none">
                                                        +{{ $remaining }} quyền khác
                                                    </a>
                                                @endif

                                                @if (count($permissions) === 0)
                                                    <span class="text-muted">Chưa có quyền</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if ($role->name === 'admin')
                                                <span class="badge badge-danger badge-pill">
                                                    <i class="bx bx-lock mr-1"></i>
                                                    Mặc định
                                                </span>
                                            @else
                                                <span class="badge badge-success badge-pill">
                                                    <i class="bx bx-check mr-1"></i>
                                                    Hoạt động
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.roles.show', $role->id) }}"
                                                    class="btn btn-outline-info" data-toggle="tooltip"
                                                    title="Xem chi tiết">
                                                    <i class="bx bx-show-alt"></i>
                                                </a>

                                                @if ($role->name !== 'admin')
                                                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                                                        class="btn btn-outline-warning" data-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        data-toggle="tooltip" title="Xóa"
                                                        onclick="deleteRole({{ $role->id }})">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                @else
                                                    <span class="btn btn-outline-secondary disabled" data-toggle="tooltip"
                                                        title="Không thể chỉnh sửa vai trò mặc định">
                                                        <i class="bx bx-lock"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-shield-off text-muted" style="font-size: 48px;"></i>
                                                <h5 class="mt-3 text-muted">Không có vai trò nào</h5>
                                                <p class="text-muted">Chưa có vai trò nào được tạo trong hệ thống.</p>
                                                <a href="{{ route('admin.roles.create') }}"
                                                    class="btn btn-primary">
                                                    <i class="bx bx-plus mr-1"></i>Tạo vai trò đầu tiên
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if ($roles->hasPages())
                        <div class="pagination-wrapper bg-light p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <small class="text-muted">
                                        Hiển thị {{ $roles->firstItem() }} - {{ $roles->lastItem() }}
                                        trong tổng số {{ $roles->total() }} kết quả
                                    </small>
                                </div>
                                <div class="pagination-links">
                                    {{ $roles->links('pagination::bootstrap-4') }}
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

        .role-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .role-info h6 {
            color: #2c3e50;
        }

        .badge-outline-info {
            color: #00CFDD;
            border: 1px solid #00CFDD;
            background: transparent;
        }

        .permissions-display {
            max-width: 300px;
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
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-rgba-white {
            background-color: rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 768px) {
            .btn-group-sm {
                flex-direction: column;
            }

            .btn-group-sm .btn {
                margin-bottom: 2px;
                margin-right: 0;
            }

            .permissions-display {
                max-width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Select all checkboxes functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.role-checkbox:not(:disabled)');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Delete role function
        function deleteRole(id) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa vai trò này? Hành động này không thể hoàn tác!',
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
                    form.action = `/admin/roles/${id}`;

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