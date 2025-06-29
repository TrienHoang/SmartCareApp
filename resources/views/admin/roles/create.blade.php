@extends('admin.dashboard')
@section('title', 'Thêm vai trò')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb with improved styling -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Tạo vai trò mới</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}">Quản lý vai trò</a>
                        </li>
                        <li class="breadcrumb-item active">Thêm vai trò</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i>Quay lại
            </a>
        </div>

        <!-- Main Form Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary">
                <h5 class="card-title text-white mb-0">
                    <i class="bx bx-user-plus me-2"></i>Thông tin vai trò
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST" id="roleForm">
                    @csrf

                    <!-- Role Name Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bx bx-tag me-1"></i>Tên vai trò <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   placeholder="Ví dụ: Quản trị viên, Biên tập viên..."
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bx bx-error-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="bx bx-info-circle me-1"></i>Tên vai trò sẽ được sử dụng để phân quyền trong hệ thống
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label fw-semibold mb-0">
                                <i class="bx bx-shield-check me-1"></i>Phân quyền <span class="text-danger">*</span>
                            </label>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllPermissions(true)">
                                    <i class="bx bx-check-square me-1"></i>Chọn tất cả
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAllPermissions(false)">
                                    <i class="bx bx-square me-1"></i>Bỏ chọn tất cả
                                </button>
                            </div>
                        </div>

                        <!-- Search permissions -->
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bx bx-search"></i>
                                </span>
                                <input type="text" class="form-control" id="permissionSearch" 
                                       placeholder="Tìm kiếm quyền...">
                            </div>
                        </div>

                        <!-- Permissions Grid -->
                        <div class="permissions-container">
                            @if (!empty($permissions))
                                @foreach ($permissions as $group => $perms)
                                    <div class="permission-group card mb-3" data-group="{{ $group }}">
                                        <div class="card-header bg-light cursor-pointer" data-bs-toggle="collapse" 
                                             data-bs-target="#group_{{ $loop->index }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-primary fw-semibold">
                                                    <i class="bx bx-folder me-2"></i>
                                                    {{ __(getPermissionGroupLabel($group)) }}
                                                    <span class="badge bg-primary ms-2">{{ count($perms) }}</span>
                                                </h6>
                                                <div class="d-flex align-items-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-2" 
                                                            onclick="toggleGroupPermissions('{{ $group }}', true)">
                                                        <i class="bx bx-check-square"></i>
                                                    </button>
                                                    <i class="bx bx-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="collapse show" id="group_{{ $loop->index }}">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($perms as $permission)
                                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                            <div class="form-check form-check-modern">
                                                                <input class="form-check-input permission-checkbox" 
                                                                       type="checkbox"
                                                                       name="permissions[]"
                                                                       value="{{ $permission['id'] }}"
                                                                       id="perm_{{ $permission['id'] }}"
                                                                       data-group="{{ $group }}"
                                                                       data-name="{{ strtolower(getPermissionLabel($permission['name'])) }}">
                                                                <label class="form-check-label d-flex align-items-center" 
                                                                       for="perm_{{ $permission['id'] }}">
                                                                    <div class="permission-info">
                                                                        <div class="permission-name fw-medium">
                                                                            {{ getPermissionLabel($permission['name']) }}
                                                                        </div>
                                                                        <small class="text-muted">
                                                                            {{ $permission['name'] }}
                                                                        </small>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="bx bx-shield-x display-4 text-muted"></i>
                                    <p class="text-muted mt-2">Không có quyền nào được tìm thấy.</p>
                                </div>
                            @endif
                        </div>

                        @error('permissions')
                            <div class="alert alert-danger mt-3">
                                <i class="bx bx-error-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <!-- Selected permissions summary -->
                        <div class="mt-3">
                            <div class="alert alert-info d-none" id="selectedSummary">
                                <i class="bx bx-info-circle me-1"></i>
                                Đã chọn <span id="selectedCount">0</span> quyền
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Các trường có dấu <span class="text-danger">*</span> là bắt buộc
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                                        <i class="bx bx-x me-1"></i>Hủy bỏ
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bx bx-check me-1"></i>Tạo vai trò
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .form-check-modern {
                background: #f8f9fa;
                border: 1px solid #e3e6f0;
                border-radius: 8px;
                padding: 12px;
                transition: all 0.3s ease;
                position: relative;
            }

            .form-check-modern:hover {
                background: #e8f4fd;
                border-color: #696cff;
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(105, 108, 255, 0.1);
            }

            .form-check-modern input[type="checkbox"]:checked + label {
                color: #696cff;
                font-weight: 600;
            }

            .form-check-modern input[type="checkbox"]:checked ~ .permission-info {
                color: #696cff;
            }

            .permission-group {
                border: 1px solid #e3e6f0;
                border-radius: 12px;
                overflow: hidden;
                transition: box-shadow 0.3s ease;
            }

            .permission-group:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .cursor-pointer {
                cursor: pointer;
            }

            .breadcrumb-style1 {
                background: transparent;
                margin-bottom: 0;
            }

            .breadcrumb-style1 .breadcrumb-item + .breadcrumb-item::before {
                content: ">";
                color: #8592a3;
            }

            .btn-group .btn {
                border-radius: 6px;
            }

            .btn-group .btn:not(:last-child) {
                margin-right: 8px;
            }

            .form-control-lg {
                font-size: 1.1rem;
                padding: 12px 16px;
            }

            .permission-name {
                font-size: 0.95rem;
                line-height: 1.3;
            }

            .card {
                border-radius: 12px;
                border: 1px solid #e3e6f0;
            }

            .card-header.bg-primary {
                background: linear-gradient(135deg, #696cff 0%, #5a5fcf 100%) !important;
            }

            #permissionSearch {
                border-radius: 8px;
            }

            .alert {
                border-radius: 8px;
            }

            .badge {
                border-radius: 20px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Toggle all permissions
            function toggleAllPermissions(select) {
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = select;
                });
                updateSelectedSummary();
            }

            // Toggle group permissions
            function toggleGroupPermissions(group, select) {
                const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                checkboxes.forEach(checkbox => {
                    checkbox.checked = select;
                });
                updateSelectedSummary();
            }

            // Search permissions
            document.getElementById('permissionSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const permissionGroups = document.querySelectorAll('.permission-group');
                
                permissionGroups.forEach(group => {
                    const checkboxes = group.querySelectorAll('.permission-checkbox');
                    let hasVisibleItems = false;
                    
                    checkboxes.forEach(checkbox => {
                        const permissionName = checkbox.getAttribute('data-name');
                        const permissionContainer = checkbox.closest('.col-lg-4');
                        
                        if (permissionName.includes(searchTerm)) {
                            permissionContainer.style.display = 'block';
                            hasVisibleItems = true;
                        } else {
                            permissionContainer.style.display = 'none';
                        }
                    });
                    
                    // Hide/show entire group based on search results
                    group.style.display = hasVisibleItems ? 'block' : 'none';
                });
            });

            // Update selected permissions summary
            function updateSelectedSummary() {
                const selectedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
                const selectedCount = selectedCheckboxes.length;
                const summaryElement = document.getElementById('selectedSummary');
                const countElement = document.getElementById('selectedCount');
                
                if (selectedCount > 0) {
                    summaryElement.classList.remove('d-none');
                    countElement.textContent = selectedCount;
                } else {
                    summaryElement.classList.add('d-none');
                }
            }

            // Add event listeners to all checkboxes
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateSelectedSummary);
                });

                // Form validation
                const form = document.getElementById('roleForm');
                const submitBtn = document.getElementById('submitBtn');
                
                form.addEventListener('submit', function(e) {
                    const roleName = document.getElementById('name').value.trim();
                    const selectedPermissions = document.querySelectorAll('.permission-checkbox:checked');
                    
                    if (!roleName) {
                        e.preventDefault();
                        document.getElementById('name').focus();
                        return;
                    }
                    
                    if (selectedPermissions.length === 0) {
                        e.preventDefault();
                        alert('Vui lòng chọn ít nhất một quyền cho vai trò này.');
                        return;
                    }
                    
                    // Show loading state
                    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Đang tạo...';
                    submitBtn.disabled = true;
                });

                // Auto-focus on role name input
                document.getElementById('name').focus();
            });
        </script>
    @endpush
@endsection