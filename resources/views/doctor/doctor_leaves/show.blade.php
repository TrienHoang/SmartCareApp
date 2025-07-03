@extends('doctor.dashboard')

@section('title', 'Chi tiết lịch nghỉ')

@section('content')
<div class="container">
    <h3>Chi tiết lịch nghỉ</h3>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Ngày bắt đầu:</strong> {{ $leave->start_date }}</li>
        <li class="list-group-item"><strong>Ngày kết thúc:</strong> {{ $leave->end_date }}</li>
        <li class="list-group-item"><strong>Lý do:</strong> {{ $leave->reason }}</li>
        <li class="list-group-item">
            <strong>Trạng thái:</strong>
            @if($leave->approved)
                <span class="badge bg-success">Đã duyệt</span>
            @else
                <span class="badge bg-warning">Chưa duyệt</span>
            @endif
        </li>
    </ul>

    <a href="{{ route('doctor.leaves.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection
