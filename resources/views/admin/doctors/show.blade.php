@extends('admin.dashboard')

@section('content')
<div class="container my-4">
    <h3 class="mb-4">Chi tiết bác sĩ</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                {{-- Ảnh đại diện --}}
                <div class="col-md-3 text-center mb-3">
                    @if($doctor->user && $doctor->user->avatar)
                        <img src="{{ asset('storage/' . $doctor->user->avatar) }}" 
                             alt="Avatar" class="img-thumbnail rounded-circle" width="150">
                    @else
                        <img src="https://via.placeholder.com/150" 
                             alt="Avatar" class="img-thumbnail rounded-circle">
                    @endif
                </div>

                {{-- Thông tin cơ bản --}}
                <div class="col-md-9">
                    <h4 class="fw-bold mb-3">{{ $doctor->user->full_name ?? 'Không rõ tên' }}</h4>
                    <p><strong>Email:</strong> {{ $doctor->user->email ?? 'N/A' }}</p>
                    <p><strong>Tên đăng nhập:</strong> {{ $doctor->user->username ?? 'N/A' }}</p>
                    <p><strong>Phòng ban:</strong> {{ $doctor->department->name ?? 'Chưa phân công' }}</p>
                    <p><strong>Trạng thái:</strong> 
                        <span class="badge bg-{{ $doctor->user->status === 'online' ? 'success' : 'secondary' }}">
                            {{ ucfirst($doctor->user->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <hr>

            {{-- Tiểu sử --}}
            <div class="mt-3">
                <h5 class="fw-semibold">Tiểu sử</h5>
                <p>{{ $doctor->biography ?: 'Chưa có thông tin tiểu sử.' }}</p>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning me-2">Chỉnh sửa</a>
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</div>
@endsection