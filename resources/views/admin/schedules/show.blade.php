@extends('admin.dashboard')
@section('title', 'Schedule Detail')
@section('content')
<div class="container mt-4">
    <h2>Chi tiết ca làm bác sĩ</h2>
    <div class="card mt-3">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $schedule->id }}</dd>

                <dt class="col-sm-3">Tên bác sĩ</dt>
                <dd class="col-sm-9">{{ $schedule->doctor->user->full_name ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Ngày trong tuần</dt>
                <dd class="col-sm-9">{{ $schedule->day_of_week }}</dd>

                <dt class="col-sm-3">Giờ bắt đầu</dt>
                <dd class="col-sm-9">{{ $schedule->start_time }}</dd>

                <dt class="col-sm-3">Giờ kết thúc</dt>
                <dd class="col-sm-9">{{ $schedule->end_time }}</dd>

                <dt class="col-sm-3">Trạng thái</dt>
                <dd class="col-sm-9">
                    @if(isset($schedule->status))
                        @if($schedule->status == 'active')
                            <span class="badge bg-success">Đang hoạt động</span>
                        @elseif($schedule->status == 'inactive')
                            <span class="badge bg-secondary">Không hoạt động</span>
                        @else
                            <span class="badge bg-info">{{ $schedule->status }}</span>
                        @endif
                    @else
                        N/A
                    @endif
                </dd>

                <dt class="col-sm-3">Ghi chú</dt>
                <dd class="col-sm-9">{{ $schedule->note ?? 'Không có' }}</dd>
            </dl>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
        </div>
    </div>
</div>
@endsection
