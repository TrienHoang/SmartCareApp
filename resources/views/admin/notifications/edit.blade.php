@extends('admin.dashboard')

@section('title', 'Chỉnh sửa Thông báo')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Chỉnh sửa Thông báo</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="me-1"><a href="{{ route('admin.dashboard') }}">Trang chủ  ></a></li>
                                <li class="me-1"><a href="{{ route('admin.notifications.index') }}">Thông báo  ></a></li>
                                <li class="breadcrumb-item active">Chỉnh sửa</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông tin Thông báo</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('admin.notifications.update', $notification) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Quan trọng: Sử dụng method PUT cho update --}}

                        <div class="form-group">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $notification->title) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="8" required>{{ old('content', $notification->content) }}</textarea>
                            <small class="form-text text-muted">Bạn có thể sử dụng HTML cơ bản hoặc Markdown để định dạng nội dung.</small>
                        </div>
                        <div class="form-group">
                            <label for="type">Loại thông báo <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                @foreach($notificationTypes as $type)
                                    <option value="{{ $type }}" {{ old('type', $notification->type) == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="recipient_type">Gửi đến <span class="text-danger">*</span></label>
                            <select class="form-control" id="recipient_type" name="recipient_type" required onchange="toggleRecipientOptions()">
                                <option value="all" {{ old('recipient_type', $notification->recipient_type) == 'all' ? 'selected' : '' }}>Tất cả người dùng</option>
                                <option value="specific_users" {{ old('recipient_type', $notification->recipient_type) == 'specific_users' ? 'selected' : '' }}>Người dùng cụ thể</option>
                                <option value="roles" {{ old('recipient_type', $notification->recipient_type) == 'roles' ? 'selected' : '' }}>Theo vai trò</option>
                                {{-- <option value="by_condition" {{ old('recipient_type', $notification->recipient_type) == 'by_condition' ? 'selected' : '' }}>Theo điều kiện</option> --}}
                            </select>
                        </div>

                        {{-- Phần chọn người dùng cụ thể --}}
                        <div class="form-group" id="specific_users_div" style="display: {{ old('recipient_type', $notification->recipient_type) == 'specific_users' ? 'block' : 'none' }};">
                            <label for="recipient_ids_users">Chọn người dùng</label>
                            <select class="form-control select2" id="recipient_ids_users" name="recipient_ids[]" multiple="multiple">
                                @if(old('recipient_type', $notification->recipient_type) == 'specific_users' && ($notification->recipient_data || old('recipient_ids')))
                                    @foreach(App\Models\User::whereIn('id', old('recipient_ids', $notification->recipient_data ?? []))->get() as $user)
                                        <option value="{{ $user->id }}" selected>{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Phần chọn vai trò --}}
                        <div class="form-group" id="roles_div" style="display: {{ old('recipient_type', $notification->recipient_type) == 'roles' ? 'block' : 'none' }};">
                            <label for="recipient_ids_roles">Chọn vai trò</label>
                            <select class="form-control select2" id="recipient_ids_roles" name="recipient_ids[]" multiple="multiple">
                                @if(old('recipient_type', $notification->recipient_type) == 'roles' && ($notification->recipient_data || old('recipient_ids')))
                                    {{-- Nếu bạn dùng Spatie Laravel Permission, bạn sẽ load Role::whereIn --}}
                                    {{-- @foreach(Spatie\Permission\Models\Role::whereIn('name', old('recipient_ids', $notification->recipient_data ?? []))->get() as $role)
                                        <option value="{{ $role->name }}" selected>{{ ucfirst($role->name) }}</option>
                                    @endforeach --}}
                                    {{-- Nếu bạn quản lý vai trò bằng cột 'role' đơn giản: --}}
                                    @foreach(old('recipient_ids', $notification->recipient_data ?? []) as $roleName)
                                        <option value="{{ $roleName }}" selected>{{ ucfirst($roleName) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="scheduled_at">Thời gian lên lịch gửi</label>
                            <input type="text" class="form-control pickatime-format" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at', $notification->scheduled_at ? $notification->scheduled_at->format('Y-m-d H:i') : '') }}">
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Cập nhật thông báo</button>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary waves-effect waves-light">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Hàm hiển thị/ẩn các lựa chọn người nhận
        function toggleRecipientOptions() {
            var recipientType = document.getElementById('recipient_type').value;
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

        function setupSelect2Users() {
            $('#recipient_ids_users').select2({
                placeholder: 'Tìm kiếm người dùng...',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('admin.notifications.getUsers') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { search: params.term };
                    },
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });
        }

        function setupSelect2Roles() {
            $('#recipient_ids_roles').select2({
                placeholder: 'Chọn vai trò...',
                ajax: {
                    url: '{{ route('admin.notifications.getRoles') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { search: params.term };
                    },
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });
        }

        $(document).ready(function() {
            toggleRecipientOptions();

            // Khởi tạo TinyMCE
            tinymce.init({
                selector: '#content',
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table paste emoticons template codesample directionality',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                height: 300,
                menubar: 'file edit view insert format tools table help',
                forced_root_block: false,
            });

            // Khởi tạo Flatpickr
            $('.pickatime-format').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "d/m/Y H:i",
                time_24hr: true
            });
        });
    </script>
@endpush

