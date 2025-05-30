@extends('admin.dashboard')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Chi tiết người dùng</h5>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">Quay lại danh sách</a>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Avatar -->
                <div class="col-md-3 text-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="text-muted">Chưa có ảnh</div>
                    @endif
                </div>

                <!-- Thông tin -->
                <div class="col-md-9">
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th>Họ tên</th>
                            <td>{{ $user->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Điện thoại</th>
                            <td>{{ $user->phone }}</td>
                        </tr>
                        <tr>
                            <th>Giới tính</th>
                            <td>
                                @if($user->gender === 'male') Nam
                                @elseif($user->gender === 'female') Nữ
                                @else Khác
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày sinh</th>
                            <td>{{ \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Địa chỉ</th>
                            <td>{{ $user->address }}</td>
                        </tr>
                        <tr>
                            <th>Vai trò</th>
                            <td>{{ $user->role->name ?? 'Chưa phân quyền' }}</td>
                        </tr>
                        <tr>
                            <th>Ngày tạo</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật</th>
                            <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
