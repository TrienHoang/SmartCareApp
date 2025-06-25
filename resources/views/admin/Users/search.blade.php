@extends('admin.dashboard')
@section('title', 'Tìm kiếm người dùng')
@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">

        {{-- Thông báo lỗi (đã tương thích Bootstrap 5) --}}
        @if(session('error'))
            <div class="alert alert-danger m-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive px-3">
            <table class="table table-bordered table-hover text-center align-middle">
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
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->gender }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->role->name ?? 'Chưa phân quyền' }}</td>
                            <td>
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="text-muted">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">Xem</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-muted">Không có người dùng nào phù hợp với tìm kiếm.</td>
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
