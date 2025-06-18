@extends('admin.dashboard')


@section('title', 'Tạo Thông báo mới')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Tạo Thông báo mới</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="me-1"><a href="{{ route('admin.dashboard') }}">Trang chủ ></a></li>
                                <li class="me-1"><a href="{{ route('admin.notifications.index') }}">Thông báo ></a></li>
                                <li class="breadcrumb-item active">Tạo mới</li>
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

                    <form action="{{ route('admin.notifications.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="content">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="8" >{{ old('content') }}</textarea>
                            {{-- <small class="form-text text-muted">Bạn có thể sử dụng HTML cơ bản hoặc Markdown để định dạng nội dung.</small> --}}
                        </div>
                        <div class="form-group mb-3">
                            <label for="type">Loại thông báo <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                @foreach ($notificationTypes as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="recipient_type">Gửi đến <span class="text-danger">*</span></label>
                            <select class="form-control" id="recipient_type" name="recipient_type" required
                                onchange="toggleRecipientOptions()">
                                <option value="all" {{ old('recipient_type') == 'all' ? 'selected' : '' }}>Tất cả người
                                    dùng</option>
                                <option value="specific_users"
                                    {{ old('recipient_type') == 'specific_users' ? 'selected' : '' }}>Người dùng cụ thể
                                </option>
                                <option value="roles" {{ old('recipient_type') == 'roles' ? 'selected' : '' }}>Theo vai
                                    trò</option>
                                {{-- <option value="by_condition" {{ old('recipient_type') == 'by_condition' ? 'selected' : '' }}>Theo điều kiện</option> --}}
                            </select>
                        </div>

                        {{-- Phần chọn người dùng cụ thể --}}
                        <div class="form-group mb-3" id="specific_users_div"
                            style="display: {{ old('recipient_type') == 'specific_users' ? 'block' : 'none' }};">
                            <label for="recipient_ids_users">Chọn người dùng</label>
                            <select class="form-control select2" id="recipient_ids_users" name="recipient_ids[]"
                                multiple="multiple">
                                {{-- Options sẽ được load qua AJAX bởi Select2 --}}
                                @if (old('recipient_type') == 'specific_users' && old('recipient_ids'))
                                    @foreach (App\Models\User::whereIn('id', old('recipient_ids'))->get() as $user)
                                        <option value="{{ $user->id }}" selected>{{ $user->name }}
                                            ({{ $user->email }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="form-text text-muted">Tìm kiếm và chọn một hoặc nhiều người dùng.</small>
                        </div>

                        {{-- Phần chọn vai trò --}}
                        <div class="form-group mb-3" id="roles_div"
                            style="display: {{ old('recipient_type') == 'roles' ? 'block' : 'none' }};">
                            <label for="recipient_ids_roles">Chọn vai trò</label>
                            <select class="form-control select2" id="recipient_ids_roles" name="recipient_ids[]"
                                multiple="multiple">
                                @if (old('recipient_type') == 'roles' && old('recipient_ids'))
                                    @foreach (App\Models\Role::whereIn('name', old('recipient_ids'))->get() as $role)
                                        <option value="{{ $role->id }}" selected>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="form-text text-muted">Chọn một hoặc nhiều vai trò (Bác sĩ, Bệnh nhân).</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="scheduled_at">Thời gian lên lịch gửi</label>
                            <input type="text" class="form-control pickatime-format" id="scheduled_at"
                                name="scheduled_at"
                                value="{{ old('scheduled_at') ? \Carbon\Carbon::parse(old('scheduled_at'))->format('Y-m-d H:i') : '' }}">
                            <small class="form-text text-muted">Để trống để gửi ngay lập tức. Chọn ngày và giờ để lên
                                lịch.</small>
                        </div>

                        <div class="form-group mb-3">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" id="send_now_checkbox" name="send_now_checkbox" value="1"
                                    {{ old('send_now_checkbox') ? 'checked' : '' }}>
                                <label for="send_now_checkbox">Gửi ngay sau khi tạo (Nếu không có lịch trình)</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Tạo thông báo</button>
                        <a href="{{ route('admin.notifications.index') }}"
                            class="btn btn-secondary waves-effect waves-light">Hủy</a>
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
                // Khởi tạo Select2 nếu chưa được khởi tạo
                if (!$('#recipient_ids_users').data('select2')) {
                    setupSelect2Users();
                }
            } else if (recipientType === 'roles') {
                document.getElementById('roles_div').style.display = 'block';
                // Khởi tạo Select2 nếu chưa được khởi tạo
                if (!$('#recipient_ids_roles').data('select2')) {
                    setupSelect2Roles();
                }
            }
        }

        // Khởi tạo Select2 cho người dùng
        function setupSelect2Users() {
            $('#recipient_ids_users').select2({
                placeholder: 'Tìm kiếm người dùng...',
                minimumInputLength: 2, // Bắt đầu tìm kiếm sau 2 ký tự
                ajax: {
                    url: '{{ route('admin.notifications.getUsers') }}', // Route API lấy người dùng
                    dataType: 'json',
                    delay: 250, // Độ trễ giữa các lần gõ phím
                    data: function(params) {
                        return {
                            search: params.term // Tham số tìm kiếm
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results // Frest cần format results: [{id: 1, text: 'abc'}]
                        };
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
                    url: '{{ route('admin.notifications.getRoles') }}', // Route API lấy vai trò
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            });
        }

        $(document).ready(function() {
            toggleRecipientOptions(); // Gọi khi trang tải để xử lý old input và hiển thị đúng phần

            // Khởi tạo TinyMCE cho trường content
            tinymce.init({
                selector: '#content',
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table paste emoticons template codesample directionality',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                height: 300,
                menubar: 'file edit view insert format tools table help',
                forced_root_block: false,
            });


            $('.pickatime-format').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "d/m/Y H:i",
                time_24hr: true
            });

            // Xử lý logic checkbox "Gửi ngay"
            $('#scheduled_at').on('change', function() {
                if ($(this).val()) { // Nếu có giá trị ngày giờ, bỏ chọn "Gửi ngay"
                    $('#send_now_checkbox').prop('checked', false);
                }
            });
            $('#send_now_checkbox').on('change', function() {
                if ($(this).is(':checked')) { // Nếu chọn "Gửi ngay", xóa giá trị lịch trình
                    $('#scheduled_at').val('').flatpickr().clear();
                }
            });
        });
    </script>
@endpush
