@extends('admin.dashboard')
@section('title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">📋 Quản lý người dùng</h4>
        <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
            <select name="role_id" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Tất cả vai trò --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tài khoản</th>
                            <th>Họ tên</th>
                            <th>Giới tính</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>
                                <span class="badge bg-{{ $user->gender === 'Nam' ? 'primary' : 'warning' }}">
                                    {{ $user->gender }}
                                </span>
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <span class="badge bg-success">{{ $user->role->name ?? 'Chưa có' }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm {{ $user->status === 'online' ? 'btn-success' : 'btn-secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info" title="Xem">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Sửa">
                                    <i class="bx bx-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Không tìm thấy người dùng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-end">
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
