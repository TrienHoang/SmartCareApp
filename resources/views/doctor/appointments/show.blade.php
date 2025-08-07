@extends('doctor.dashboard')

@section('title', 'Chi tiết lịch hẹn')

@section('content')
    <style>
        :root {
            --primary-blue: #1e40af;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --dark-blue: #1e3a8a;
            --accent-blue: #60a5fa;
            --success-green: #10b981;
            --warning-orange: #f59e0b;
            --danger-red: #ef4444;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --white: #ffffff;
            --light-gray: #f8fafc;
        }

        .page-container {
            background: linear-gradient(135deg, var(--light-blue) 0%, #e0f2fe 50%, var(--white) 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .appointment-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .breadcrumb-nav {
            background: var(--white);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(30, 64, 175, 0.1);
            border-left: 4px solid var(--primary-blue);
        }

        .breadcrumb {
            margin: 0;
            background: transparent;
        }

        .breadcrumb-item a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--secondary-blue);
        }

        .appointment-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.15);
            transition: all 0.3s ease;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(30, 64, 175, 0.2);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: var(--white);
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-bottom: 3px solid rgba(255, 255, 255, 0.2);
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            transition: all 0.3s ease;
        }

        .card-header:hover::before {
            right: -30%;
        }

        .card-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            z-index: 1;
            color: var(--white);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 0.5px;
        }

        .card-header .appointment-id {
            background: rgba(255, 255, 255, 0.25);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 700;
            margin-left: 0.5rem;
            color: var(--white);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .card-body {
            padding: 2.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            background: var(--light-gray);
            border-radius: 12px;
            padding: 1.5rem;
            border-left: 4px solid var(--secondary-blue);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-item:hover {
            background: var(--light-blue);
            border-left-color: var(--primary-blue);
            transform: translateX(5px);
        }

        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.05), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .info-item:hover::before {
            opacity: 1;
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--text-gray);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-value {
            font-size: 1.1rem;
            color: var(--text-dark);
            font-weight: 600;
            line-height: 1.4;
        }

        .status-container {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--light-blue);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: var(--white);
        }

        .status-confirmed {
            background: linear-gradient(135deg, var(--secondary-blue), var(--primary-blue));
            color: var(--white);
        }

        .status-checked_in {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: var(--white);
        }

        .status-completed {
            background: linear-gradient(135deg, var(--success-green), #059669);
            color: var(--white);
        }

        .status-cancelled {
            background: linear-gradient(135deg, var(--danger-red), #dc2626);
            color: var(--white);
        }

        .action-form {
            background: linear-gradient(135deg, var(--light-blue), rgba(219, 234, 254, 0.5));
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            border: 2px dashed var(--secondary-blue);
        }

        .form-label {
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-select {
            border-radius: 8px;
            border: 2px solid var(--light-blue);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-select:focus {
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-update {
            background: linear-gradient(135deg, var(--success-green), #059669);
            border: none;
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-cancel {
            background: linear-gradient(135deg, var(--danger-red), #dc2626);
            border: none;
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-back {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border: none;
            color: var(--white);
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
            color: var(--white);
            text-decoration: none;
        }

        .notes-section {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid var(--accent-blue);
        }

        .icon-wrapper {
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .appointment-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .info-item {
            animation: fadeInUp 0.6s ease-out;
        }

        .info-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .info-item:nth-child(3) {
            animation-delay: 0.2s;
        }

        .info-item:nth-child(4) {
            animation-delay: 0.3s;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-container {
                padding: 1rem 0;
            }

            .card-header {
                padding: 1.5rem;
            }

            .card-header h1 {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .info-item {
                padding: 1rem;
            }

            .action-form {
                padding: 1.5rem;
            }

            .btn-back {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .appointment-wrapper {
                padding: 0 0.5rem;
            }

            .breadcrumb-nav {
                padding: 0.75rem 1rem;
            }

            .card-header h1 {
                font-size: 1.25rem;
            }

            .info-value {
                font-size: 1rem;
            }

            .btn-back {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
        }
    </style>

    <div class="page-container">
        <div class="appointment-wrapper">
            <!-- Breadcrumb Navigation -->
            <nav class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('doctor.appointments.index') }}">
                            <i class="bx bx-calendar icon-wrapper"></i>
                            Lịch hẹn
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Chi tiết lịch hẹn</li>
                </ol>
            </nav>

            <!-- Appointment Detail Card -->
            <div class="appointment-card">
                <div class="card-header">
                    <h1>
                        <i class="bx bx-calendar-check"></i>
                        Chi tiết lịch hẹn
                        <span class="appointment-id">#{{ $appointment->id }}</span>
                    </h1>
                </div>

                <div class="card-body">
                    <!-- Information Grid -->
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bx bx-time-five icon-wrapper"></i>
                                Thời gian
                            </div>
                            <div class="info-value">{{ $appointment->appointment_time }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bx bx-user icon-wrapper"></i>
                                Bệnh nhân
                            </div>
                            <div class="info-value">{{ $appointment->patient->full_name ?? 'Không xác định' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bx bx-plus-medical icon-wrapper"></i>
                                Dịch vụ
                            </div>
                            <div class="info-value">{{ $appointment->service->name ?? 'Không xác định' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bx bx-message-detail icon-wrapper"></i>
                                Lý do khám
                            </div>
                            <div class="info-value">{{ $appointment->reason ?? 'Không có' }}</div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="status-container">
                        <div class="info-label">
                            <i class="bx bx-info-circle icon-wrapper"></i>
                            Trạng thái hiện tại
                        </div>
                        <div style="margin-top: 1rem;">
                            @switch($appointment->status)
                                @case('pending')
                                    <span class="status-badge status-pending">
                                        <i class="bx bx-hourglass"></i>
                                        Đang chờ
                                    </span>
                                @break

                                @case('confirmed')
                                    <span class="status-badge status-confirmed">
                                        <i class="bx bx-check-circle"></i>
                                        Đã xác nhận
                                    </span>
                                @break

                                @case('checked_in')
                                    <span class="status-badge status-checked_in">
                                        <i class="bx bx-user-check"></i>
                                        Đã đến
                                    </span>
                                @break

                                @case('completed')
                                    <span class="status-badge status-completed">
                                        <i class="bx bx-check-double"></i>
                                        Hoàn tất
                                    </span>
                                @break

                                @case('cancelled')
                                    <span class="status-badge status-cancelled">
                                        <i class="bx bx-x-circle"></i>
                                        Đã hủy
                                    </span>
                                @break

                                @default
                                    <span class="status-badge" style="background: #6b7280;">
                                        <i class="bx bx-question-mark"></i>
                                        Không xác định
                                    </span>
                            @endswitch
                        </div>
                    </div>

                    <!-- Action Forms -->
                    @if ($appointment->status == 'checked_in')
                        <div class="action-form">
                            <form action="{{ route('doctor.appointments.updateStatus', $appointment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <i class="bx bx-edit icon-wrapper"></i>
                                        Cập nhật trạng thái lịch hẹn
                                    </label>
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <select name="status" id="status" class="form-select"
                                            style="width: auto; min-width: 200px;">
                                            <option value="completed">Hoàn tất khám</option>
                                            <option value="cancelled">Hủy lịch hẹn</option>
                                        </select>
                                        <button type="submit" class="btn-update">
                                            <i class="bx bx-save"></i>
                                            Cập nhật
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @elseif ($appointment->status == 'confirmed')
                        <div class="action-form">
                            <form action="{{ route('doctor.appointments.updateStatus', $appointment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <i class="bx bx-x-circle icon-wrapper"></i>
                                        Hủy lịch hẹn đã xác nhận
                                    </label>
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <select name="status" id="status" class="form-select"
                                            style="width: auto; min-width: 200px;">
                                            <option value="cancelled">Hủy lịch hẹn</option>
                                        </select>
                                        <button type="submit" class="btn-cancel">
                                            <i class="bx bx-trash"></i>
                                            Hủy lịch
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Notes Section -->
                    @if ($appointment->notes)
                        <div class="notes-section">
                            <div class="info-label">
                                <i class="bx bx-note icon-wrapper"></i>
                                Ghi chú
                            </div>
                            <div class="info-value">{{ $appointment->notes }}</div>
                        </div>
                    @endif

                    <!-- Back Button -->
                    <div class="text-center mt-4">
                        <a href="{{ route('doctor.appointments.index') }}" class="btn-back">
                            <i class="bx bx-arrow-back"></i>
                            Quay lại danh sách lịch hẹn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
