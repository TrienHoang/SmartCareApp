@extends('admin.dashboard')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top align-items-center">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bx bx-user me-2 text-primary" style="font-size: 24px;"></i>
                        <h2 class="content-header-title mb-0">Chi tiết người dùng</h2>
                    </div>
                    <div class="breadcrumb-wrapper d-flex align-items-center" style="min-height: 32px;">
                        <nav aria-label="breadcrumb" class="w-100">
                            <ol class="breadcrumb mb-0">
                                <li class="">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                        Trang chủ >
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                                        Người dùng >
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Chi tiết</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-body">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bx bx-user me-2"></i> Thông tin người dùng</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                            <i class="bx bx-arrow-back"></i> Quay lại
                        </a>
                    </div>
                    <div class="card-body px-4 py-5">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-4 d-flex flex-column align-items-center justify-content-center border-end" style="min-height: 260px;">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                         class="rounded-circle border border-3 border-primary shadow-sm mb-3"
                                         style="width: 140px; height: 140px; object-fit: cover;" alt="Avatar">
                                @else
                                    <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center mb-3"
                                         style="width: 140px; height: 140px;">
                                        <i class="bx bx-user text-muted fs-1"></i>
                                    </div>
                                    <p class="text-muted mb-0">Chưa có ảnh</p>
                                @endif

                                <h5 class="fw-bold text-center mt-2 mb-1">{{ $user->full_name }}</h5>
                                <span class="text-muted text-center">{{ $user->role->name ?? 'Chưa phân quyền' }}</span>
                            </div>
                            <div class="col-md-8 ps-md-5">
                                <div class="row gx-4 gy-2">
                                    <div class="col-sm-6 mb-3 d-flex align-items-center">
                                        <label class="form-label fw-semibold mb-0 me-2" style="min-width:120px;">Tên đăng nhập</label>
                                        <div class="form-control-plaintext mb-0">{{ $user->username }}</div>
                                    </div>
                                    <div class="col-sm-6 mb-3 d-flex align-items-center">
                                        <label class="form-label fw-semibold mb-0 me-2" style="min-width:80px;">Email</label>
                                        <div class="form-control-plaintext mb-0">{{ $user->email }}</div>
                                    </div>
                                    <div class="col-sm-6 mb-3 d-flex align-items-center">
                                        <label class="form-label fw-semibold mb-0 me-2" style="min-width:120px;">Số điện thoại</label>
                                        <div class="form-control-plaintext mb-0">{{ $user->phone }}</div>
                                    </div>
                                    <div class="col-sm-6 mb-3 d-flex align-items-center">
                                        <label class="form-label fw-semibold mb-0 me-2" style="min-width:80px;">Giới tính</label>
                                        <div class="form-control-plaintext mb-0">
                                            @switch($user->gender)
                                                @case('male') Nam @break
                                                @case('female') Nữ @break
                                                @default Khác
                                            @endswitch
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3 d-flex align-items-center">
                                        <label class="form-label fw-semibold mb-0 me-2" style="min-width:120px;">Ngày sinh</label>
                                        <div class="form-control-plaintext mb-0">
                                            {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : '' }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3 d-flex align-items-center">
                                        <label class="form-label fw-semibold mb-0 me-2" style="min-width:80px;">Địa chỉ</label>
                                        <div class="form-control-plaintext mb-0">{{ $user->address }}</div>
                                    </div>
                                    <div class="col-sm-6 mb-3 d-flex align-items-center">
                                        <label class="form-label fw-semibold mb-0 me-2" style="min-width:120px;">Ngày tạo</label>
                                        <div class="form-control-plaintext mb-0">
                                            {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '' }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3 d-flex align-items-center">
                                        <label class="form-label fw-semibold mb-0 me-2" style="min-width:120px;">Cập nhật lần cuối</label>
                                        <div class="form-control-plaintext mb-0">
                                            {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
    .breadcrumb-wrapper {
        /* Đảm bảo chiều cao và căn giữa dọc với tiêu đề */
        min-height: 32px;
        display: flex;
        align-items: center;
    }
    .content-header-title {
        line-height: 1.2;
    }
    .breadcrumb {
        margin-bottom: 0;
        background: transparent;
        padding-left: 0;
    }
    .breadcrumb li,
    .breadcrumb .breadcrumb-item {
        display: inline-block;
        vertical-align: middle;
    }
</style>
@endpush
@endsection
