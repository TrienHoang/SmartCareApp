@extends('admin.dashboard')

@section('title', 'Chỉnh sửa Thông báo')

@section('content')
    <div class="content-wrapper">
        <!-- Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bx bx-bell me-2 text-primary" style="font-size: 24px;"></i>
                            <h2 class="content-header-title mb-0">Chi tiết Thông báo</h2>
                        </div>
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            </i>Trang chủ >
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
                                            </i>Thông báo >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <!-- Form Card -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="avatar-wrapper me-3">
                                    <div class="avatar avatar-lg bg-primary">
                                        <i class="bx bx-edit text-white" style="font-size: 20px;"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="card-title mb-1">Thông tin Thông báo</h4>
                                    <small class="text-muted">Cập nhật thông tin thông báo của bạn</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bx bx-error-circle me-2" style="font-size: 18px;"></i>
                                        <strong>Vui lòng kiểm tra lại thông tin:</strong>
                                    </div>
                                    <ul class="mb-0 ps-4">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-error-circle me-2" style="font-size: 18px;"></i>
                                        {{ session('error') }}
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Form -->
                            <form action="{{ route('admin.notifications.update', $notification) }}" method="POST"
                                class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-lg-8">
                                        <!-- Basic Information Section -->
                                        <div class="form-section mb-4">
                                            <div class="section-header mb-3">
                                                <h5 class="section-title">
                                                    <i class="bx bx-info-circle text-primary me-2"></i>
                                                    Thông tin cơ bản
                                                </h5>

                                            </div>

                                            <!-- Title Field -->
                                            <div class="form-group mb-3">
                                                <label for="title" class="form-label d-flex align-items-center">
                                                    <i class="bx bx-text me-2 text-muted"></i>
                                                    Tiêu đề <span class="text-danger ms-1">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-heading"></i>
                                                    </span>
                                                    <input type="text"
                                                        class="form-control @error('title') is-invalid @enderror"
                                                        id="title" name="title"
                                                        value="{{ old('title', e($notification->title)) }}" required
                                                        placeholder="Nhập tiêu đề thông báo...">
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Content Field -->
                                            <div class="form-group mb-3">
                                                <label for="content" class="form-label d-flex align-items-center">
                                                    <i class="bx bx-file-blank me-2 text-muted"></i>
                                                    Nội dung <span class="text-danger ms-1">*</span>
                                                </label>
                                                <div class="content-editor-wrapper">
                                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8"
                                                        placeholder="Nhập nội dung thông báo...">{{ old('content', $notification->content) }}</textarea>
                                                    @error('content')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Type Field -->
                                            <div class="form-group mb-3">
                                                <label for="type" class="form-label d-flex align-items-center">
                                                    <i class="bx bx-category me-2 text-muted"></i>
                                                    Loại thông báo <span class="text-danger ms-1">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-tag"></i>
                                                    </span>
                                                    <select class="form-select @error('type') is-invalid @enderror"
                                                        id="type" name="type" required>
                                                        <option value="">Chọn loại thông báo</option>
                                                        @foreach ($notificationTypes as $type)
                                                            <option value="{{ $type }}"
                                                                {{ old('type', $notification->type) == $type ? 'selected' : '' }}>
                                                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Recipients Section -->
                                        <div class="form-section mb-4">
                                            <div class="section-header mb-3">
                                                <h5 class="section-title">
                                                    <i class="bx bx-group text-primary me-2"></i>
                                                    Người nhận
                                                </h5>

                                            </div>

                                            <!-- Recipient Type -->
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center">
                                                    <i class="bx bx-target-lock me-2 text-muted"></i>
                                                    Gửi đến <span class="text-danger ms-1">*</span>
                                                </label>
                                                <div class="recipient-type-cards">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <div class="recipient-card" data-value="all">
                                                                <input type="radio" name="recipient_type"
                                                                    value="all" id="recipient_all"
                                                                    class="recipient-radio"
                                                                    {{ old('recipient_type', $notification->recipient_type) == 'all' ? 'checked' : '' }}
                                                                    onchange="toggleRecipientOptions()">
                                                                <label for="recipient_all" class="recipient-label">
                                                                    <i class="bx bx-world recipient-icon"></i>
                                                                    <span class="recipient-title">Tất cả</span>
                                                                    <small class="recipient-desc">Gửi đến tất cả người
                                                                        dùng</small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="recipient-card" data-value="specific_users">
                                                                <input type="radio" name="recipient_type"
                                                                    value="specific_users" id="recipient_users"
                                                                    class="recipient-radio"
                                                                    {{ old('recipient_type', $notification->recipient_type) == 'specific_users' ? 'checked' : '' }}
                                                                    onchange="toggleRecipientOptions()">
                                                                <label for="recipient_users" class="recipient-label">
                                                                    <i class="bx bx-user recipient-icon"></i>
                                                                    <span class="recipient-title">Người dùng</span>
                                                                    <small class="recipient-desc">Chọn người dùng cụ
                                                                        thể</small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="recipient-card" data-value="roles">
                                                                <input type="radio" name="recipient_type"
                                                                    value="roles" id="recipient_roles"
                                                                    class="recipient-radio"
                                                                    {{ old('recipient_type', $notification->recipient_type) == 'roles' ? 'checked' : '' }}
                                                                    onchange="toggleRecipientOptions()">
                                                                <label for="recipient_roles" class="recipient-label">
                                                                    <i class="bx bx-shield recipient-icon"></i>
                                                                    <span class="recipient-title">Vai trò</span>
                                                                    <small class="recipient-desc">Chọn theo vai trò</small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Specific Users Selection -->
                                            <div class="form-group mb-3" id="specific_users_div"
                                                style="display: {{ old('recipient_type', $notification->recipient_type) == 'specific_users' ? 'block' : 'none' }};">
                                                <label for="recipient_ids_users"
                                                    class="form-label d-flex align-items-center">
                                                    <i class="bx bx-user-check me-2 text-muted"></i>
                                                    Chọn người dùng
                                                </label>
                                                <div class="select-wrapper">
                                                    <select class="form-control select2" id="recipient_ids_users"
                                                        name="recipient_ids[]" multiple="multiple">
                                                        @if ($notification->recipient_type == 'specific_users' && !empty($notification->recipient_ids))
                                                            @php
                                                                $selectedUserIds = is_array(
                                                                    $notification->recipient_ids,
                                                                )
                                                                    ? $notification->recipient_ids
                                                                    : json_decode($notification->recipient_ids, true);
                                                                $selectedUsers = App\Models\User::whereIn(
                                                                    'id',
                                                                    $selectedUserIds,
                                                                )->get();
                                                            @endphp
                                                            @foreach ($selectedUsers as $user)
                                                                <option value="{{ $user->id }}" selected>
                                                                    {{ e($user->name) }} ({{ e($user->email) }})
                                                                </option>
                                                            @endforeach
                                                        @elseif (old('recipient_type') == 'specific_users' && old('recipient_ids'))
                                                            @php
                                                                $oldSelectedUserIds = old('recipient_ids');
                                                                $oldSelectedUsers = App\Models\User::whereIn(
                                                                    'id',
                                                                    $oldSelectedUserIds,
                                                                )->get();
                                                            @endphp
                                                            @foreach ($oldSelectedUsers as $user)
                                                                <option value="{{ $user->id }}" selected>
                                                                    {{ e($user->name) }} ({{ e($user->email) }})
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Roles Selection -->
                                            <div class="form-group mb-3" id="roles_div"
                                                style="display: {{ old('recipient_type', $notification->recipient_type) == 'roles' ? 'block' : 'none' }};">
                                                <label for="recipient_ids_roles"
                                                    class="form-label d-flex align-items-center">
                                                    <i class="bx bx-shield-check me-2 text-muted"></i>
                                                    Chọn vai trò
                                                </label>
                                                <div class="select-wrapper">
                                                    <select class="form-control select2" id="recipient_ids_roles"
                                                        name="recipient_ids[]" multiple="multiple">
                                                        @if ($notification->recipient_type == 'roles' && !empty($notification->recipient_ids))
                                                            @php
                                                                $selectedRoleNames = is_array(
                                                                    $notification->recipient_ids,
                                                                )
                                                                    ? $notification->recipient_ids
                                                                    : json_decode($notification->recipient_ids, true);
                                                                $selectedRoles = App\Models\Role::whereIn(
                                                                    'id',
                                                                    $selectedRoleNames,
                                                                )->get();
                                                            @endphp
                                                            @foreach ($selectedRoles as $role)
                                                                <option value="{{ $role->id }}" selected>
                                                                    {{ e(ucfirst($role->name)) }}
                                                                </option>
                                                            @endforeach
                                                        @elseif (old('recipient_type') == 'roles' && old('recipient_ids'))
                                                            @php
                                                                $oldSelectedRoleValues = old('recipient_ids');
                                                                $oldSelectedRoles = App\Models\Role::whereIn(
                                                                    'id',
                                                                    $oldSelectedRoleValues,
                                                                )->get();
                                                            @endphp
                                                            @foreach ($oldSelectedRoles as $role)
                                                                <option value="{{ $role->id }}" selected>
                                                                    {{ e(ucfirst($role->name)) }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-lg-4">
                                        <!-- Schedule Section -->
                                        <div class="sticky-sidebar">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-primary text-white mb-2">
                                                    <h6 class="mb-0">
                                                        <i class="bx bx-time me-2"></i>
                                                        Lên lịch gửi
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <label for="scheduled_at"
                                                            class="form-label d-flex align-items-center">
                                                            <i class="bx bx-calendar me-2 text-muted"></i>
                                                            Thời gian lên lịch
                                                        </label>
                                                        <div class="flatpickr-container">
                                                            <div class="input-group">
                                                                <span class="input-group-text">
                                                                    <i class="bx bx-time-five"></i>
                                                                </span>
                                                                <input type="text"
                                                                    class="form-control pickatime-format @error('scheduled_at') is-invalid @enderror"
                                                                    id="scheduled_at" name="scheduled_at"
                                                                    value="{{ old('scheduled_at', $notification->scheduled_at ? $notification->scheduled_at->format('Y-m-d H:i') : '') ?: '' }}"
                                                                    placeholder="Chọn thời gian...">
                                                                @error('scheduled_at')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <small class="form-text text-muted mt-1">
                                                                <i class="bx bx-info-circle me-1"></i>
                                                                Để trống để gửi ngay lập tức
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="action-buttons mt-4">
                                                        <button type="submit" class="btn btn-primary w-100 mb-2">
                                                            <i class="bx bx-save me-2"></i>
                                                            Cập nhật thông báo
                                                        </button>
                                                        <a href="{{ route('admin.notifications.index') }}"
                                                            class="btn btn-outline-secondary w-100">
                                                            <i class="bx bx-x me-2"></i>
                                                            Hủy bỏ
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quick Tips -->
                                            <div class="card border-0 shadow-sm mt-3">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0 text-muted">
                                                        <i class="bx bx-bulb me-2"></i>
                                                        Mẹo sử dụng
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0 quick-tips">
                                                        <li><i class="bx bx-check text-success me-2"></i>Sử dụng tiêu đề
                                                            ngắn gọn</li>
                                                        <li><i class="bx bx-check text-success me-2"></i>Nội dung rõ ràng,
                                                            dễ hiểu</li>
                                                        <li><i class="bx bx-check text-success me-2"></i>Chọn đúng đối
                                                            tượng nhận</li>
                                                        <li><i class="bx bx-check text-success me-2"></i>Kiểm tra trước khi
                                                            gửi</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .flatpickr-container .input-group-text {
            background-color: #f8f9fc;
            border-color: #d1d3e2;
            color: #6c757d;
        }

        .flatpickr-container .form-control {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }

        .flatpickr-container .flatpickr-input {
            height: auto !important;
        }

        .custom-flatpickr {
            border-radius: 0.35rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .custom-flatpickr .flatpickr-day.selected {
            background-color: #667eea;
            border-color: #667eea;
        }

        /* Form Sections */
        .form-section {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid #e3e6f0;
            margin-bottom: 1.5rem;
        }

        .section-header {
            border-bottom: 2px solid #f8f9fc;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .section-title {
            color: #5a5c69;
            font-weight: 600;
            margin: 0;
        }

        .section-divider {
            margin: 0;
            border-color: #e3e6f0;
        }

        /* Avatar */
        .avatar {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Form Controls */
        .form-label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.15s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #5a67d8;
            box-shadow: 0 0 0 0.2rem rgba(90, 103, 216, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fc;
            border-color: #d1d3e2;
            color: #6c757d;
        }

        /* Recipient Type Cards */
        .recipient-type-cards {
            margin-bottom: 1rem;
        }

        .recipient-card {
            position: relative;
            height: 100%;
        }

        .recipient-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .recipient-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1.5rem 1rem;
            border: 2px solid #e3e6f0;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
            margin: 0;
            background: #fff;
        }

        .recipient-label:hover {
            border-color: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(90, 103, 216, 0.15);
        }

        .recipient-radio:checked+.recipient-label {
            border-color: #5a67d8;
            background-color: #f7fafc;
            color: #5a67d8;
        }

        .recipient-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .recipient-radio:checked+.recipient-label .recipient-icon {
            color: #5a67d8;
        }

        .recipient-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .recipient-desc {
            font-size: 0.75rem;
            color: #6c757d;
        }

        /* Select2 Wrapper */
        .select-wrapper {
            position: relative;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-selection {
            border: 1px solid #d1d3e2 !important;
            border-radius: 0.35rem !important;
            min-height: 45px !important;
        }

        /* Sticky Sidebar */
        .sticky-sidebar {
            position: sticky;
            top: 2rem;
        }

        /* Quick Tips */
        .quick-tips li {
            padding: 0.25rem 0;
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* Alert Improvements */
        .alert {
            border: none;
            border-radius: 0.5rem;
            border-left: 4px solid;
        }

        .alert-danger {
            border-left-color: #e74c3c;
            background-color: #fdf2f2;
            color: #721c24;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sticky-sidebar {
                position: static;
                margin-top: 2rem;
            }

            .recipient-type-cards .col-md-4 {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }

            .recipient-label {
                padding: 1rem 0.5rem;
            }

            .recipient-icon {
                font-size: 1.5rem;
            }
        }

        /* Content Editor */
        .content-editor-wrapper {
            position: relative;
        }

        .content-editor-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #5a67d8, #667eea);
            border-radius: 0.35rem 0.35rem 0 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .content-editor-wrapper:focus-within::before {
            opacity: 1;
        }

        /* Button Enhancements */
        .btn {
            border-radius: 0.375rem;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-1px);
        }
    </style>
@endsection

@push('scripts')
    <script>
        // Hàm hiển thị/ẩn các lựa chọn người nhận
        function toggleRecipientOptions() {
            const recipientType = document.querySelector('input[name="recipient_type"]:checked')?.value;
            document.getElementById('specific_users_div').style.display = 'none';
            document.getElementById('roles_div').style.display = 'none';

            if (recipientType === 'specific_users') {
                document.getElementById('specific_users_div').style.display = 'block';
            } else if (recipientType === 'roles') {
                document.getElementById('roles_div').style.display = 'block';
            }
        }

        function setupSelect2Users() {
            $('#recipient_ids_users').select2({
                placeholder: 'Tìm kiếm người dùng...',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('admin.notifications.getUsers') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            selected_ids: $('#recipient_ids_users').val() || []
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results || []
                        };
                    },
                    cache: true
                }
            }).on('select2:select', function() {
                // Đảm bảo giá trị được cập nhật khi chọn
                $(this).trigger('change');
            }).on('select2:unselect', function() {
                $(this).trigger('change');
            });
        }

        function setupSelect2Roles() {
            $('#recipient_ids_roles').select2({
                placeholder: 'Chọn vai trò...',
                ajax: {
                    url: '{{ route('admin.notifications.getRoles') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            selected_names: $('#recipient_ids_roles').val() || []
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results || []
                        };
                    },
                    cache: true
                }
            }).on('select2:select', function() {
                $(this).trigger('change');
            }).on('select2:unselect', function() {
                $(this).trigger('change');
            });

            // Đặt giá trị ban đầu nếu có
            const initialRoles = $('#recipient_ids_roles').data('initial-values') || [];
            if (initialRoles.length > 0) {
                $('#recipient_ids_roles').val(initialRoles).trigger('change');
            }
        }

        $(document).ready(function() {
            // Khởi tạo Select2
            setupSelect2Users();
            setupSelect2Roles();

            // Gọi hàm toggle ban đầu
            toggleRecipientOptions();

            // Khởi tạo TinyMCE
            tinymce.init({
                selector: '#content',
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table paste emoticons template codesample directionality',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                height: 300,
                menubar: 'file edit view insert format tools table help',
                forced_root_block: false,
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save(); // Đảm bảo nội dung được lưu vào textarea
                    });
                }
            });

            // Khởi tạo Flatpickr
            $(document).ready(function() {
            if (typeof flatpickr === 'undefined') {
                console.error('Flatpickr not loaded. Please check the script inclusion.');
                return;
            }
            $('.pickatime-format').flatpickr({
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                altInput: true,
                altFormat: 'd/m/Y H:i',
                time_24hr: true,
                minDate: 'today',
                minuteIncrement: 1,
                defaultDate: '{{ old('scheduled_at') ? \Carbon\Carbon::parse(old('scheduled_at'))->format('Y-m-d H:i') : '' }}' || null,
                onReady: function(selectedDates, dateStr, instance) {
                    instance.calendarContainer.classList.add('custom-flatpickr');
                },
                theme: 'material_blue'
            });
        });

            // Validation phía client
            $('form').on('submit', function(e) {
                const title = $('#title').val().trim();
                const content = tinymce.get('content').getContent().trim();
                const type = $('#type').val();
                const recipientType = document.querySelector(
                    'input[name="recipient_type"]:checked')?.value;
                const scheduledAt = $('#scheduled_at').val();

                if (!title || !content || !type || !recipientType) {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ các trường bắt buộc (*).');
                    return;
                }

                if (scheduledAt) {
                    const selectedTime = new Date(scheduledAt);
                    const now = new Date();
                    if (selectedTime < now) {
                        e.preventDefault();
                        alert('Thời gian lên lịch phải từ hiện tại trở đi.');
                        $('#scheduled_at').focus();
                    }
                }
            });
        });
    </script>
@endpush
