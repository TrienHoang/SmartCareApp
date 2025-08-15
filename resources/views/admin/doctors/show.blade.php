@extends('admin.dashboard')

@section('content')
<div class="container my-4">
    <h3 class="mb-4">Chi tiết bác sĩ</h3>

    <div class="card">
        <div class="card-body">
            {{-- Ảnh đại diện --}}
            <div class="mb-3 text-center">
                @if($doctor->user->avatar)
                    <img src="{{ asset('storage/' . $doctor->user->avatar) }}" 
                         alt="Avatar" class="rounded-circle" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/150" 
                         alt="No Avatar" class="rounded-circle">
                @endif
            </div>

            {{-- Thông tin cá nhân --}}
            <table class="table table-bordered">
                <tr>
                    <th>Họ và tên</th>
                    <td>{{ $doctor->user->full_name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $doctor->user->email }}</td>
                </tr>
                <tr>
                    <th>Phòng ban</th>
                    <td>{{ $doctor->department->name ?? 'Chưa có phòng ban' }}</td>
                </tr>

                <tr>
                    <th>Tiểu sử</th>
                    <td>{{ $doctor->biography ?? 'Chưa có' }}</td>
                </tr>
                <tr>
                    <th>Dịch vụ</th>
                    <td>
                        @if($doctor->services->count() > 0)
                            @foreach($doctor->services as $service)
                                <span class="badge bg-info text-dark">{{ $service->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Chưa có dịch vụ</span>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="mt-3 text-end">
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
