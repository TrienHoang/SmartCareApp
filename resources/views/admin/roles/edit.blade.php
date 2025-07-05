@extends('admin.dashboard')
@section('title', 'Chỉnh sửa vai trò')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb with improved styling -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Chỉnh sửa vai trò</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}">Quản lý vai trò</a>
                        </li>
                        <li class="breadcrumb-item active">Chỉnh sửa: {{ $role->name }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i>Quay lại
            </a>
        </div>

        <!-- Role Info Summary Card -->
        <div class="card mb-4 border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-wrapper me-3">
                        <span class="avatar avatar-lg bg-warning text-white">
                            <i class="bx bx-user-circle"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">{{ $role->name }}</h5>
                        <p class="text-muted mb-0">
                            <i class="bx bx-time me-1"></i>
                            @if($role->created_at)
                                Được tạo: {{ $role->created_at->format('d/m/Y') }}
                            @else
                                Được tạo: Chưa rõ
                            @endif
                        
                            @if($role->updated_at && $role->updated_at != $role->created_at)
                                | Cập nhật: {{ $role->updated_at->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary fs-6">
                            {{ count($rolePermissions) }} quyền được cấp
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="card-title text-white mb-0">
                    <i class="bx bx-edit-alt me-2"></i>Cập nhật thông tin vai trò
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" id="roleEditForm">
                    @csrf
                    @method('PUT')

                    <!-- Role Name Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bx bx-tag me-1"></i>Tên vai trò <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   placeholder="Ví dụ: Quản trị viên, Biên tập viên..."
                                   value="{{ old('name', $role->name) }}"
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

                    <!-- Changes Tracker -->
                    <div class="alert alert-info d-none" id="changesAlert">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-info-circle me-2"></i>
                            <div>
                                <strong>Có thay đổi chưa lưu!</strong>
                                <div class="mt-1">
                                    <small id="changesDetails"></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label fw-semibold mb-0">
                                <i class="bx bx-shield-check me-1"></i>Phân quyền 
                                <span class="text-danger">*</span>
                                <span class="badge bg-secondary ms-2" id="currentPermissionCount">{{ count($rolePermissions) }}</span>
                            </label>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-success" onclick="toggleAllPermissions(true)">
                                    <i class="bx bx-check-square me-1"></i>Chọn tất cả
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="toggleAllPermissions(false)">
                                    <i class="bx bx-square me-1"></i>Bỏ chọn tất cả
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="resetToOriginal()">
                                    <i class="bx bx-reset me-1"></i>Khôi phục
                                </button>
                            </div>
                        </div>

                        <!-- Search and filters -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bx bx-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="permissionSearch" 
                                           placeholder="Tìm kiếm quyền...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="filterType" id="filterAll" value="all" checked>
                                    <label class="btn btn-outline-primary" for="filterAll">Tất cả</label>
                                    
                                    <input type="radio" class="btn-check" name="filterType" id="filterSelected" value="selected">
                                    <label class="btn btn-outline-success" for="filterSelected">Đã chọn</label>
                                    
                                    <input type="radio" class="btn-check" name="filterType" id="filterUnselected" value="unselected">
                                    <label class="btn btn-outline-secondary" for="filterUnselected">Chưa chọn</label>
                                </div>
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
                                                    <span class="badge bg-success ms-1 group-selected-count" data-group="{{ $group }}">
                                                        {{ count(array_intersect(array_column($perms, 'id'), $rolePermissions)) }}
                                                    </span>
                                                </h6>
                                                <div class="d-flex align-items-center">
                                                    <button type="button" class="btn btn-sm btn-outline-success me-1" 
                                                            onclick="toggleGroupPermissions('{{ $group }}', true)"
                                                            title="Chọn tất cả trong nhóm">
                                                        <i class="bx bx-check-square"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger me-2" 
                                                            onclick="toggleGroupPermissions('{{ $group }}', false)"
                                                            title="Bỏ chọn tất cả trong nhóm">
                                                            <i class='bx bx-x-circle'></i> 
                                                    </button>
                                                    <i class="bx bx-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="collapse show" id="group_{{ $loop->index }}">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($perms as $permission)
                                                        @php
                                                            $isChecked = in_array($permission['id'], $rolePermissions);
                                                        @endphp
                                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                            <div class="form-check form-check-modern {{ $isChecked ? 'selected' : '' }}">
                                                                <input class="form-check-input permission-checkbox" 
                                                                       type="checkbox"
                                                                       name="permissions[]"
                                                                       value="{{ $permission['id'] }}"
                                                                       id="perm_{{ $permission['id'] }}"
                                                                       data-group="{{ $group }}"
                                                                       data-name="{{ strtolower(getPermissionLabel($permission['name'])) }}"
                                                                       data-original="{{ $isChecked ? 'true' : 'false' }}"
                                                                       {{ $isChecked ? 'checked' : '' }}>
                                                                <label class="form-check-label d-flex align-items-center" 
                                                                       for="perm_{{ $permission['id'] }}">
                                                                    <div class="permission-info">
                                                                        <div class="permission-name fw-medium">
                                                                            {{ getPermissionLabel($permission['name']) }}
                                                                            @if($isChecked)
                                                                                <i class="bx bx-check-circle text-success ms-1"></i>
                                                                            @endif
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

                        <!-- Changes summary -->
                        <div class="mt-3">
                            <div class="alert alert-warning d-none" id="changesSummary">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="bx bx-plus-circle text-success display-6"></i>
                                            <div class="mt-2">
                                                <strong id="addedCount">0</strong> quyền được thêm
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="bx bx-minus-circle text-danger display-6"></i>
                                            <div class="mt-2">
                                                <strong id="removedCount">0</strong> quyền bị xóa
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="bx bx-check-circle text-primary display-6"></i>
                                            <div class="mt-2">
                                                <strong id="totalSelected">{{ count($rolePermissions) }}</strong> tổng quyền
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                    <button type="button" class="btn btn-outline-warning" onclick="resetForm()">
                                        <i class="bx bx-reset me-1"></i>Khôi phục
                                    </button>
                                    <button type="submit" class="btn btn-warning" id="submitBtn">
                                        <i class="bx bx-save me-1"></i>Cập nhật vai trò
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

            .form-check-modern.selected {
                background: #e8f8f5;
                border-color: #28a745;
            }

            .form-check-modern:hover {
                background: #e8f4fd;
                border-color: #ffc107;
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(255, 193, 7, 0.2);
            }

            .form-check-modern input[type="checkbox"]:checked + label {
                color: #28a745;
                font-weight: 600;
            }

            .form-check-modern input[type="checkbox"]:checked ~ .permission-info {
                color: #28a745;
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

            .card-header.bg-warning {
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
            }

            .avatar {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
            }

            .border-warning {
                border-color: #ffc107 !important;
                border-width: 2px !important;
            }

            .btn-check:checked + .btn {
                background-color: var(--bs-primary);
                border-color: var(--bs-primary);
                color: white;
            }

            .permission-changed {
                position: relative;
            }

            .permission-changed::after {
                content: '';
                position: absolute;
                top: -2px;
                right: -2px;
                width: 8px;
                height: 8px;
                background-color: #ffc107;
                border-radius: 50%;
                border: 2px solid white;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            let originalPermissions = @json($rolePermissions);
            let hasUnsavedChanges = false;

            // Toggle all permissions
            function toggleAllPermissions(select) {
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = select;
                    updatePermissionVisualState(checkbox);
                });
                updateAllCounters();
                checkForChanges();
            }

            // Toggle group permissions
            function toggleGroupPermissions(group, select) {
                const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                checkboxes.forEach(checkbox => {
                    checkbox.checked = select;
                    updatePermissionVisualState(checkbox);
                });
                updateGroupCounter(group);
                updateAllCounters();
                checkForChanges();
            }

            // Reset to original permissions
            function resetToOriginal() {
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(checkbox => {
                    const originalState = checkbox.getAttribute('data-original') === 'true';
                    checkbox.checked = originalState;
                    updatePermissionVisualState(checkbox);
                });
                updateAllCounters();
                checkForChanges();
            }

            // Reset entire form
            function resetForm() {
                document.getElementById('name').value = '{{ $role->name }}';
                resetToOriginal();
            }

            // Update visual state of permission checkbox
            function updatePermissionVisualState(checkbox) {
                const container = checkbox.closest('.form-check-modern');
                const originalState = checkbox.getAttribute('data-original') === 'true';
                const currentState = checkbox.checked;
                
                // Update selected state
                if (currentState) {
                    container.classList.add('selected');
                } else {
                    container.classList.remove('selected');
                }
                
                // Mark as changed if different from original
                if (originalState !== currentState) {
                    container.classList.add('permission-changed');
                } else {
                    container.classList.remove('permission-changed');
                }
            }

            // Update group counter
            function updateGroupCounter(group) {
                const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                const selectedCount = Array.from(groupCheckboxes).filter(cb => cb.checked).length;
                const counter = document.querySelector(`.group-selected-count[data-group="${group}"]`);
                if (counter) {
                    counter.textContent = selectedCount;
                }
            }

            // Update all counters and changes summary
            function updateAllCounters() {
                // Update current permission count
                const totalSelected = document.querySelectorAll('.permission-checkbox:checked').length;
                document.getElementById('currentPermissionCount').textContent = totalSelected;
                document.getElementById('totalSelected').textContent = totalSelected;

                // Update group counters
                const groups = [...new Set(Array.from(document.querySelectorAll('.permission-checkbox')).map(cb => cb.getAttribute('data-group')))];
                groups.forEach(group => updateGroupCounter(group));

                // Calculate changes
                const currentPermissions = Array.from(document.querySelectorAll('.permission-checkbox:checked')).map(cb => parseInt(cb.value));
                const added = currentPermissions.filter(id => !originalPermissions.includes(id));
                const removed = originalPermissions.filter(id => !currentPermissions.includes(id));

                document.getElementById('addedCount').textContent = added.length;
                document.getElementById('removedCount').textContent = removed.length;

                // Show/hide changes summary
                const changesSummary = document.getElementById('changesSummary');
                if (added.length > 0 || removed.length > 0) {
                    changesSummary.classList.remove('d-none');
                } else {
                    changesSummary.classList.add('d-none');
                }
            }

            // Check for unsaved changes
            function checkForChanges() {
                const currentName = document.getElementById('name').value;
                const originalName = '{{ $role->name }}';
                const currentPermissions = Array.from(document.querySelectorAll('.permission-checkbox:checked')).map(cb => parseInt(cb.value));
                
                const nameChanged = currentName !== originalName;
                const permissionsChanged = JSON.stringify(currentPermissions.sort()) !== JSON.stringify(originalPermissions.sort());
                
                hasUnsavedChanges = nameChanged || permissionsChanged;
                
                const changesAlert = document.getElementById('changesAlert');
                const changesDetails = document.getElementById('changesDetails');
                
                if (hasUnsavedChanges) {
                    changesAlert.classList.remove('d-none');
                    let details = [];
                    if (nameChanged) details.push('Tên vai trò đã thay đổi');
                    if (permissionsChanged) details.push('Phân quyền đã thay đổi');
                    changesDetails.textContent = details.join(' • ');
                } else {
                    changesAlert.classList.add('d-none');
                }
            }

            // Search permissions
            function filterPermissions() {
                const searchTerm = document.getElementById('permissionSearch').value.toLowerCase();
                const filterType = document.querySelector('input[name="filterType"]:checked').value;
                const permissionGroups = document.querySelectorAll('.permission-group');
                
                permissionGroups.forEach(group => {
                    const checkboxes = group.querySelectorAll('.permission-checkbox');
                    let hasVisibleItems = false;
                    
                    checkboxes.forEach(checkbox => {
                        const permissionName = checkbox.getAttribute('data-name');
                        const permissionContainer = checkbox.closest('.col-lg-4');
                        const isChecked = checkbox.checked;
                        
                        let shouldShow = true;
                        
                        // Apply search filter
                        if (searchTerm && !permissionName.includes(searchTerm)) {
                            shouldShow = false;
                        }
                        
                        // Apply type filter
                        if (filterType === 'selected' && !isChecked) {
                            shouldShow = false;
                        } else if (filterType === 'unselected' && isChecked) {
                            shouldShow = false;
                        }
                        
                        if (shouldShow) {
                            permissionContainer.style.display = 'block';
                            hasVisibleItems = true;
                        } else {
                            permissionContainer.style.display = 'none';
                        }
                    });
                    
                    // Hide/show entire group based on search results
                    group.style.display = hasVisibleItems ? 'block' : 'none';
                });
            }

            // Prevent accidental navigation
            function preventNavigation(e) {
                if (hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = 'Bạn có thay đổi chưa được lưu. Bạn có chắc chắn muốn rời khỏi trang này?';
                    return e.returnValue;
                }
            }

            // Initialize
            document.addEventListener('DOMContentLoaded', function() {
                // Add event listeners to all checkboxes
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updatePermissionVisualState(this);
                        updateAllCounters();
                        checkForChanges();
                    });
                });

                // Add event listener for name input
                document.getElementById('name').addEventListener('input', checkForChanges);

                // Add event listeners for search and filters
                document.getElementById('permissionSearch').addEventListener('input', filterPermissions);
                document.querySelectorAll('input[name="filterType"]').forEach(radio => {
                    radio.addEventListener('change', filterPermissions);
                });

                // Form submission
                const form = document.getElementById('roleEditForm');
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
                    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Đang cập nhật...';
                    submitBtn.disabled = true;
                    hasUnsavedChanges = false; // Prevent navigation warning
                });

                // Initialize visual states
                checkboxes.forEach(checkbox => {
                    updatePermissionVisualState(checkbox);
                });
                updateAllCounters();

                // Prevent accidental navigation
                window.addEventListener('beforeunload', preventNavigation);

                // Auto-focus on role name input
                document.getElementById('name').focus();
                document.getElementById('name').select();
            });
        </script>
    @endpush
@endsection