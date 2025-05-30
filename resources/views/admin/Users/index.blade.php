@extends('admin.dashboard')
@section('title', 'Danh sách người dùng')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Users /</span> Tài khoản người dùng
        </h4>
        
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách người dùng</h5>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger m-3">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
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
                        <tbody class="table-border-bottom-0">
                            @forelse($users as $user)
                                <tr>
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
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                                class="rounded-circle"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <span class="text-muted">Chưa có ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                            <i class="bx bx-show me-1"></i> Xem
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="btn btn-sm btn-warning me-1">
                                            <i class="bx bx-edit-alt me-1"></i> Sửa
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-muted">Không có người dùng nào.</td>
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
    </div>

@endsection
