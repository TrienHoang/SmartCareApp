@extends('admin.dashboard')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    {{-- Thanh tiêu đề và tìm kiếm --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <h4 class="mb-0"><i class="bx bx-user-circle"></i> Danh sách người dùng</h4>

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
            <button type="submit" class="btn btn-primary"><i class="bx bx-search"></i> Tìm</button>
        </form>
    </div>

    {{-- Thông báo --}}
    @foreach (['success', 'error'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                <span class="badge bg-{{ strtolower($user->gender) == 'nam' ? 'primary' : 'warning' }}">
                                    {{ ucfirst($user->gender) }}
                                </span>
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $user->role->name ?? 'Chưa có' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm {{ $user->status === 'online' ? 'btn-success' : 'btn-outline-secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info" title="Chi tiết">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                    <i class="bx bx-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-muted text-center py-4">Không có người dùng nào được tìm thấy.</td>
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
