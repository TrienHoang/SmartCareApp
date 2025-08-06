@extends('doctor.dashboard') {{-- hoặc layout chính của bạn nếu khác --}}

@section('title', 'Chi tiết lịch hẹn')

@section('content')
    <style>
        .appointment-container {
            background: linear-gradient(135deg, #8B6F47, #D9C2A6);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            max-width: 1400px;
            margin: 2rem auto;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .appointment-container:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
        }

        .appointment-card {
            background: #F5F5F5;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
        }

        .appointment-card:hover {
            transform: scale(1.02);
        }

        .appointment-card-header {
            background: linear-gradient(to right, #4A7043, #8B9A46);
            color: #FFF8E7;
            padding: 1.5rem;
            border-bottom: 4px solid #3F5E3A;
            text-align: center;
        }

        .appointment-card-header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .appointment-card-body {
            padding: 1.5rem;
        }

        .appointment-detail {
            font-size: 1.1rem;
            color: #3F5E3A;
            margin-bottom: 1rem;
        }

        .appointment-detail strong {
            color: #6B4E31;
        }

        .btn-back {
            background: #6B4E31;
            border: none;
            color: #FFF8E7;
            border-radius: 6px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-back:hover {
            background: #8B6F47;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .appointment-container {
                padding: 1rem;
                margin: 1rem;
            }

            .appointment-card-header h1 {
                font-size: 1.5rem;
            }

            .appointment-detail {
                font-size: 1rem;
            }

            .btn-back {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .appointment-card-header h1 {
                font-size: 1.2rem;
            }

            .appointment-detail {
                font-size: 0.9rem;
            }

            .btn-back {
                font-size: 0.8rem;
                padding: 0.5rem 0.75rem;
            }
        }
    </style>

    <div class="appointment-container">
        <div class="appointment-card">
            <div class="appointment-card-header">
                <h1><i class="bx bx-calendar-check me-2"></i>Chi tiết lịch hẹn #{{ $appointment->id }}</h1>
            </div>
            <div class="appointment-card-body">
                <p class="appointment-detail"><strong>Thời gian:</strong> {{ $appointment->appointment_time }}</p>
                <p class="appointment-detail"><strong>Bệnh nhân:</strong>
                    {{ $appointment->patient->full_name ?? 'Không xác định' }}</p>
                <p class="appointment-detail"><strong>Dịch vụ:</strong> {{ $appointment->service->name ?? 'Không xác định' }}
                </p>
                <p class="appointment-detail"><strong>Lý do:</strong> {{ $appointment->reason ?? 'Không có' }}</p>
                @if (!in_array($appointment->status, ['completed', 'cancelled']))
                    <form action="{{ route('doctor.appointments.updateStatus', $appointment->id) }}" method="POST"
                        class="mt-4">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label"><strong>Cập nhật trạng thái lịch hẹn:</strong></label>
                            <select name="status" id="status" class="form-select w-auto d-inline-block">
                                <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Đang chờ
                                </option>
                                <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Đã xác
                                    nhận</option>
                                <option value="check_in" {{ $appointment->status == 'check_in' ? 'selected' : '' }}>Đã đến
                                </option>
                                <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Hoàn
                                    tất</option>
                                <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Đã
                                    hủy</option>
                            </select>
                            <button type="submit" class="btn btn-primary ms-2">Cập nhật</button>
                        </div>
                    </form>
                @endif


                <p class="appointment-detail"><strong>Ghi chú:</strong> {{ $appointment->notes ?? 'Không có' }}</p>
                <a href="{{ route('doctor.appointments.index') }}" class="btn btn-back">← Quay lại lịch</a>
            </div>
        </div>
    </div>
@endsection
