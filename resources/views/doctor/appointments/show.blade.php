@extends('doctor.dashboard')

@section('title', 'Chi tiết lịch làm việc')

@section('content')
    <style>
        :root {
            /* --- Bảng màu "Blue Ocean" --- */
            --ocean-primary: #0852f2;
            --ocean-primary-dark: #1d4ed8;
            --ocean-bg-start: #bfdbfe;
            --ocean-bg-end: #eff6ff;
            --ocean-card-bg: rgba(255, 255, 255, 0.85);
            --ocean-glass-bg: rgba(255, 255, 255, 0.6);
            --ocean-text-dark: #1e3a8a;
            --ocean-text-muted: #3b82f6;
            --ocean-border: rgba(255, 255, 255, 0.9);
            --ocean-border-light: #dbeafe;

            /* Status Badge Colors */
            --badge-warning-bg: #fef3c7;
            --badge-warning-border: #f59e0b;
            --badge-warning-text: #b45309;
            --badge-success-bg: #dcfce7;
            --badge-success-border: #16a34a;
            --badge-success-text: #15803d;
            --badge-danger-bg: #fee2e2;
            --badge-danger-border: #dc2626;
            --badge-danger-text: #b91c1c;
            --badge-secondary-bg: #e5e7eb;
            --badge-secondary-border: #6b7280;
            --badge-secondary-text: #374151;

            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.07);
            --shadow-lg-ocean: 0 10px 15px -3px rgba(37, 99, 235, 0.1), 0 4px 6px -4px rgba(37, 99, 235, 0.1);
        }

        /* Animated Background */
        @keyframes animated-ocean {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }



        .detail-container {
            padding: 24px;
            max-width: 800px;
            margin: 0 auto;
        }

        .detail-card {
            background: var(--ocean-card-bg);
            backdrop-filter: blur(5px);
            border: 1px solid var(--ocean-border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg-ocean);
            transition: all 0.3s ease-out;
            padding: 24px;
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--ocean-border-light);
            padding-bottom: 16px;
        }

        .detail-header svg {
            width: 24px;
            height: 24px;
            color: var(--ocean-primary);
        }

        .detail-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--ocean-text-dark);
        }

        .detail-info p {
            margin-bottom: 16px;
        }

        .detail-info label {
            font-weight: 500;
            color: var(--ocean-text-dark);
            font-size: 0.95rem;
            margin-right: 8px;
        }

        .detail-info span {
            padding: 8px 12px;
            background: var(--ocean-glass-bg);
            border-radius: 8px;
            color: var(--ocean-text-muted);
            font-size: 0.9rem;
        }

        .detail-info .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.2s ease-out;
        }

        .detail-info .badge.bg-warning {
            background-color: var(--badge-warning-bg);
            border: 1px solid var(--badge-warning-border);
            color: var(--badge-warning-text);
        }

        .detail-info .badge.bg-success {
            background-color: var(--badge-success-bg);
            border: 1px solid var(--badge-success-border);
            color: var(--badge-success-text);
        }

        .detail-info .badge.bg-danger {
            background-color: var(--badge-danger-bg);
            border: 1px solid var(--badge-danger-border);
            color: var(--badge-danger-text);
        }

        .detail-info .badge.bg-secondary {
            background-color: var(--badge-secondary-bg);
            border: 1px solid var(--badge-secondary-border);
            color: var(--badge-secondary-text);
        }

        .btn-back {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease-out;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--ocean-card-bg);
            color: var(--ocean-text-muted);
            border: 1px solid var(--ocean-border-light);
            text-decoration: none;
        }

        .btn-back:hover {
            background: white;
            color: var(--ocean-text-dark);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .detail-container {
                padding: 16px;
            }

            .detail-card {
                padding: 16px;
            }

            .detail-header h3 {
                font-size: 1.25rem;
            }
        }
    </style>

    <div class="detail-container">
        <div class="detail-card">
            <div class="detail-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 12.75h.008v.008H12v-.008z" />
                </svg>
                <h3>Chi tiết Lịch Làm Việc</h3>
            </div>
            <div class="detail-info">
                <p>
                    <label>Ngày:</label>
                    <span>{{ \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') }}</span>
                </p>
                <p>
                    <label>Ca làm việc:</label>
                    <span>{{ $schedule->shift?->name ?? 'Không xác định' }}
                        @if($schedule->shift)
                            ({{ $schedule->shift->start_time }} - {{ $schedule->shift->end_time }})
                        @endif
                    </span>
                </p>
                <p>
                    <label>Phòng:</label>
                    <span>{{ $schedule->room?->name ?? 'Không xác định' }}</span>
                </p>
                <p>
                    <label>Trạng thái:</label>
                    @php
                        $status = $schedule->status;
                        $badgeClass = match($status) {
                            'Chờ xét duyệt' => 'bg-warning',
                            'Đã duyệt' => 'bg-success',
                            'Từ chối' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                </p>
            </div>
            <div class="mt-4">
                <a href="{{ route('doctor.working_schedules.index') }}" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                        <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201-4.42 5.5 5.5 0 011.663-1.43l.865.865A4.25 4.25 0 006.5 9.883a4.25 4.25 0 008.463.385.75.75 0 00-.66-1.074.75.75 0 00-1.073.662A2.75 2.75 0 018.75 9.883a2.75 2.75 0 01-2.635-4.149l.942.942a.75.75 0 001.06-1.06l-2.25-2.25a.75.75 0 00-1.06 0l-2.25 2.25a.75.75 0 101.06 1.06l.942-.942A4.002 4.002 0 0110 5.883a4.002 4.002 0 013.248 6.335.75.75 0 00.964.906z" clip-rule="evenodd" />
                    </svg>
                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
@endsection