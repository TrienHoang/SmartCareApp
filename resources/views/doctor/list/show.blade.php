@extends('doctor.dashboard')

@section('title', 'Chi tiết bác sĩ')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top d-flex justify-content-between">
                    <h4 class="mb-0 fw-bold"><i class="bx bx-user me-2"></i> Thông tin chi tiết bác sĩ</h4>
                    <a href="{{ route('doctor.index') }}" class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i> Quay lại danh sách
                    </a>
                </div>

                <div class="card-body p-4">
                    {{-- Ảnh + Tên --}}
                    <div class="row g-4 align-items-center mb-4">
                        <div class="col-md-3 text-center">
                            <img src="{{ $doctor->user->avatar_url ?? asset('images/default-avatar.png') }}"
                                 class="img-thumbnail rounded-circle shadow" width="150" height="150" alt="Avatar">
                        </div>
                        <div class="col-md-9">
                            <h3 class="fw-bold text-primary mb-1">{{ $doctor->user->name }}</h3>
                            <p class="mb-1"><strong>ID Bác sĩ:</strong> #{{ $doctor->id }}</p>
                            <p class="mb-1"><strong>Username:</strong> {{ $doctor->user->username }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $doctor->user->email }}</p>
                            <p class="mb-1"><strong>Số điện thoại:</strong> {{ $doctor->user->phone ?? '—' }}</p>
                        </div>
                    </div>

                    <hr>

                    {{-- Thông tin cá nhân --}}
                    <div class="row g-4">
                        <div class="col-md-4">
                            <p><strong>Giới tính:</strong> {{ ucfirst($doctor->user->gender ?? '—') }}</p>
                            <p><strong>Ngày sinh:</strong> {{ $doctor->user->date_of_birth ?? '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Địa chỉ:</strong> {{ $doctor->user->address ?? '—' }}</p>
                            <p><strong>Chuyên môn:</strong> {{ $doctor->specialization }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Khoa:</strong> {{ $doctor->department->name ?? '—' }}</p>
                            <p><strong>Phòng:</strong> {{ $doctor->room->name ?? '—' }}</p>
                        </div>
                    </div>

                    <hr>

                    {{-- Tiểu sử --}}
                    <div class="mt-4">
                        <h5 class="fw-bold"><i class="bx bx-book-content me-1 text-secondary"></i> Tiểu sử bác sĩ</h5>
                        <p class="text-muted">{{ $doctor->biography ?? 'Không có tiểu sử.' }}</p>
                    </div>

                    <hr>

                    {{-- Thông tin hệ thống --}}
                    <div class="row g-4">
                        <div class="col-md-6">
                            <p><strong>Ngày tạo tài khoản:</strong> {{ $doctor->user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Cập nhật lần cuối:</strong> {{ $doctor->user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
