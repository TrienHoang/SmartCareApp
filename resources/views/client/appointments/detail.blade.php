@extends('client.layouts.app')

@section('title', 'Chi tiết khám bệnh')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📋 Chi tiết khám bệnh</h2>

    <!-- Thông tin cuộc hẹn -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $appointment->service->name ?? 'Dịch vụ khám' }}</h5>
            <p><strong>Ngày khám:</strong> {{ $appointment->appointment_time->format('d/m/Y H:i') }}</p>
            <p><strong>Bác sĩ:</strong> {{ $appointment->doctor->user->full_name ?? 'Chưa rõ' }}</p>
            <p><strong>Lý do khám:</strong> {{ $appointment->reason ?? 'Không có ghi chú' }}</p>
        </div>
    </div>

    <!-- Hồ sơ khám -->
    @if ($appointment->medicalRecord)
    <div class="card mb-3">
        <div class="card-header">🧾 Hồ sơ khám bệnh</div>
        <div class="card-body">
            <p><strong>Triệu chứng:</strong> {{ $appointment->medicalRecord->symptoms }}</p>
            <p><strong>Chẩn đoán:</strong> {{ $appointment->medicalRecord->diagnosis }}</p>
            <p><strong>Hướng điều trị:</strong> {{ $appointment->medicalRecord->treatment }}</p>
        </div>
    </div>
    @endif

    <!-- Đơn thuốc -->
    @if ($appointment->prescription && $appointment->prescription->items->count())
    <div class="card mb-3">
        <div class="card-header">💊 Đơn thuốc</div>
        <div class="card-body">
            <ul class="list-group">
                @foreach ($appointment->prescription->items as $item)
                    <li class="list-group-item">
                        <strong>Thuốc:</strong> {{ $item->medicine->name ?? 'Không rõ' }}<br>
                        <strong>Số lượng:</strong> {{ $item->quantity }}<br>
                        <strong>Hướng dẫn sử dụng:</strong> {{ $item->usage_instructions }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Thông tin thanh toán -->
    @if ($appointment->payment)
    <div class="card mb-3">
        <div class="card-header">💳 Thông tin thanh toán</div>
        <div class="card-body">
            <p><strong>Số tiền:</strong> {{ number_format($appointment->payment->amount, 0, ',', '.') }} đ</p>
            <p><strong>Phương thức:</strong> {{ $appointment->payment->payment_method ?? 'Không rõ' }}</p>
            <p><strong>Trạng thái:</strong> {{ $appointment->payment->status ?? 'Chưa thanh toán' }}</p>
        </div>
    </div>
    @endif

    <!-- Đánh giá -->
    @if ($appointment->review)
    <div class="card mb-3">
        <div class="card-header">⭐ Đánh giá</div>
        <div class="card-body">
            <div class="mb-2"><strong>Số sao:</strong> <span class="text-warning">{{ $appointment->review->rating }} / 5</span></div>
            <div class="mb-2"><strong>Nhận xét:</strong> {{ $appointment->review->comment }}</div>
        </div>
    </div>
    @endif

    <!-- Nút quay lại -->
    <a href="{{ route('client.appointments.history') }}" class="btn btn-outline-secondary mt-3">
        <i class="bx bx-arrow-back"></i> Quay lại lịch sử
    </a>
</div>
@endsection
