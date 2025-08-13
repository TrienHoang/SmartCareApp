@extends('client.layouts.app')

@section('title', 'Chi Tiết Lịch Hẹn')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
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
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                <span>Đơn Thuốc</span>
                            </a>
                            <a href="{{ route('client.appointments.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600 border-l-4 border-blue-600">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                                <span class="font-semibold">Lịch Hẹn</span>
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

                {{-- Main Content: Chi Tiết Lịch Hẹn --}}
                <div class="lg:w-3/4">
                    <!-- Header Section -->
                    <div class="gradient-bg text-white py-8 mb-8 rounded-2xl">
                        <div class="max-w-6xl mx-auto px-6">
                            <div class="flex flex-col md:flex-row items-center justify-between">
                                <div class="mb-4 md:mb-0">
                                    <h1 class="text-3xl font-bold mb-2">
                                        <i class="fas fa-calendar-check mr-3"></i>Chi tiết lịch hẹn
                                    </h1>
                                    <p class="text-blue-100">Thông tin đầy đủ về cuộc hẹn của bạn</p>
                                </div>
                                <div class="text-center md:text-right">
                                    <div class="text-sm text-blue-100">Mã lịch hẹn</div>
                                    <div class="text-2xl font-bold">#{{ $appointment->id ?? 'AP001' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="max-w-6xl mx-auto px-6 pb-12">
                        <!-- Status Card -->
                        <div class="mb-8">
                            <div class="bg-white rounded-2xl card-shadow p-6 border-l-4 border-blue-500">
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                                    <div class="flex items-center mb-4 md:mb-0">
                                        <div
                                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                            <i class="fas fa-clock text-blue-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800">Trạng thái lịch hẹn</h3>
                                            <p class="text-gray-600">
                                                {{ $appointment->formatted_time ?? 'Thứ 2, 25/12/2023 - 09:00' }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="status-badge px-4 py-2 text-sm font-semibold rounded-full {{ $appointment->statusClass() ?? 'bg-blue-100 text-blue-800' }}">
                                        {{ $appointment->status_text ?? 'Đã xác nhận' }}
                                    </span>
                                </div>

                             
                            </div>
                        </div>

                        <!-- QR Code Section -->
                      @if ($appointment->status !== 'cancelled' && $appointment->status !== 'pending')
                            <div class="mb-8">
                                <div class="bg-white rounded-2xl card-shadow p-6 text-center">
                                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                                        <i class="fas fa-qrcode mr-2 text-blue-600"></i>
                                        Mã QR Check-in
                                    </h2>
                                    <div class="flex justify-center">
                                        <img src="{{ route('qr.generate', ['data' => $appointment->qr_code]) }}"
                                            alt="Mã QR Check-in" class="border border-gray-300 rounded-lg shadow-md"
                                            style="width: 200px; height: 200px;">
                                    </div>
                                    <p class="text-sm text-gray-500 mt-3">Quét mã QR này khi đến khám</p>
                                </div>
                            </div>
                        @endif


                        <!-- Patient & Doctor Info Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                            <!-- Thông tin bệnh nhân -->
                            <div class="info-card bg-white rounded-2xl card-shadow p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-user-injured text-green-600 text-xl"></i>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-800">Thông tin bệnh nhân</h2>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-id-card text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Họ và tên</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->patient->full_name ?? 'Nguyễn Văn A' }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-envelope text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Email</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->patient->email ?? 'nguyenvana@email.com' }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-phone text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Số điện thoại</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->patient->phone ?? '0123 456 789' }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-venus-mars text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Giới tính</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->patient->gender ?? 'Nam' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin bác sĩ -->
                            <div class="info-card bg-white rounded-2xl card-shadow p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-user-md text-blue-600 text-xl"></i>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-800">Thông tin bác sĩ</h2>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-user-tie text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Tên bác sĩ</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->doctor->user->full_name ?? 'BS. Trần Thị B' }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-stethoscope text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Chuyên khoa</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->doctor->specialization ?? 'Nội tổng quát' }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-venus-mars text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Giới tính</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->doctor->gender ?? 'Nữ' }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-envelope text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Email</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->doctor->user->email ?? 'bs.tranthib@hospital.com' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Information -->
                        <div class="mb-8">
                            <div class="info-card bg-white rounded-2xl card-shadow p-6">
                                <div class="flex items-center mb-6">
                                    <div
                                        class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-medical-bag text-purple-600 text-xl"></i>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-800">Thông tin dịch vụ khám</h2>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-tag text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Tên dịch vụ</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->service->name ?? 'Khám tổng quát' }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-money-bill-wave text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Giá dịch vụ</div>
                                            <div class="font-semibold text-green-600 text-lg">
                                                {{ number_format($appointment->service->price ?? 500000, 0, ',', '.') }}₫
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-user-md text-gray-400 mr-3 mt-1"></i>
                                        <div>
                                            <div class="text-sm text-gray-500 mb-1">Chuyên khoa</div>
                                            <div class="font-medium text-gray-800">
                                                {{ $appointment->doctor->specialization ?? 'Nội tổng quát' }}</div>
                                        </div>
                                    </div>
                                </div>

                                @if ($appointment->service->description ?? 'Khám sức khỏe tổng quát bao gồm kiểm tra các chỉ số cơ bản')
                                    <div class="pt-4 border-t border-gray-100">
                                        <div class="text-sm text-gray-500 mb-2">Mô tả dịch vụ</div>
                                        <div class="text-gray-700 leading-relaxed">
                                            {{ $appointment->service->description ?? 'Khám sức khỏe tổng quát bao gồm kiểm tra các chỉ số cơ bản, đo huyết áp, cân nặng, và tư vấn sức khỏe.' }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Notes Section -->
                        @if ($appointment->reason ?? 'Khám tổng quát định kỳ')
                            <div class="mb-8">
                                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-sticky-note text-blue-500 mr-3"></i>
                                        <h3 class="text-lg font-semibold text-blue-800">Ghi chú khám bệnh</h3>
                                    </div>
                                    <p class="text-blue-700 leading-relaxed">
                                        {{ $appointment->reason ?? 'Khám tổng quát định kỳ' }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Cancel Reason (if exists) -->
                        @if ($appointment->cancel_reason ?? false)
                            <div class="mb-8">
                                <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                                        <h3 class="text-lg font-semibold text-red-800">Lý do hủy lịch hẹn</h3>
                                    </div>
                                    <p class="text-red-700 leading-relaxed">{{ $appointment->cancel_reason }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-4 justify-center">
                            <a href="{{ route('client.appointments.index') ?? '#' }}"
                                class="flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Quay lại danh sách
                            </a>

                            @if (($appointment->status ?? 'pending') === 'pending')
                                <button onclick="confirmCancel()"
                                    class="flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                                    <i class="fas fa-times mr-2"></i>
                                    Hủy lịch hẹn
                                </button>
                            @endif

                            {{-- <button onclick="printAppointment()"
                                class="flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-print mr-2"></i>
                                In lịch hẹn
                            </button> --}}
                        </div>
                    </div>
                </div>

                <style>
                    .gradient-bg {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    }

                    .card-shadow {
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                    }

                    .info-card {
                        transition: transform 0.2s ease-in-out;
                    }

                    .info-card:hover {
                        transform: translateY(-2px);
                    }

                    @media print {
                        .no-print {
                            display: none;
                        }
                    }
                </style>

                <script>
                    function confirmCancel() {
                        if (confirm('Bạn có chắc chắn muốn hủy lịch hẹn này không?')) {
                            // Handle cancel appointment
                            alert('Chức năng hủy lịch hẹn sẽ được triển khai');
                        }
                    }

                    function printAppointment() {
                        window.print();
                    }
                </script>

                <script>
                    function confirmCancel() {
                        if (confirm('Bạn có chắc chắn muốn hủy lịch hẹn này không?')) {
                            // Thực hiện hủy lịch hẹn
                            console.log('Hủy lịch hẹn');
                        }
                    }

                    function printAppointment() {
                        window.print();
                    }

                    // Animation cho các card khi load trang
                    document.addEventListener('DOMContentLoaded', function() {
                        const cards = document.querySelectorAll('.info-card');
                        cards.forEach((card, index) => {
                            setTimeout(() => {
                                card.style.opacity = '0';
                                card.style.transform = 'translateY(20px)';
                                card.style.transition = 'all 0.6s ease';

                                setTimeout(() => {
                                    card.style.opacity = '1';
                                    card.style.transform = 'translateY(0)';
                                }, 100);
                            }, index * 200);
                        });
                    });
                </script>
            </div>
        </div>
    </div>
    </div>

    @push('styles')
        <style>
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .card-shadow {
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .info-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .info-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            }

            .status-badge {
                animation: pulse 2s infinite;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.8;
                }
            }

            /* General Detail Wrapper */
            .appointment-detail-wrapper {
                padding: 0;
                background: none;
            }

            /* Detail Header */
            .detail-header {
                margin-bottom: 2.5rem;
                padding-bottom: 1.5rem;
                border-bottom: 1px solid #e5e7eb;
            }

            .detail-title {
                font-size: 2.25rem;
                font-weight: 700;
                color: #1f2937;
                display: flex;
                align-items: center;
                margin: 0;
            }

            /* Detail Card Section */
            .detail-card-section {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1.5rem;
                margin-bottom: 3rem;
            }

            .detail-card {
                background: #f8fafc;
                border-radius: 12px;
                padding: 1.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                border: 1px solid #e5e7eb;
            }

            .card-icon {
                flex-shrink: 0;
                padding: 0.75rem;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .card-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: #1f2937;
                margin: 0 0 0.25rem 0;
            }

            .card-text {
                font-size: 1rem;
                color: #374151;
                font-weight: 500;
                margin: 0 0 0.25rem 0;
            }

            .card-subtitle {
                font-size: 0.875rem;
                color: #6b7280;
                margin: 0;
            }

            /* Status Badge in Detail */
            .status-badge-detail {
                display: inline-flex;
                align-items: center;
                padding: 0.2em 0.6em;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
                line-height: 1;
                margin-left: 0.5rem;
            }

            /* Medical Record Section */
            .medical-record-section {
                background: #fdfefe;
                border: 1px solid #e0e7eb;
                border-radius: 12px;
                padding: 2rem;
                margin-bottom: 2.5rem;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            }

            .section-heading {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                padding-bottom: 1rem;
                border-bottom: 1px dashed #e5e7eb;
            }

            .info-block {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 1.25rem;
                margin-bottom: 1rem;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            }

            .info-block-title {
                font-size: 1rem;
                font-weight: 600;
                color: #1f2937;
                margin: 0 0 0.75rem 0;
                display: flex;
                align-items: center;
            }

            .info-block-text {
                font-size: 0.95rem;
                color: #4b5563;
                line-height: 1.6;
                margin: 0;
            }

            .prescription-list-detail {
                list-style: none;
                padding: 0;
                margin: 0.75rem 0 0 0;
            }

            .prescription-list-detail li {
                margin-bottom: 0.5rem;
            }

            .prescription-item-link {
                display: flex;
                align-items: center;
                padding: 0.75rem 1rem;
                background: #f3f4f6;
                border-radius: 6px;
                text-decoration: none;
                color: #1f2937;
                transition: background-color 0.2s ease, transform 0.2s ease;
                border: 1px solid #e5e7eb;
            }

            .prescription-item-link:hover {
                background-color: #e5e7eb;
                transform: translateX(5px);
            }

            .no-medical-record-info {
                background-color: #fffbeb;
                /* yellow-50 */
                color: #d97706;
                /* yellow-700 */
                padding: 1rem;
                border-radius: 8px;
                display: flex;
                align-items: center;
                font-size: 0.9rem;
                margin-bottom: 2.5rem;
                border: 1px solid #fef3c7;
            }

            /* Action Buttons Detail */
            .action-buttons-detail {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                justify-content: flex-end;
                padding-top: 1.5rem;
                border-top: 1px solid #e5e7eb;
            }

            .btn-secondary-back,
            .btn-edit-appointment,
            .btn-cancel-appointment {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1.25rem;
                border-radius: 8px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
                cursor: pointer;
                border: none;
            }

            .btn-secondary-back {
                background-color: #e5e7eb;
                color: #4b5563;
            }

            .btn-secondary-back:hover {
                background-color: #d1d5db;
                transform: translateY(-1px);
            }

            .btn-edit-appointment {
                background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
                color: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .btn-edit-appointment:hover {
                background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
                transform: translateY(-1px);
                box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            }

            .btn-cancel-appointment {
                background-color: #ef4444;
                /* red-500 */
                color: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .btn-cancel-appointment:hover {
                background-color: #dc2626;
                /* red-600 */
                transform: translateY(-1px);
                box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .detail-title {
                    font-size: 1.75rem;
                    justify-content: center;
                    text-align: center;
                }

                .detail-header {
                    text-align: center;
                }

                .detail-card-section {
                    grid-template-columns: 1fr;
                }

                .detail-card {
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }

                .action-buttons-detail {
                    flex-direction: column;
                    align-items: stretch;
                }

                .btn-secondary-back,
                .btn-edit-appointment,
                .btn-cancel-appointment {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        </script>
    @endpush
@endsection
