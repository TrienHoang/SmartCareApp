@extends('admin.dashboard')
@section('title', 'Chi tiết ca làm việc')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i data-feather="calendar" class="me-2"></i>
                <h5 class="mb-0">Ca làm việc của bác sĩ: <strong>{{ $schedule->doctor->user->full_name ?? 'Không rõ tên' }}</strong></h5>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    @if($schedule->doctor->user->avatar)
                        <img src="{{ Storage::url($schedule->doctor->user->avatar) }}" class="img-thumbnail rounded-circle shadow-sm" style="width: 100px; height: 100px;" alt="Avatar">
                    @else
                        <div class="bg-light border rounded-circle d-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                            <i data-feather="user" class="text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">ID:</dt>
                        <dd class="col-sm-8">{{ $schedule->id }}</dd>

                        <dt class="col-sm-4">Ngày làm việc:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') }} ({{ $schedule->day_of_week }})</dd>

                        <dt class="col-sm-4">Giờ bắt đầu:</dt>
                        <dd class="col-sm-8">{{ $schedule->start_time }}</dd>

                        <dt class="col-sm-4">Giờ kết thúc:</dt>
                        <dd class="col-sm-8">{{ $schedule->end_time }}</dd>

                        <dt class="col-sm-4">Ghi chú:</dt>
                        <dd class="col-sm-8">{{ $schedule->note ?? 'Không có ghi chú' }}</dd>

                        <dt class="col-sm-4">Trạng thái:</dt>
                        <dd class="col-sm-8">
                            @php
                                $badge = 'bg-info';
                                if ($schedule->status === 'active') $badge = 'bg-success';
                                elseif ($schedule->status === 'inactive') $badge = 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badge }} px-3 py-2 text-capitalize" data-bs-toggle="tooltip" title="Trạng thái hiện tại">
                                {{ $schedule->status ?? 'Không xác định' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-primary">
                    <i data-feather="arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) {
            new bootstrap.Tooltip(el);
        });
    });
</script>
@endsection
