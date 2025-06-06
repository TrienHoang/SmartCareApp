@extends('admin.dashboard')
@section('title', 'Danh sách người dùng')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Users /</span> Tài khoản người dùng
    </h4>

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <h5 class="mb-0">Danh sách người dùng</h5>
            <form action="{{ route('admin.users.search') }}" method="get" class="d-flex" style="max-width: 300px;">
                <input type="text" name="search" class="form-control me-2" placeholder="Nhập từ khóa..." required>
                <button type="submit" class="btn btn-sm btn-primary">Tìm</button>
            </form>
        </div>

        @if (session('error'))
            <div class="alert alert-danger mx-3 mt-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Tài khoản</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Giới tính</th>
                        <th>Địa chỉ</th>
                        <th>Vai trò</th>
                        <th>Ảnh đại diện</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="text-center">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <span class="badge bg-label-{{ $user->gender === 'Nam' ? 'primary' : 'warning' }}">
                                    {{ $user->gender }}
                                </span>
                            </td>
                            <td>{{ $user->address }}</td>
                            <td>
                                @if ($user->role)
                                    <span class="badge bg-label-success">{{ $user->role->name }}</span>
                                @else
                                    <span class="badge bg-label-secondary">Chưa phân quyền</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle border"
                                        style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <span class="text-muted">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info mb-1">
                                    <i class="bx bx-show me-1"></i> Xem
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit-alt me-1"></i> Sửa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Không có người dùng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-end">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
