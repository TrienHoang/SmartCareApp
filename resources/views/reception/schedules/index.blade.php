@extends('reception.dashboard')

@section('title', 'Lịch làm việc bác sĩ')

@push('styles')
    <style>
        .stats-card {
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            color: white;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stats-card .icon {
            font-size: 2.5rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .stats-card .number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stats-card .label {
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .card-turquoise {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
        }

        .card-orange {
            background: linear-gradient(135deg, #ffa726 0%, #ff7043 100%);
        }

        .card-green {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
        }

        .card-red {
            background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
        }

        .card-blue {
            background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%);
        }

        .card-gray {
            background: linear-gradient(135deg, #90a4ae 0%, #78909c 100%);
        }

        .filter-section {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            border: 1px solid #e8eaed;
        }

        .filter-section .section-title {
            color: #5f6368;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .filter-section .section-title i {
            color: #4285f4;
            font-size: 1.3rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e8eaed;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            height: auto;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4285f4;
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: #3c4043;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .btn-search {
            background: linear-gradient(135deg, #4285f4 0%, #1a73e8 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 32px;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(66, 133, 244, 0.3);
        }

        .btn-search:hover {
            background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.4);
            color: white;
        }

        .btn-reset {
            background: #f8f9fa;
            border: 2px solid #e8eaed;
            border-radius: 12px;
            padding: 12px 24px;
            color: #5f6368;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: #e8eaed;
            border-color: #d2d3d4;
            color: #3c4043;
        }

        .table-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e8eaed;
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px 32px;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .table-header .count-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .table-responsive {
            border-radius: 0;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .table thead th {
            background: #f8f9fc;
            border: none;
            padding: 20px 24px;
            font-weight: 600;
            color: #5f6368;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e8eaed;
        }

        .table tbody td {
            padding: 20px 24px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
        }

        .doctor-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .doctor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4285f4, #34a853);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .doctor-details {
            flex: 1;
        }

        .doctor-name {
            font-weight: 600;
            color: #3c4043;
            margin-bottom: 4px;
            font-size: 0.95rem;
        }

        .doctor-dept {
            color: #5f6368;
            font-size: 0.8rem;
        }

        .shift-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .shift-morning {
            background: linear-gradient(135deg, #fff9c4, #fef08a);
            color: #a16207;
            border: 1px solid #fde047;
        }

        .shift-afternoon {
            background: linear-gradient(135deg, #fed7d7, #fca5a5);
            color: #dc2626;
            border: 1px solid #f87171;
        }

        .shift-evening {
            background: linear-gradient(135deg, #ddd6fe, #c4b5fd);
            color: #7c3aed;
            border: 1px solid #a78bfa;
        }

        .room-badge {
            background: #e8f0fe;
            color: #1a73e8;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1;
            white-space: nowrap;
            min-width: 90px;
            gap: 6px;
            text-align: center;
            /* Khoảng cách giữa icon và text nếu có */
        }



        .status-available {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .status-leave {
            background: #ffebee;
            color: #c62828;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #5f6368;
        }

        .empty-state i {
            font-size: 4rem;
            color: #e8eaed;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            color: #3c4043;
            margin-bottom: 12px;
        }

        .pagination {
            justify-content: center;
            padding: 24px;
            margin: 0;
        }

        .page-link {
            border: none;
            color: #5f6368;
            padding: 10px 16px;
            margin: 0 4px;
            border-radius: 8px;
            font-weight: 500;
        }

        .page-link:hover {
            background: #f8f9fc;
            color: #4285f4;
        }

        .page-item.active .page-link {
            background: #4285f4;
            color: white;
        }

        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 16px;
            }

            .filter-section {
                padding: 24px 20px;
            }

            .table-header {
                padding: 20px;
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .table thead th,
            .table tbody td {
                padding: 16px 12px;
            }

            .doctor-info {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1" style="color: #3c4043; font-weight: 600;">
                    <i class="fas fa-calendar-alt me-3" style="color: #4285f4;"></i>
                    Lịch làm việc bác sĩ
                </h2>
                <p class="text-muted mb-0">Quản lý và theo dõi lịch làm việc của các bác sĩ</p>
            </div>
            <div class="text-end">
                @if ($carbonDate)
                    <div class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-calendar-day me-2"></i>
                        {{ $carbonDate->format('d/m/Y') }}
                    </div>
                @elseif(isset($dayStats['period_info']))
                    <div class="badge bg-info fs-6 px-3 py-2">
                        <i class="fas fa-calendar-week me-2"></i>
                        {{ $dayStats['period_info']['period_name'] }}
                        <small class="d-block mt-1" style="font-size: 0.75rem;">
                            {{ $dayStats['period_info']['start'] }} - {{ $dayStats['period_info']['end'] }}
                        </small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="stats-card card-blue">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="number">{{ $stats['total_doctors'] }}</div>
                    <div class="label">Tổng bác sĩ</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="stats-card card-green">
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="number">{{ $stats['working_today'] }}</div>
                    <div class="label">Đang làm việc</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="stats-card card-orange">
                    <div class="icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="number">{{ $stats['on_leave_today'] }}</div>
                    <div class="label">Đang nghỉ phép</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="stats-card card-turquoise">
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="number">{{ $stats['departments_count'] }}</div>
                    <div class="label">Chuyên khoa</div>
                </div>
            </div>
            @if ($date && isset($dayStats))
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stats-card card-red">
                        <div class="icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="number">{{ $dayStats['total_schedules'] }}</div>
                        <div class="label">
                            @if ($carbonDate)
                                Lịch ngày {{ $carbonDate->format('d/m') }}
                            @else
                                Lịch được tìm thấy
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stats-card card-gray">
                        <div class="icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div class="number">{{ $dayStats['departments_active'] }}</div>
                        <div class="label">Khoa hoạt động</div>
                    </div>
                </div>
            @elseif(isset($dayStats) && ($doctorId || $departmentId))
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stats-card card-red">
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="number">{{ $dayStats['total_schedules'] }}</div>
                        <div class="label">Tổng lịch làm việc</div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stats-card card-gray">
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="number">{{ $dayStats['departments_active'] }}</div>
                        <div class="label">Khoa tham gia</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="section-title">
                <i class="fas fa-filter"></i>
                Bộ lọc tìm kiếm
            </div>

            <form action="{{ route('receptionist.doctors.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar me-2"></i>Chọn ngày
                        </label>
                        <input type="date" name="date" id="date" class="form-control"
                            value="{{ $date ?? '' }}" max="{{ now()->addMonths(3)->format('Y-m-d') }}"
                            placeholder="Không bắt buộc">
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-hospital me-2"></i>Chuyên khoa
                        </label>
                        <select name="department" id="department_id" class="form-select">
                            <option value="">-- Tất cả chuyên khoa --</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-user-md me-2"></i>Bác sĩ
                        </label>
                        <select name="doctor" id="doctor_id" class="form-select">
                            <option value="">-- Tất cả bác sĩ --</option>
                            @foreach ($doctors as $doc)
                                <option value="{{ $doc->id }}" {{ $doctorId == $doc->id ? 'selected' : '' }}>
                                    {{ $doc->user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar-week me-2"></i>Thời gian
                        </label>
                        <select name="period" id="period" class="form-select">
                            <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Tuần này</option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Tháng này</option>
                            <option value="next_week" {{ request('period') == 'next_week' ? 'selected' : '' }}>Tuần sau
                            </option>
                            <option value="next_month" {{ request('period') == 'next_month' ? 'selected' : '' }}>Tháng sau
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-search px-3">
                            <i class="fas fa-search me-2"></i>
                        </button>
                        <button type="button" class="btn btn-reset px-3"
                            onclick="resetFilters()">
                            <i class="fas fa-redo me-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Schedule Table -->
        <div class="table-section">
            <div class="table-header">
                <h5>
                    <i class="fas fa-list-ul"></i>
                    Danh sách lịch làm việc
                </h5>
                @if ($schedules->count() > 0)
                    <span class="count-badge">{{ $schedules->total() }} lịch làm việc</span>
                @endif
            </div>

            @if ($schedules && $schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <i class="fas fa-hashtag me-1"></i>STT
                                </th>
                                <th>
                                    <i class="fas fa-user-md me-1"></i>Bác sĩ
                                </th>
                                <th>
                                    <i class="fas fa-hospital me-1"></i>Chuyên khoa
                                </th>
                                <th>
                                    <i class="fas fa-calendar me-1"></i>Ngày làm
                                </th>
                                <th>
                                    <i class="fas fa-clock me-1"></i>Ca làm việc
                                </th>
                                <th>
                                    <i class="fas fa-door-open me-1"></i>Phòng
                                </th>
                                <th>
                                    <i class="fas fa-info-circle me-1"></i>Trạng thái
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($schedules as $index => $schedule)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">
                                            #{{ $schedules->firstItem() + $index }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="doctor-info">
                                            <div class="doctor-avatar">
                                                {{ strtoupper(substr($schedule->doctor->user->full_name, 0, 1)) }}
                                            </div>
                                            <div class="doctor-details">
                                                <div class="doctor-name">
                                                    {{ $schedule->doctor->user->full_name }}
                                                </div>
                                                <div class="doctor-dept">
                                                    ID: {{ $schedule->doctor->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">
                                            {{ $schedule->doctor->department->name ?? 'Chưa phân khoa' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- <i class="fas fa-calendar-day text-primary me-2"></i> --}}
                                            <div>
                                                <div class="fw-bold">
                                                    {{ \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($schedule->day)->dayName }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($schedule->shift)
                                            <span class="shift-badge shift-{{ strtolower($schedule->shift->name) }}">
                                                {{-- <i class="fas fa-clock"></i> --}}
                                                {{ $schedule->shift->name }}
                                                <small class="d-block mt-1">
                                                    {{ substr($schedule->shift->start_time, 0, 5) }} -
                                                    {{ substr($schedule->shift->end_time, 0, 5) }}
                                                </small>
                                            </span>
                                        @else
                                            <span class="text-muted">Chưa có ca</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($schedule->room)
                                            <span class="room-badge">
                                                {{-- <i class="fas fa-door-open me-1"></i> --}}
                                                {{ $schedule->room->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Chưa có phòng</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (in_array($schedule->doctor_id, $leaves))
                                            <span class="status-badge status-leave">
                                                Nghỉ phép
                                            </span>
                                        @else
                                            <span class="status-badge status-available">
                                                Làm việc
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($schedules->hasPages())
                    <div class="px-4 pb-4">
                        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                            {{-- Left: Pagination Info --}}
                            <div class="text-sm text-gray-600 order-1">
                                Hiển thị {{ $schedules->firstItem() }} - {{ $schedules->lastItem() }} trong tổng số
                                {{ $schedules->total() }} lịch làm việc
                            </div>

                            {{-- Right: Pagination Navigation --}}
                            <nav class="flex items-center space-x-2 order-2">
                                {{-- Previous Page --}}
                                @if ($schedules->onFirstPage())
                                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $schedules->previousPageUrl() }}"
                                        class="px-4 py-2 text-blue-600 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 hover:border-blue-400 transition-colors duration-200 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach ($schedules->getUrlRange(1, $schedules->lastPage()) as $page => $url)
                                    @if ($page == $schedules->currentPage())
                                        <span
                                            class="px-4 py-2 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg font-medium shadow-lg">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="px-4 py-2 text-blue-600 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 hover:border-blue-400 transition-colors duration-200 shadow-sm">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next Page --}}
                                @if ($schedules->hasMorePages())
                                    <a href="{{ $schedules->nextPageUrl() }}"
                                        class="px-4 py-2 text-blue-600 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 hover:border-blue-400 transition-colors duration-200 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h5>Không có lịch làm việc</h5>
                    <p class="mb-0">
                        @if ($date)
                            Không tìm thấy lịch làm việc cho ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                        @elseif($doctorId || $departmentId)
                            Không tìm thấy lịch làm việc cho bộ lọc đã chọn
                        @else
                            Hãy chọn bác sĩ hoặc chuyên khoa để xem lịch làm việc
                        @endif
                    </p>
                    @if (!$doctorId && !$departmentId && !$date)
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-lightbulb me-1"></i>
                                Gợi ý: Chọn một bác sĩ cụ thể hoặc chuyên khoa để xem lịch làm việc của họ
                            </small>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function resetFilters() {
                document.getElementById('date').value = '';
                document.getElementById('department_id').value = '';
                document.getElementById('doctor_id').value = '';
                document.getElementById('period').value = 'week';
                document.getElementById('filterForm').submit();
            }

            function viewScheduleDetail(scheduleId) {
                // Thêm logic để xem chi tiết lịch làm việc
                alert('Xem chi tiết lịch làm việc #' + scheduleId);
            }

            // Auto-submit form when selections change
            document.getElementById('department_id').addEventListener('change', function() {
                if (this.value !== '') {
                    document.getElementById('filterForm').submit();
                }
            });

            document.getElementById('doctor_id').addEventListener('change', function() {
                if (this.value !== '') {
                    document.getElementById('filterForm').submit();
                }
            });

            document.getElementById('period').addEventListener('change', function() {
                const doctorId = document.getElementById('doctor_id').value;
                const departmentId = document.getElementById('department_id').value;

                // Only auto-submit if doctor or department is selected
                if (doctorId !== '' || departmentId !== '') {
                    document.getElementById('filterForm').submit();
                }
            });

            // Optional: Auto-submit when date is selected (if user wants to use date filter)
            document.getElementById('date').addEventListener('change', function() {
                if (this.value !== '') {
                    document.getElementById('filterForm').submit();
                }
            });

            // Filter doctors by department (AJAX call could be added here)
            document.getElementById('department_id').addEventListener('change', function() {
                const departmentId = this.value;
                const doctorSelect = document.getElementById('doctor_id');

                // Reset doctor selection when department changes
                if (departmentId !== '') {
                    doctorSelect.value = '';
                }

                // You can add AJAX call here to filter doctors by department
                // For now, keeping it simple with form auto-submission
            });
        </script>
    @endpush
@endsection
