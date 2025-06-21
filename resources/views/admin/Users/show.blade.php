@extends('admin.dashboard')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-user me-2"></i> Thông tin người dùng</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                        <i class="bx bx-arrow-back"></i> Quay lại
                    </a>
                </div>

                <div class="card-body px-4 py-5">
                    <div class="row g-4">
                        <!-- Avatar và Tên -->
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}"
                                     class="rounded-circle border border-3 border-primary shadow-sm mb-3"
                                     style="width: 160px; height: 160px; object-fit: cover;" alt="Avatar">
                            @else
                                <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center mb-3"
                                     style="width: 160px; height: 160px;">
                                    <i class="bx bx-user text-muted fs-1"></i>
                                </div>
                                <p class="text-muted">Chưa có ảnh</p>
                            @endif

                            <h5 class="fw-bold text-center mt-2 mb-1">{{ $user->full_name }}</h5>
                            <span class="text-muted text-center">{{ $user->role->name ?? 'Chưa phân quyền' }}</span>
                        </div>

                        <!-- Thông tin -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tên đăng nhập</label>
                                    <div class="form-control-plaintext">{{ $user->username }}</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <div class="form-control-plaintext">{{ $user->email }}</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Số điện thoại</label>
                                    <div class="form-control-plaintext">{{ $user->phone }}</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Giới tính</label>
                                    <div class="form-control-plaintext">
                                        @switch($user->gender)
                                            @case('male') Nam @break
                                            @case('female') Nữ @break
                                            @default Khác
                                        @endswitch
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Ngày sinh</label>
                                    <div class="form-control-plaintext">{{ \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') }}</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Địa chỉ</label>
                                    <div class="form-control-plaintext">{{ $user->address }}</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Ngày tạo</label>
                                    <div class="form-control-plaintext">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Cập nhật lần cuối</label>
                                    <div class="form-control-plaintext">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection
