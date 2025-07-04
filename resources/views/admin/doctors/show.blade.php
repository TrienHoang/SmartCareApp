@extends('admin.dashboard')

@section('title', 'Chi tiết bác sĩ')

@section('content')
<style>
    .card-3d {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem;
    }

    .card-3d:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-3d {
        transition: all 0.2s ease-in-out;
        border-radius: 0.5rem;
    }

    .btn-3d:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .info-label {
        font-weight: 600;
        color: #0d6efd;
    }

    .info-value {
        font-weight: 500;
        color: #333;
    }

    .status-badge {
        font-weight: 600;
    }
</style>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card card-3d">
                <div class="card-header bg-primary text-white rounded-top">
                    <h4 class="mb-0">
                        👨‍⚕️ Chi tiết bác sĩ: {{ $doctor->user->full_name ?? 'Không rõ' }}
                    </h4>
                </div>

                <div class="card-body bg-light">
                    <div class="mb-3">
                        <span class="info-label">💼 Chuyên môn:</span>
                        <span class="info-value">{{ $doctor->specialization }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="info-label">🏥 Phòng ban:</span>
                        <span class="info-value">{{ $doctor->department->name ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="info-label">🏨 Phòng khám:</span>
                        <span class="info-value">{{ $doctor->room->name ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="info-label">📝 Tiểu sử:</span>
                        <div class="info-value">
                            {{ $doctor->biography ?? 'Không có' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="info-label">📅 Trạng thái hôm nay:</span>
                        @if ($doctor->isOnLeaveToday())
                            <span class="text-danger status-badge">Đang nghỉ</span>
                        @else
                            <span class="text-success status-badge">Đang làm việc</span>
                        @endif
                    </div>

                    <div class="text-center">
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-3d px-4">
                            ← Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
