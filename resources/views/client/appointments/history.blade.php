@extends('client.layouts.app')

@section('title', 'Lịch sử khám bệnh')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">🩺 Lịch sử khám bệnh</h2>

    @if ($appointments->isEmpty())
        <div class="alert alert-info">Bạn chưa có lịch sử khám bệnh nào.</div>
    @else
        @foreach($appointments as $appointment)
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $appointment->service->name ?? 'Dịch vụ' }}</h5>

                    <div class="mb-1">
                        <i class="bx bx-calendar"></i>
                        <strong>Ngày khám:</strong> {{ $appointment->appointment_time->format('d/m/Y H:i') }}
                    </div>

                    <div class="mb-1">
                        <i class="bx bx-user"></i>
                        <strong>Bác sĩ:</strong> {{ $appointment->doctor->user->full_name ?? 'Chưa rõ' }}
                    </div>

                    <div class="mb-1">
                        <i class="bx bx-notepad"></i>
                        <strong>Chẩn đoán:</strong> {{ $appointment->medicalRecord->diagnosis ?? 'Chưa có thông tin' }}
                    </div>

                    <div class="mb-1">
                        <i class="bx bx-credit-card"></i>
                        <strong>Trạng thái thanh toán:</strong> {{ $appointment->payment->status ?? 'Chưa thanh toán' }}
                    </div>

                    <a href="{{ route('client.appointments.detail', $appointment->id) }}"
                       class="btn btn-outline-primary btn-sm mt-2">
                        <i class="bx bx-detail"></i> Xem chi tiết
                    </a>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
