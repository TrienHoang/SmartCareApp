@extends('doctor.dashboard')


@section('content')
<div class="container">
    <h2>Chi tiết lịch khám</h2>

    <div class="card mb-3">
        <div class="card-header">Thông tin bệnh nhân</div>
        <div class="card-body">
            <p><strong>Họ tên:</strong> {{ $appointment->patient->full_name }}</p>
            <p><strong>Email:</strong> {{ $appointment->patient->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $appointment->patient->phone }}</p>
            <p><strong>Ngày sinh:</strong> {{ $appointment->patient->date_of_birth?->format('d/m/Y') ?? 'N/A' }}</p>
            <p><strong>Địa chỉ:</strong> {{ $appointment->patient->address }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Thông tin lịch khám</div>
        <div class="card-body">
            <p><strong>Dịch vụ:</strong> {{ $appointment->service->name ?? 'N/A' }}</p>
            <p><strong>Thời gian khám:</strong> {{ $appointment->appointment_time->format('d/m/Y H:i') }}</p>
            <p><strong>Trạng thái:</strong> {{ $appointment->status }}</p>
            <p><strong>Lý do khám:</strong> {{ $appointment->reason ?? 'N/A' }}</p>
            <p><strong>Lý do hủy:</strong> {{ $appointment->cancel_reason ?? 'N/A' }}</p>
        </div>
    </div>

<div class="card mb-3">
    <div class="card-header">Kết quả khám</div>
    <div class="card-body">
        @if ($appointment->medicalRecord)
            <p><strong>Chuẩn đoán:</strong> {{ $appointment->medicalRecord->diagnosis ?? '---' }}</p>
            <p><strong>Ghi chú:</strong> {{ $appointment->medicalRecord->notes ?? '---' }}</p>
            {{-- Ví dụ thêm các trường khác --}}
            @if($appointment->medicalRecord->prescription)
                <p><strong>Đơn thuốc:</strong> {{ $appointment->medicalRecord->prescription }}</p>
            @endif
            @if($appointment->medicalRecord->next_appointment)
                <p><strong>Lịch hẹn tái khám:</strong> {{ \Carbon\Carbon::parse($appointment->medicalRecord->next_appointment)->format('d/m/Y') }}</p>
            @endif
        @else
            <p>Chưa có kết quả khám.</p>
        @endif
    </div>
</div>


    <a href="{{ route('doctor.history.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
</div>
@endsection

