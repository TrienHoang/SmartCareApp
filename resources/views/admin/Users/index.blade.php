@extends('admin.dashboard') {{-- Kế thừa từ layout chính --}}

@section('title', 'Quản lý người dùng') {{-- Định nghĩa tiêu đề cho trang này --}}

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    {{-- Thanh tiêu đề và tìm kiếm --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex align-items-center mb-3">
            <div class="icon-circle bg-primary me-3 "> {{-- me-3 thay cho mr-3 trong Bootstrap 5 --}}
                <i class="bx bx-user-circle text-white "></i>
            </div>
            <div>
                <h2 class="content-header-title mb-0 text-primary fw-bold">Danh sách người dùng</h2> {{-- fw-bold thay cho font-weight-bold --}}
                <p class="text-muted mb-0">Quản lý và theo dõi thông tin người dùng trong hệ thống</p>
            </div>
        </div>

        <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
            <select name="role_id" class="form-select" onchange="this.form.submit()"> {{-- form-select cho Bootstrap 5 --}}
                <option value="all">-- Tất cả vai trò --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary"><i class="bx bx-search"></i> Tìm</button>
        </form>
    </div>

    {{-- Thông báo --}}
    @foreach (['success', 'error'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                {{-- Nút đóng cho Bootstrap 5 --}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    {{-- Bảng người dùng --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle text-center mb-0">
                    <thead class="table-light text-dark">
                        <tr>
                            <th>STT</th>
                            <th>Tài khoản</th>
                            <th>Avatar</th>
                            <th>Họ tên</th>
                            <th>Giới tính</th>
                            <th>SĐT</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>{{ $user->username }}</td>
                            <td>
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle shadow" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                        <i class="bx bx-user text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-start">
                                <div class="fw-bold">{{ $user->full_name }}</div>
                                <small class="text-muted">{{ $user->username }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ strtolower($user->gender) == 'nam' ? 'primary' : 'warning' }}"> {{-- bg- thay cho badge- trong Bootstrap 5 --}}
                                    {{ ucfirst($user->gender) }}
                                </span>
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <span class="badge bg-success"> {{-- bg- thay cho badge- trong Bootstrap 5 --}}
                                    {{ $user->role->name ?? 'Chưa có' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->status === 'online' ? 'btn-success' : 'btn-outline-secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Chi tiết"> {{-- data-bs-toggle cho Bootstrap 5 --}}
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Chỉnh sửa"> {{-- data-bs-toggle cho Bootstrap 5 --}}
                                    <i class="bx bx-edit"></i>
                                </a>
                                {{-- Nút xóa có thể dùng SweetAlert2 như trong ví dụ thông báo --}}
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Xóa" onclick="deleteUser({{ $user->id }})"> {{-- data-bs-toggle cho Bootstrap 5 --}}
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-muted text-center py-4">Không có người dùng nào được tìm thấy.</td> {{-- Cập nhật colspan --}}
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-end">
            {{-- Đổi pagination::bootstrap-4 thành pagination::bootstrap-5 cho tương thích --}}
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Hàm xóa người dùng (tương tự như xóa thông báo)
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
                form.action = `/admin/users/${id}`; // Đảm bảo đúng route API

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

    // Các script khác
    $(document).ready(function() {
        // Khởi tạo tooltips cho Bootstrap 5
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Tùy chọn: Tự động ẩn thông báo sau 5 giây
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endpush
