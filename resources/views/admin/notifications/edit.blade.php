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
                                <li class="me-1"><a href="{{ route('admin.dashboard') }}">Trang chủ ></a></li>
                                <li class="me-1"><a href="{{ route('admin.notifications.index') }}">Thông báo ></a></li>
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
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title', $notification->title) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="content">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="8">{{ old('content', $notification->content) }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="type">Loại thông báo <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                @foreach ($notificationTypes as $type)
                                    <option value="{{ $type }}"
                                        {{ old('type', $notification->type) == $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="recipient_type">Gửi đến <span class="text-danger">*</span></label>
                            <select class="form-control" id="recipient_type" name="recipient_type" required
                                onchange="toggleRecipientOptions()">
                                <option value="all"
                                    {{ old('recipient_type', $notification->recipient_type) == 'all' ? 'selected' : '' }}>
                                    Tất cả người dùng</option>
                                <option value="specific_users"
                                    {{ old('recipient_type', $notification->recipient_type) == 'specific_users' ? 'selected' : '' }}>
                                    Người dùng cụ thể</option>
                                <option value="roles"
                                    {{ old('recipient_type', $notification->recipient_type) == 'roles' ? 'selected' : '' }}>
                                    Theo vai trò</option>

                            </select>
                        </div>

                        {{-- Phần chọn người dùng cụ thể --}}
                        <div class="form-group mb-3" id="specific_users_div"
                            style="display: {{ old('recipient_type', $notification->recipient_type) == 'specific_users' ? 'block' : 'none' }};">
                            <label for="recipient_ids_users">Chọn người dùng</label>
                            <select class="form-control select2" id="recipient_ids_users" name="recipient_ids[]"
                                multiple="multiple">
                                {{-- Load người dùng đã chọn --}}
                                @if ($notification->recipient_type == 'specific_users' && !empty($notification->recipient_ids))
                                    @php
                                        $selectedUserIds = is_array($notification->recipient_ids)
                                            ? $notification->recipient_ids
                                            : json_decode($notification->recipient_ids, true);
                                        $selectedUsers = App\Models\User::whereIn('id', $selectedUserIds)->get();
                                    @endphp
                                    @foreach ($selectedUsers as $user)
                                        <option value="{{ $user->id }}" selected>{{ $user->name }}
                                            ({{ $user->email }})
                                        </option>
                                    @endforeach
                                @elseif (old('recipient_type') == 'specific_users' && old('recipient_ids'))
                                    {{-- Nếu có lỗi validation và recipient_type là specific_users, giữ lại các lựa chọn cũ --}}
                                    @php
                                        $oldSelectedUserIds = old('recipient_ids');
                                        $oldSelectedUsers = App\Models\User::whereIn('id', $oldSelectedUserIds)->get();
                                    @endphp
                                    @foreach ($oldSelectedUsers as $user)
                                        <option value="{{ $user->id }}" selected>{{ $user->name }}
                                            ({{ $user->email }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Phần chọn vai trò --}}
                        <div class="form-group mb-3" id="roles_div"
                            style="display: {{ old('recipient_type', $notification->recipient_type) == 'roles' ? 'block' : 'none' }};">
                            <label for="recipient_ids_roles">Chọn vai trò</label>
                            <select class="form-control select2" id="recipient_ids_roles" name="recipient_ids[]"
                                multiple="multiple">
                                {{-- Load vai trò đã chọn --}}
                                @if ($notification->recipient_type == 'roles' && !empty($notification->recipient_ids))
                                    @php
                                        // Đảm bảo $notification->recipient_ids là một mảng và không rỗng
                                        $selectedRoleNames = is_array($notification->recipient_ids)
                                            ? $notification->recipient_ids
                                            : json_decode($notification->recipient_ids, true);
                                        // Lấy các vai trò đã chọn dựa trên tên
                                        $selectedRoles = App\Models\Role::whereIn('id', $selectedRoleNames)->get(); // Sử dụng model của Spatie
                                    @endphp
                                    @foreach ($selectedRoles as $role)
                                        <option value="{{ $role->id }}" selected>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                @elseif (old('recipient_type') == 'roles' && old('recipient_ids'))
                                    {{-- Khi có lỗi validation, giữ lại các lựa chọn cũ từ old() --}}
                                    @php
                                        // Giả sử old('recipient_ids') trả về id của roles nếu bạn thay đổi cách lưu
                                        // Hoặc là tên roles nếu bạn đã điều chỉnh controller để lưu tên
                                        $oldSelectedRoleValues = old('recipient_ids');
                                        // Cần xác định $oldSelectedRoleValues là ID hay NAME để query đúng
                                        // Tạm thời giả định là NAME dựa trên ví dụ của bạn
                                        $oldSelectedRoles = App\Models\Role::whereIn(
                                            'name',
                                            $oldSelectedRoleValues,
                                        )->get();
                                    @endphp
                                    @foreach ($oldSelectedRoles as $role)
                                        <option value="{{ $role->name }}" selected>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>


                        <div class="form-group mb-3">
                            <label for="scheduled_at">Thời gian lên lịch gửi</label>
                            <input type="text" class="form-control pickatime-format" id="scheduled_at"
                                name="scheduled_at"
                                value="{{ old('scheduled_at', $notification->scheduled_at ? $notification->scheduled_at->format('Y-m-d H:i') : '') }}">
                            @error('scheduled_at')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Cập nhật thông
                            báo</button>
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
                            // Thêm các ID người dùng đã chọn để loại trừ khỏi kết quả tìm kiếm
                            selected_ids: $('#recipient_ids_users').val()
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

        function setupSelect2Roles() {
            // Lấy các giá trị đã chọn ban đầu từ HTML (những option có selected)
            // và chuẩn hóa chúng thành mảng string của tên vai trò (ví dụ: ["admin", "doctor"])
            var initialSelectedRoles = $('#recipient_ids_roles option:selected').map(function() {
                return $(this).val();
            }).get();

            $('#recipient_ids_roles').select2({
                placeholder: 'Chọn vai trò...',
                ajax: {
                    url: '{{ route('admin.notifications.getRoles') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            // Truyền các tên vai trò đã được chọn để loại trừ khỏi kết quả tìm kiếm
                            // Select2.val() sẽ trả về mảng các value của các option đã chọn
                            selected_names: $('#recipient_ids_roles').val()
                        };
                    },
                    processResults: function(data) {
                        // Lọc bỏ các vai trò đã chọn khỏi kết quả tìm kiếm
                        var selectedValues = $('#recipient_ids_roles').val() || [];
                        var filteredResults = data.results.filter(function(role) {
                            return selectedValues.indexOf(role.id) === -
                            1; // Giả sử AJAX trả về {id: 'role_name', text: 'Role Name'}
                        });
                        return {
                            results: filteredResults
                        };
                    },
                    cache: true
                }
            });

            // Sau khi Select2 được khởi tạo, nếu có các giá trị đã chọn ban đầu từ blade,
            // chúng ta cần thiết lập chúng cho Select2.
            // Điều này thường không cần thiết nếu các option đã có selected="selected"
            // và Select2 được khởi tạo đúng cách. Tuy nhiên, nếu vẫn gặp lỗi,
            // bạn có thể thử thiết lập lại:
            if (initialSelectedRoles.length > 0) {
                $('#recipient_ids_roles').val(initialSelectedRoles).trigger('change');
            }
        }

        $(document).ready(function() {
            // Khởi tạo Select2 cho cả hai trường ngay khi tải trang
            // Điều này đảm bảo Select2 được áp dụng đúng cách và có thể nhận diện các option "selected" ban đầu.
            setupSelect2Users();
            setupSelect2Roles();

            // Gọi hàm này để hiển thị/ẩn div tương ứng dựa trên giá trị đã chọn
            toggleRecipientOptions();

            // Khởi tạo TinyMCE
            tinymce.init({
                selector: '#content',
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table paste emoticons template codesample directionality',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft align alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                height: 300,
                menubar: 'file edit view insert format tools table help',
                forced_root_block: false,
            });

            // Khởi tạo Flatpickr
            $('.pickatime-format').flatpickr({
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                altInput: true,
                altFormat: 'd/m/Y H:i',
                time_24hr: true,
                minDate: 'today', // Giới hạn chọn từ thời điểm hiện tại
                minuteIncrement: 1, // Bước nhảy phút
                defaultDate: '{{ old('scheduled_at') ? \Carbon\Carbon::parse(old('scheduled_at'))->format('Y-m-d H:i') : '' }}'
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
                        alert('Thời gian lên lịch phải từ hiện tại trở đi.');
                        $('#scheduled_at').focus();
                    }
                }
            });
        });
    </script>
@endpush
