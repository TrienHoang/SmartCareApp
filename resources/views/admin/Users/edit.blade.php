@extends('admin.dashboard')

@section('title', 'Chỉnh sửa quyền người dùng')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 p-4">
            <h2 class="mb-0">Cập nhật quyền cho: {{ $user->full_name }}</h2>
        </div>
        <div class="card-body p-5 bg-light">
            {{-- Layout chính: Avatar trái + Thông tin + form phải --}}
            <div class="row g-4">
                {{-- Avatar --}}
                <div class="col-md-3 d-flex justify-content-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                             class="rounded-circle border border-3 border-primary shadow-sm"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white shadow-sm"
                             style="width: 150px; height: 150px;">
                            <span>Chưa có ảnh</span>
                        </div>
                    @endif
                </div>

                {{-- Thông tin + Form --}}
                <div class="col-md-9">
                    {{-- Thông tin cá nhân + hệ thống --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="bg-white p-4 rounded-3 shadow-sm h-100">
                                <h6 class="text-uppercase text-primary fw-bold mb-3">Thông tin cá nhân</h6>
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Họ tên:</strong> {{ $user->full_name }}</li>
                                    <li><strong>Email:</strong> {{ $user->email }}</li>
                                    <li><strong>Điện thoại:</strong> {{ $user->phone }}</li>
                                    <li><strong>Giới tính:</strong> {{ $user->gender }}</li>
                                    <li><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-white p-4 rounded-3 shadow-sm h-100">
                                <h6 class="text-uppercase text-primary fw-bold mb-3">Thông tin hệ thống</h6>
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Địa chỉ:</strong> {{ $user->address }}</li>
                                    <li><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
                                    <li><strong>Cập nhật gần nhất:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Form cập nhật quyền --}}
                    <div class="bg-white p-4 rounded-4 shadow-sm">
                        <h4 class="text-center mb-4 text-uppercase text-muted">Phân quyền người dùng</h4>
                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="row g-3 align-items-center">
                            @csrf
                            @method('PUT')

                            <div class="col-12">
                                <label for="role_id" class="form-label fw-semibold">Chọn quyền</label>
                                <select name="role_id" id="role_id" class="form-select form-select-lg">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex justify-content-center gap-3 mt-4 pt-2 border-top border-gray-300">
                                <button type="submit" class="btn btn-success btn-lg px-5 py-3 rounded-3 shadow-sm">Cập nhật</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-lg px-5 py-3 rounded-3 shadow-sm">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
