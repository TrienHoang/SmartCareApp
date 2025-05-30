@extends('admin.dashboard')

@section('title', 'Chỉnh sửa quyền')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">Chỉnh sửa quyền người dùng: {{ $user->full_name }}</div>
        <div class="card-body">
            {{-- Thông tin người dùng --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Họ tên:</strong> {{ $user->full_name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Điện thoại:</strong> {{ $user->phone }}</p>
                    <p><strong>Giới tính:</strong> {{ $user->gender }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $user->address }}</p>
                    <p><strong>Ngày tạo tài khoản:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Cập nhật lần cuối:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            {{-- Ảnh đại diện --}}
            <div class="mb-4 text-center">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <span class="text-muted">Chưa có ảnh đại diện</span>
                @endif
            </div>

            {{-- Form cập nhật quyền --}}
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="role_id" class="form-label">Quyền</label>
                    <select name="role_id" id="role_id" class="form-select">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection
