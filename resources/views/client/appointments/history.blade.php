@extends('client.layouts.app')

@section('title', 'Lịch sử khám bệnh')

@section('content')
    <style>
        .history-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .history-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
        }

        .history-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .appointment-card {
            background: #fff;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border-left: 5px solid #667eea;
        }

        .appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        .appointment-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #4a4a4a;
            margin-bottom: 1rem;
        }

        .appointment-info {
            margin-bottom: 0.5rem;
            color: #555;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .appointment-info i {
            color: #667eea;
        }

        .appointment-info strong {
            min-width: 150px;
            display: inline-block;
        }

        .view-detail-btn {
            margin-top: 1rem;
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #667eea;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .view-detail-btn:hover {
            background: #5a67d8;
        }

        .empty-message {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 8px;
            color: #444;
        }
    </style>

    <div class="history-container">
        <div class="history-header">
            <h2>🩺 Lịch sử khám bệnh của bạn</h2>
        </div>

        @if ($appointments->isEmpty())
            <div class="empty-message">Bạn chưa có lịch sử khám bệnh nào.</div>
        @else
            @foreach($appointments as $appointment)
                <div class="appointment-card">
                    <div class="appointment-title">{{ $appointment->service->name ?? 'Dịch vụ khám' }}</div>

                    <div class="appointment-info">
                        <i class="bx bx-calendar"></i>
                        <strong>Ngày khám:</strong>
                        {{ $appointment->appointment_time->format('d/m/Y H:i') }}
                    </div>

                    <div class="appointment-info">
                        <i class="bx bx-user"></i>
                        <strong>Bác sĩ:</strong>
                        {{ $appointment->doctor->user->full_name ?? 'Chưa rõ' }}
                    </div>

                    <div class="appointment-info">
                        <i class="bx bx-notepad"></i>
                        <strong>Chẩn đoán:</strong>
                        {{ $appointment->medicalRecord->diagnosis ?? 'Chưa có thông tin' }}
                    </div>

                    <div class="appointment-info">
                        <i class="bx bx-credit-card"></i>
                        <strong>Thanh toán:</strong>
                        {{ $appointment->payment->status ?? 'Chưa thanh toán' }}
                    </div>

                    <a href="{{ route('client.appointments.detail', $appointment->id) }}" class="view-detail-btn">
                        <i class="bx bx-detail"></i> Xem chi tiết
                    </a>
                </div>
            @endforeach
        @endif
    </div>
@endsection

