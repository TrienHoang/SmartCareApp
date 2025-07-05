@extends('doctor.dashboard')

@section('title', 'Lịch khám của tôi')

@section('content')
    <div class="container mt-4">
        <h3>Lịch khám</h3>

        @forelse ($appointments as $appointment)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        Dịch vụ: {{ $appointment->service->name ?? 'N/A' }}
                    </h5>

                    <p class="mb-1"><strong>Bệnh nhân:</strong> {{ $appointment->patient->full_name ?? 'Ẩn danh' }}</p>
                    <p class="mb-1"><strong>Thời gian:</strong> {{ $appointment->appointment_time->format('d/m/Y H:i') }}
                    </p>
                    <p class="mb-1"><strong>Trạng thái:</strong> {{ ucfirst($appointment->status) }}</p>

                    @if ($appointment->reason)
                        <p class="mb-0"><strong>Lý do đặt:</strong> {{ $appointment->reason }}</p>
                    @endif
                </div>
            </div>
        @empty
            <p>Không có lịch hẹn nào.</p>
        @endforelse

        <div class="mt-3">
            {{ $appointments->links() }}
        </div>
    </div>
@endsection
