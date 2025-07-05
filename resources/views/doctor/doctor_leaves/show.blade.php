@extends('doctor.dashboard')

@section('title', 'Chi tiết lịch nghỉ')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Chi tiết lịch nghỉ của bác sĩ</h5>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Ngày bắt đầu:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Ngày kết thúc:</strong> {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="mb-3">
                <p><strong>Lý do nghỉ phép:</strong></p>
                <div class="border p-3 bg-light rounded">
                    {{ $leave->reason }}
                </div>
            </div>

            <div class="mb-3">
                <p><strong>Trạng thái:</strong>
                    @if($leave->approved)
                        <span class="badge bg-success">Đã duyệt</span>
                    @else
                        <span class="badge bg-warning text-dark">Chưa duyệt</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('doctor.leaves.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>
@endsection
