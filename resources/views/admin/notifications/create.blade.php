@extends('admin.dashboard')

@section('title', 'Tạo Thông báo mới')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3 ">
                                <i class="menu-icon tf-icons bx bx-bell text-white "></i>
                            </div>
                            <div>
                                <h2 class=" mb-0 text-primary font-weight-bold">Tạo Thông báo mới</h2>
                                <p class="text-muted mb-0">Tạo và gửi thông báo đến người dùng trong hệ thống</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                            <i class="feather icon-home mr-1"></i>Trang chủ >  
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
                                            <i class="feather icon-bell mr-1"></i>Thông báo > 
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">Tạo mới</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Enhanced Alert Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-alert-circle mr-2"></i>
                        <strong>Có lỗi xảy ra!</strong>
                    </div>
                    <ul class="mb-0 mt-2 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-x-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Enhanced Main Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-edit-3 mr-2"></i>
                        <h4 class="card-title mb-0 text-white font-weight-bold">Thông tin Thông báo</h4>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.notifications.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Title Field -->
                        <div class="form-group mb-4">
                            <label for="title" class="form-label font-weight-semibold d-flex align-items-center">
                                <i class="feather icon-type mr-2 text-primary"></i>
                                Tiêu đề <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="input-group">
   
                                <input type="text" class="form-control form-control-lg border-left-0" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Nhập tiêu đề thông báo..." required>
                            </div>
                            <small class="form-text text-muted mt-1">
                                <i class="feather icon-info mr-1"></i>Tiêu đề ngắn gọn, súc tích để thu hút sự chú ý
                            </small>
                        </div>

                        <!-- Content Field -->
                        <div class="form-group mb-4">
                            <label for="content" class="form-label font-weight-semibold d-flex align-items-center">
                                <i class="feather icon-edit mr-2 text-primary"></i>
                                Nội dung <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="content-editor-wrapper">
                                <textarea class="form-control" id="content" name="content" rows="8" 
                                          placeholder="Nhập nội dung thông báo...">{{ old('content') }}</textarea>
                            </div>
                            <small class="form-text text-muted mt-1">
                                <i class="feather icon-edit-2 mr-1"></i>Sử dụng trình soạn thảo để định dạng nội dung
                            </small>
                        </div>

                        <!-- Type Field -->
                        <div class="form-group mb-4">
                            <label for="type" class="form-label font-weight-semibold d-flex align-items-center">
                                <i class="feather icon-tag mr-2 text-primary"></i>
                                Loại thông báo <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="input-group">
                                <select class="form-control custom-select border-left-0" id="type" name="type" required>
                                    <option value="">-- Chọn loại thông báo --</option>
                                    @foreach ($notificationTypes as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Recipient Section -->
                        <div class="recipient-section bg-light p-4 rounded mb-4">
                            <h5 class="mb-3 text-primary font-weight-bold">
                                <i class="feather icon-users mr-2"></i>Người nhận thông báo
                            </h5>
                            
                            <div class="form-group mb-3">
                                <label for="recipient_type" class="form-label font-weight-semibold">
                                    Gửi đến <span class="text-danger">*</span>
                                </label>
                                <div class="recipient-options">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="recipient_all" 
                                                       name="recipient_type" value="all" 
                                                       {{ old('recipient_type', 'all') == 'all' ? 'checked' : '' }}
                                                       onchange="toggleRecipientOptions()">
                                                <label class="custom-control-label" for="recipient_all">
                                                    <i class="feather icon-globe mr-1 text-success"></i>
                                                    Tất cả người dùng
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="recipient_specific" 
                                                       name="recipient_type" value="specific_users"
                                                       {{ old('recipient_type') == 'specific_users' ? 'checked' : '' }}
                                                       onchange="toggleRecipientOptions()">
                                                <label class="custom-control-label" for="recipient_specific">
                                                    <i class="feather icon-user mr-1 text-warning"></i>
                                                    Người dùng cụ thể
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="recipient_roles" 
                                                       name="recipient_type" value="roles"
                                                       {{ old('recipient_type') == 'roles' ? 'checked' : '' }}
                                                       onchange="toggleRecipientOptions()">
                                                <label class="custom-control-label" for="recipient_roles">
                                                    <i class="feather icon-shield mr-1 text-info"></i>
                                                    Theo vai trò
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Specific Users Selection -->
                            <div class="form-group mb-3" id="specific_users_div" 
                                 style="display: {{ old('recipient_type') == 'specific_users' ? 'block' : 'none' }};">
                                <label for="recipient_ids_users" class="form-label font-weight-semibold">
                                    <i class="feather icon-search mr-1"></i>Chọn người dùng
                                </label>
                                <select class="form-control select2" id="recipient_ids_users" name="recipient_ids[]" multiple="multiple">
                                    @if (old('recipient_type') == 'specific_users' && old('recipient_ids'))
                                        @foreach (App\Models\User::whereIn('id', old('recipient_ids'))->get() as $user)
                                            <option value="{{ $user->id }}" selected>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                <small class="form-text text-muted">
                                    <i class="feather icon-help-circle mr-1"></i>Gõ tên hoặc email để tìm kiếm người dùng
                                </small>
                            </div>

                            <!-- Roles Selection -->
                            <div class="form-group mb-3" id="roles_div" 
                                 style="display: {{ old('recipient_type') == 'roles' ? 'block' : 'none' }};">
                                <label for="recipient_ids_roles" class="form-label font-weight-semibold">
                                    <i class="feather icon-shield mr-1"></i>Chọn vai trò
                                </label>
                                <select class="form-control select2" id="recipient_ids_roles" name="recipient_ids[]" multiple="multiple">
                                    @if (old('recipient_type') == 'roles' && old('recipient_ids'))
                                        @foreach (App\Models\Role::whereIn('name', old('recipient_ids'))->get() as $role)
                                            <option value="{{ $role->id }}" selected>{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <small class="form-text text-muted">
                                    <i class="feather icon-info mr-1"></i>Chọn vai trò để gửi cho tất cả người dùng có vai trò đó
                                </small>
                            </div>
                        </div>

                        <!-- Scheduling Section -->
                        <div class="scheduling-section bg-light p-4 rounded mb-4">
                            <h5 class="mb-3 text-primary font-weight-bold">
                                <i class="feather icon-clock mr-2"></i>Lên lịch gửi
                            </h5>
                            
                            <div class="form-group mb-3">
                                <label for="scheduled_at" class="form-label font-weight-semibold">
                                    <i class="feather icon-calendar mr-1"></i>Thời gian lên lịch
                                </label>
                                <div class="input-group">

                                    <input type="text" class="form-control pickatime-format border-left-0" 
                                           id="scheduled_at" name="scheduled_at"
                                           placeholder="Chọn ngày và giờ gửi..."
                                           value="{{ old('scheduled_at') ? \Carbon\Carbon::parse(old('scheduled_at'))->format('Y-m-d H:i') : '' }}">
                                </div>
                                @error('scheduled_at')
                                    <span class="text-danger small mt-1"><i class="feather icon-alert-circle mr-1"></i>{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted mt-1">
                                    <i class="feather icon-info mr-1"></i>Để trống nếu muốn gửi ngay lập tức
                                </small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="send_now_checkbox" 
                                           name="send_now_checkbox" value="1" {{ old('send_now_checkbox') ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-semibold" for="send_now_checkbox">
                                        <i class="feather icon-send mr-1 text-primary"></i>
                                        Gửi ngay sau khi tạo (nếu không có lịch trình)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-actions d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="text-muted">
                                <i class="feather icon-info mr-1"></i>
                                <small>Các trường có dấu <span class="text-danger">*</span> là bắt buộc</small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('admin.notifications.index') }}" 
                                   class="btn btn-outline-secondary waves-effect mr-2">
                                    <i class="feather icon-x mr-1"></i>Hủy bỏ
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light px-4">
                                    <i class="feather icon-send mr-2"></i>Tạo thông báo
                                </button>
                            </div>
                        </div>
                    </form>
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

        .icon-circle i{
            margin: 0px auto
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .recipient-section, .scheduling-section {
            border-left: 4px solid #667eea;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .input-group-text {
            border-right: none;
        }
        
        .form-control.border-left-0 {
            border-left: none;
        }
        
        .content-editor-wrapper {
            border: 2px dashed #e3ebf0;
            border-radius: 8px;
            padding: 10px;
            transition: border-color 0.3s ease;
        }
        
        .content-editor-wrapper:hover {
            border-color: #667eea;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        .breadcrumb-item.active {
            color: #667eea !important;
        }
        
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
                gap: 1rem;
            }
            
            .btn-group {
                width: 100%;
                display: flex;
            }
            
            .btn-group .btn {
                flex: 1;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Hàm hiển thị/ẩn các lựa chọn người nhận
        function toggleRecipientOptions() {
            var recipientType = document.querySelector('input[name="recipient_type"]:checked').value;
            document.getElementById('specific_users_div').style.display = 'none';
            document.getElementById('roles_div').style.display = 'none';

            if (recipientType === 'specific_users') {
                document.getElementById('specific_users_div').style.display = 'block';
                if (!$('#recipient_ids_users').data('select2')) {
                    setupSelect2Users();
                }
            } else if (recipientType === 'roles') {
                document.getElementById('roles_div').style.display = 'block';
                if (!$('#recipient_ids_roles').data('select2')) {
                    setupSelect2Roles();
                }
            }
        }

        // Khởi tạo Select2 cho người dùng
        function setupSelect2Users() {
            $('#recipient_ids_users').select2({
                placeholder: 'Tìm kiếm người dùng...',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('admin.notifications.getUsers') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { search: params.term };
                    },
                    processResults: function(data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });
        }

        // Khởi tạo Select2 cho vai trò
        function setupSelect2Roles() {
            $('#recipient_ids_roles').select2({
                placeholder: 'Chọn vai trò...',
                ajax: {
                    url: '{{ route('admin.notifications.getRoles') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { search: params.term };
                    },
                    processResults: function(data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });
        }

        $(document).ready(function() {
            toggleRecipientOptions();

            // Khởi tạo TinyMCE cho trường content
            tinymce.init({
                selector: '#content',
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table paste emoticons template codesample directionality',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                height: 300,
                menubar: 'file edit view insert format tools table help',
                forced_root_block: false,
                skin: 'oxide',
                content_css: 'default'
            });

            // Khởi tạo Flatpickr với giới hạn thời gian từ hiện tại
            $('.pickatime-format').flatpickr({
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                altInput: true,
                altFormat: 'd/m/Y H:i',
                time_24hr: true,
                minDate: 'today',
                minuteIncrement: 1,
                defaultDate: '{{ old('scheduled_at') ? \Carbon\Carbon::parse(old('scheduled_at'))->format('Y-m-d H:i') : '' }}'
            });

            // Xử lý logic checkbox "Gửi ngay"
            $('#scheduled_at').on('change', function() {
                if ($(this).val()) {
                    $('#send_now_checkbox').prop('checked', false);
                }
            });

            $('#send_now_checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#scheduled_at').val('').flatpickr().clear();
                }
            });

            // Validation phía client trước khi submit
            $('form').on('submit', function(e) {
                const scheduledAt = $('#scheduled_at').val();
                const sendNow = $('#send_now_checkbox').is(':checked');
                
                if (scheduledAt && !sendNow) {
                    const selectedTime = new Date(scheduledAt);
                    const now = new Date();
                    if (selectedTime < now) {
                        e.preventDefault();
                        // Hiển thị thông báo đẹp hơn
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi thời gian!',
                            text: 'Thời gian lên lịch phải từ hiện tại trở đi.',
                            confirmButtonColor: '#667eea'
                        });
                        $('#scheduled_at').focus();
                    }
                }
            });

            // Smooth scrolling cho form validation
            $('.form-control').on('invalid', function() {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    </script>
@endpush