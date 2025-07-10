@extends('admin.dashboard')

@section('title', 'Chi tiết phòng ban: ' . $department->name)

@section('content')
<div class="container py-4">

    {{-- Thông tin phòng ban --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-building-house mr-2"></i> Chi tiết phòng ban</h5>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-light btn-sm">
                <i class="bx bx-arrow-back"></i> Quay lại danh sách
            </a>
        </div>
        <div class="card-body">
            <h4 class="text-primary font-weight-bold">{{ $department->name }}</h4>
            <p><strong>Mô tả:</strong> {{ $department->description ?? 'Không có mô tả' }}</p>
            <p><strong>Trạng thái:</strong>
                @if ($department->is_active)
                    <span class="badge badge-success"><i class="bx bx-check-circle"></i> Hoạt động</span>
                @else
                    <span class="badge badge-danger"><i class="bx bx-block"></i> Ngưng hoạt động</span>
                @endif
            </p>
            <p><strong>Ngày tạo:</strong> {{ $department->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- Danh sách bác sĩ --}}
    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bx bx-user"></i> Danh sách bác sĩ</h6>
        </div>
        <div class="card-body p-0">
            @forelse ($department->doctors as $doctor)
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $doctor->user->full_name ?? 'Không rõ' }}</strong><br>
                            <small class="text-muted"><i class="bx bx-phone-call"></i> {{ $doctor->user->phone ?? 'N/A' }}</small>
                        </div>
                        <span class="badge badge-success">{{ $doctor->position ?? 'Bác sĩ' }}</span>
                    </div>
                </div>
            @empty
                <div class="p-3 text-muted">Phòng ban này chưa có bác sĩ nào.</div>
            @endforelse
        </div>
    </div>

{{-- Danh sách dịch vụ --}}
<div class="card mt-4 border-0 shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h6 class="mb-0"><i class="bx bx-list-ul"></i> Dịch vụ theo phòng ban</h6>
    </div>
    <div class="card-body p-0">
        @forelse ($department->services->where('status', 'active') as $service)
            <div class="list-group list-group-flush">
                <div class="list-group-item">
                    <strong>{{ $service->name }}</strong><br>
                    <small class="text-muted">{{ $service->description ?? 'Không có mô tả' }}</small><br>
                    <span class="text-primary">Giá: {{ number_format($service->price) }}đ</span> |
                    <span class="text-muted">Thời gian: {{ $service->duration }} phút</span>
                </div>
            </div>
        @empty
            <div class="p-3 text-muted">Không có dịch vụ nào đang hoạt động trong phòng ban này.</div>
        @endforelse
    </div>
</div>


    {{-- Danh sách phòng khám --}}
    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h6 class="mb-0"><i class="bx bx-door-open"></i> Danh sách phòng khám</h6>
        </div>
        <div class="card-body p-0">
            @forelse ($department->rooms as $room)
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <strong>{{ $room->name }}</strong><br>
                        <small class="text-muted">{{ $room->description ?? 'Không có mô tả' }}</small>
                    </div>
                </div>
            @empty
                <div class="p-3 text-muted">Chưa có phòng khám nào thuộc phòng ban này.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
