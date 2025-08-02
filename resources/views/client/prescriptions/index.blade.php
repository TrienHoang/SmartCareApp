@extends('client.layouts.app') {{-- Đảm bảo layout chính của bạn có @yield('content') --}}

@section('title', 'Danh Sách Đơn Thuốc') {{-- Đặt tiêu đề cho trang này --}}

@section('content')
    <div class="min-h-screen bg-gray-50 py-12"> {{-- Bao bọc toàn bộ nội dung trong một container chính --}}
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                {{-- Sidebar: KHÔNG THAY ĐỔI, GIỮ NGUYÊN TỪ LAYOUT CHUNG --}}
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                        {{-- Profile Avatar --}}
                        <div class="text-center mb-8">
                            <div class="relative inline-block">
                                <img id="profile-avatar" src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}"
                                    alt="Avatar"
                                    class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-blue-100 object-cover">
                                <button onclick="openAvatarModal()"
                                    class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 transition-colors shadow-lg">
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ auth()->user()->name ?? 'Người dùng' }}</h3>
                            <p class="text-gray-600">{{ auth()->user()->email ?? 'email@example.com' }}</p>
                        </div>

                        {{-- Menu --}}
                        <nav class="space-y-2">
                            <a href="{{ route('client.profile.show') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="user" class="w-5 h-5"></i>
                                <span>Thông Tin Cá Nhân</span>
                            </a>
                            <a href="{{ route('client.appointments.history') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                                <span>Lịch Sử Khám</span>
                            </a>
                            <a href="{{ route('client.prescriptions.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600 border-l-4 border-blue-600">
                                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                <span class="font-semibold">Đơn Thuốc</span>
                            </a>
                            <a href="{{ route('client.appointments.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                                <span>Lịch Hẹn</span>
                            </a>
                            <a href="#ho-so-y-te"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <span>Hồ Sơ Y Tế</span>
                            </a>
                            <a href="{{ route('client.uploads.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="upload" class="w-5 h-5"></i>
                                <span>Upload File</span>
                            </a>
                            <a href="{{ route('client.notifications.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                <span>Thông Báo</span>
                                @php
                                    $currentUnreadCount = $notifications
                                        ->where('userStatuses.0.is_read', false)
                                        ->count();
                                @endphp
                                @if ($currentUnreadCount > 0)
                                    <span id="unreadCount"
                                        class="ml-2 px-2 py-0.5 bg-red-500 text-white rounded-full text-xs font-semibold">
                                        {{ $currentUnreadCount }}
                                    </span>
                                @endif
                            </a>
                            <a href="#cai-dat"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="settings" class="w-5 h-5"></i>
                                <span>Cài Đặt</span>
                            </a>
                        </nav>
                    </div>
                </div>

                {{-- Main Content: Dán mã giao diện "Danh Sách Đơn Thuốc" của bạn vào đây --}}
                <div class="lg:w-3/4">
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        {{-- Bắt đầu mã "Danh Sách Đơn Thuốc" --}}
                        <div class="prescription-list-container">
                            {{-- Thanh tìm kiếm --}}
                            <form method="GET" action="{{ route('client.prescriptions.index') }}"
                                class="mb-4 w-full max-w-xl">
                                <div class="flex gap-2 items-center">
                                    {{-- Ô tìm kiếm --}}
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Tìm bác sĩ, triệu chứng hoặc chẩn đoán"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300 text-sm">

                                    {{-- Nút tìm kiếm --}}
                                    <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm flex items-center gap-1">
                                        <i class="fas fa-search"></i>
                                        <span>Tìm Kiếm</span>
                                    </button>

                                    {{-- Nút reset, chỉ hiển thị nếu đang có từ khoá tìm kiếm --}}
                                    @if (request('search'))
                                        <a href="{{ route('client.prescriptions.index') }}"
                                            class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm flex items-center gap-1">
                                            <i class="fas fa-times"></i>
                                            <span>Reset</span>
                                        </a>
                                    @endif
                                </div>
                            </form>




                            {{-- Header --}}
                            <div class="page-header" style="background: none; padding: 0; margin-bottom: 0;">
                                <div class="container-fluid" style="padding: 0;">
                                    <div class="header-content" style="gap: 1rem;">
                                        <div class="header-info">
                                            <h1 class="page-title" style="font-size: 2rem; margin-bottom: 0;color:#4338ca">
                                                <i class="fas fa-prescription-bottle-alt"></i>
                                                Danh Sách Đơn Thuốc
                                            </h1>
                                            <p class="page-subtitle" style="font-size: 0.9rem;">Quản lý và theo dõi các đơn
                                                thuốc của bạn</p>
                                        </div>
                                        <div class="header-stats">
                                            <div class="stat-item" style="background: none; padding: 0;">
                                                <span class="stat-number text-indigo-600">{{ count($appointments) }}</span>
                                                <span class="stat-label text-gray-500">Lịch khám</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Nội dung --}}
                            <div class="container-fluid p-0">
                                <div class="appointments-grid p-0">
                                    @forelse($appointments as $appointment)
                                        <div class="appointment-card">
                                            <div class="doctor-info">
                                                <div class="doctor-avatar">
                                                    <i class="fas fa-user-md"></i>
                                                </div>
                                                <div class="doctor-details">
                                                    <h3 class="doctor-name">
                                                        {{ $appointment->doctor->user->full_name ?? 'Không xác định' }}
                                                    </h3>
                                                    <p class="doctor-role">Bác sĩ khám bệnh</p>
                                                </div>
                                            </div>

                                            <div class="appointment-info">
                                                <div class="info-row">
                                                    <div class="info-item">
                                                        <i class="fas fa-calendar-check"></i>
                                                        <span>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d/m/Y - H:i') }}</span>
                                                    </div>
                                                    <div class="status-badge completed">
                                                        <i class="fas fa-check-circle"></i>
                                                        Đã khám
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Triệu chứng --}}
                                            @if ($appointment->medicalRecord && $appointment->medicalRecord->symptoms)
                                                <div class="medical-detail-section">
                                                    <div class="section-header">
                                                        <h4 class="section-title">Triệu chứng</h4>
                                                    </div>
                                                    <p class="medical-text">{{ $appointment->medicalRecord->symptoms }}
                                                    </p>
                                                </div>
                                            @endif

                                            {{-- Chẩn đoán --}}
                                            @if ($appointment->medicalRecord && $appointment->medicalRecord->diagnosis)
                                                <div class="medical-detail-section">
                                                    <div class="section-header">
                                                        <h4 class="section-title">Chẩn đoán</h4>
                                                    </div>
                                                    <p class="medical-text">{{ $appointment->medicalRecord->diagnosis }}
                                                    </p>
                                                </div>
                                            @endif

                                            {{-- Đơn thuốc --}}
                                            @if ($appointment->medicalRecord && count($appointment->medicalRecord->prescriptions))
                                                <div class="prescriptions-section">
                                                    <div class="section-header">
                                                        <h4 class="section-title">Đơn thuốc</h4>
                                                        <span
                                                            class="prescription-count">{{ count($appointment->medicalRecord->prescriptions) }}
                                                            đơn</span>
                                                    </div>

                                                    <div class="prescriptions-list">
                                                        @foreach ($appointment->medicalRecord->prescriptions as $index => $prescription)
                                                            <div class="prescription-item">
                                                                <div class="prescription-info">
                                                                    <div class="prescription-id">
                                                                        <span class="id-badge">{{ $index + 1 }}</span>
                                                                        <span class="id-text">Đơn
                                                                            #{{ $prescription->id }}</span>
                                                                    </div>
                                                                    <div class="prescription-date">
                                                                        <i class="fas fa-calendar"></i>
                                                                        {{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y') }}
                                                                    </div>
                                                                </div>
                                                                <a href="{{ route('client.prescriptions.show', $prescription->id) }}"
                                                                    class="view-btn">
                                                                    <i class="fas fa-eye"></i>
                                                                    Chi tiết
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="no-prescription">
                                                    <div class="no-prescription-icon">
                                                        <i class="fas fa-hourglass-half"></i>
                                                    </div>
                                                    <div class="no-prescription-text">
                                                        <h4>Chưa có đơn thuốc</h4>
                                                        <p>Đơn thuốc sẽ được tạo sau khi bác sĩ hoàn thành khám</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="fas fa-prescription-bottle-alt"></i>
                                            </div>
                                            <div class="empty-content">
                                                <h3>Chưa có lịch khám nào</h3>
                                                <p>Bạn chưa có lịch khám nào để hiển thị đơn thuốc</p>
                                                <a href="#" class="cta-btn">
                                                    <i class="fas fa-plus"></i>
                                                    Đặt lịch khám
                                                </a>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* CSS của bạn đã gửi sẽ được đặt ở đây */
            .prescription-list-container {
                background: #f8fafc;
                min-height: auto;
                /* Thay đổi để không override min-h-screen của layout */
            }

            /* Header */
            .page-header {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                /* Giữ màu nền gradient */
                color: white;
                padding: 2rem 0;
                margin-bottom: 2rem;
            }

            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 2rem;
            }

            .page-title {
                font-size: 1.875rem;
                font-weight: 700;
                margin: 0 0 0.5rem 0;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .page-subtitle {
                margin: 0;
                opacity: 0.9;
                font-size: 1rem;
            }

            .header-stats {
                display: flex;
                gap: 1.5rem;
            }

            .stat-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                background: rgba(255, 255, 255, 0.1);
                padding: 1rem 1.5rem;
                border-radius: 12px;
                backdrop-filter: blur(10px);
            }

            .stat-number {
                font-size: 2rem;
                font-weight: 700;
                line-height: 1;
            }

            .stat-label {
                font-size: 0.875rem;
                opacity: 0.9;
                margin-top: 0.25rem;
            }

            /* Appointments Grid */
            .appointments-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
                gap: 1.5rem;
                padding: 0 1rem;
            }

            /* Appointment Card */
            .appointment-card {
                background: white;
                border-radius: 16px;
                padding: 1.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                border: 1px solid #e5e7eb;
            }

            .appointment-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            /* Doctor Info */
            .doctor-info {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid #f3f4f6;
            }

            .doctor-avatar {
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.25rem;
                flex-shrink: 0;
            }

            .doctor-name {
                font-size: 1.125rem;
                font-weight: 600;
                color: #1f2937;
                margin: 0 0 0.25rem 0;
            }

            .doctor-role {
                color: #6b7280;
                font-size: 0.875rem;
                margin: 0;
            }

            /* Appointment Info */
            .appointment-info {
                margin-bottom: 1.5rem;
            }

            .info-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
            }

            .info-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #4b5563;
                font-size: 0.875rem;
            }

            .info-item i {
                color: #6b7280;
                width: 16px;
            }

            .status-badge {
                padding: 0.375rem 0.75rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .status-badge.completed {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }

            /* NEW: Medical Detail Section (for Symptoms and Diagnosis) */
            .medical-detail-section {
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid #f3f4f6;
            }

            .medical-text {
                color: #4b5563;
                font-size: 0.9rem;
                line-height: 1.5;
                margin-top: 0.75rem;
            }

            /* Prescriptions Section */
            .prescriptions-section {
                margin-bottom: 1.5rem;
            }

            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
            }

            .section-title {
                font-size: 1rem;
                font-weight: 600;
                color: #1f2937;
                margin: 0;
            }

            .prescription-count {
                background: #4f46e5;
                color: white;
                padding: 0.25rem 0.75rem;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 500;
            }

            .prescriptions-list {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .prescription-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 1rem;
                background: #f8fafc;
                border-radius: 8px;
                border: 1px solid #e5e7eb;
                transition: all 0.2s ease;
            }

            .prescription-item:hover {
                background: #f1f5f9;
                border-color: #4f46e5;
            }

            .prescription-info {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .prescription-id {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .id-badge {
                background: #4f46e5;
                color: white;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .id-text {
                font-weight: 500;
                color: #1f2937;
                font-size: 0.875rem;
            }

            .prescription-date {
                display: flex;
                align-items: center;
                gap: 0.375rem;
                color: #6b7280;
                font-size: 0.75rem;
            }

            .view-btn {
                display: flex;
                align-items: center;
                gap: 0.375rem;
                padding: 0.375rem 0.75rem;
                background: white;
                color: #4f46e5;
                border: 1px solid #4f46e5;
                border-radius: 6px;
                text-decoration: none;
                font-size: 0.75rem;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .view-btn:hover {
                background: #4f46e5;
                color: white;
            }

            /* Action Button */
            .card-actions {
                margin-top: 1.5rem;
                padding-top: 1rem;
                border-top: 1px solid #f3f4f6;
            }

            .primary-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                width: 100%;
                padding: 0.875rem 1rem;
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .primary-btn:hover {
                background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
                color: white;
                transform: translateY(-1px);
            }

            .btn-badge {
                background: rgba(255, 255, 255, 0.2);
                padding: 0.125rem 0.5rem;
                border-radius: 10px;
                font-size: 0.75rem;
            }

            /* No Prescription State */
            .no-prescription {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1.5rem;
                background: rgba(245, 158, 11, 0.05);
                border: 1px solid rgba(245, 158, 11, 0.2);
                border-radius: 8px;
                margin-bottom: 1.5rem;
            }

            .no-prescription-icon {
                color: #f59e0b;
                font-size: 2rem;
                flex-shrink: 0;
            }

            .no-prescription-text h4 {
                margin: 0 0 0.25rem 0;
                color: #1f2937;
                font-size: 1rem;
                font-weight: 600;
            }

            .no-prescription-text p {
                margin: 0;
                color: #6b7280;
                font-size: 0.875rem;
            }

            /* Empty State */
            .empty-state {
                grid-column: 1 / -1;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 3rem;
                background: white;
                border-radius: 16px;
                border: 2px dashed #e5e7eb;
                text-align: center;
            }

            .empty-icon {
                color: #9ca3af;
                font-size: 4rem;
                margin-bottom: 1.5rem;
            }

            .empty-content h3 {
                color: #1f2937;
                font-size: 1.5rem;
                font-weight: 600;
                margin: 0 0 0.5rem 0;
            }

            .empty-content p {
                color: #6b7280;
                margin: 0 0 1.5rem 0;
                font-size: 1rem;
            }

            .cta-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.875rem 1.5rem;
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .cta-btn:hover {
                background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
                color: white;
                transform: translateY(-1px);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .appointments-grid {
                    grid-template-columns: 1fr;
                    padding: 0 0.5rem;
                }

                .header-content {
                    flex-direction: column;
                    text-align: center;
                    gap: 1.5rem;
                }

                .info-row {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 0.75rem;
                }

                .prescription-item {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 0.75rem;
                }

                .view-btn {
                    justify-content: center;
                }
            }

            @media (max-width: 480px) {
                .appointment-card {
                    padding: 1rem;
                }

                .page-header {
                    padding: 1.5rem 0;
                }

                .page-title {
                    font-size: 1.5rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Khởi tạo lại Lucide icons nếu chúng được thêm động hoặc nếu trang này tải qua AJAX
            // Đảm bảo thư viện Lucide đã được tải trước đó trong client.layouts.app
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        </script>
    @endpush
@endsection
