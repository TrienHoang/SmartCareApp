@extends('client.layouts.app')

@section('title', 'Lịch sử khám bệnh')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                    <!-- Avatar -->
                    <div class="text-center mb-6">
                        <div class="relative inline-block">
                            <img id="profile-avatar" src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}"
                                alt="Avatar"
                                class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-blue-100 object-cover">
                            <button onclick="openAvatarModal()"
                                class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 shadow">
                                <i class="bx bx-camera text-white text-sm"></i>
                            </button>
                        </div>
                        <h3 class="text-lg font-semibold">{{ auth()->user()->name ?? 'Người dùng' }}</h3>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email ?? 'email@example.com' }}</p>
                    </div>

                    <!-- Menu -->
                    <nav class="space-y-2">
                        <a href="{{ route('client.profile.show') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600">
                            <i class="bx bx-user text-lg"></i>
                            <span>Thông Tin Cá Nhân</span>
                        </a>
                        <a href="{{ route('client.appointments.history') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600 border-l-4 border-blue-600">
                            <i class="bx bx-calendar text-lg"></i>
                            <span class="font-medium">Lịch Sử Khám</span>
                        </a>
                        <a href="{{ route('client.appointments.index') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600">
                            <i class="bx bx-time text-lg"></i>
                            <span>Lịch Hẹn</span>
                        </a>
                        <a href="#ho-so-y-te"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600">
                            <i class="bx bx-file text-lg"></i>
                            <span>Hồ Sơ Y Tế</span>
                        </a>
                        <a href="{{ route('client.uploads.index') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600">
                            <i class="bx bx-upload text-lg"></i>
                            <span>Upload File</span>
                        </a>
                        <a href="{{ route('client.notifications.index') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span>Thông Báo</span>
                            @php
                                $currentUnreadCount = $notifications->where('userStatuses.0.is_read', false)->count();
                            @endphp
                            @if ($currentUnreadCount > 0)
                                <span id="unreadCount"
                                    class="ml-2 px-2 py-0.5 bg-red-500 text-white rounded-full text-xs font-semibold">
                                    {{ $currentUnreadCount }}
                                </span>
                            @endif
                        </a>
                        <a href="#cai-dat"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600">
                            <i class="bx bx-cog text-lg"></i>
                            <span>Cài Đặt</span>
                        </a>
                        <a href="{{ route('client.payment_history.index') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600">
                            <i class="bx bx-receipt text-lg"></i>
                            <span>Lịch sử thanh toán</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-3 space-y-6">
                <!-- Lịch sử khám bệnh -->
                <div id="history" class="bg-white rounded-lg shadow p-6">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">🩺 Lịch sử khám bệnh của bạn</h2>
                    </div>

                    @if ($appointments->isEmpty())
                        <div class="bg-blue-50 border border-blue-200 text-blue-600 p-4 rounded text-center">
                            Bạn chưa có lịch sử khám bệnh nào.
                        </div>
                    @else
                        @foreach ($appointments as $appointment)
                            <div
                                class="bg-white border-l-4 border-indigo-500 rounded-lg shadow-md p-5 mb-5 hover:shadow-lg transition">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                    {{ $appointment->service->name ?? 'Dịch vụ khám' }}
                                </h3>

                                <div class="flex items-center mb-2 text-sm text-gray-600">
                                    <i class="bx bx-calendar mr-2 text-indigo-500"></i>
                                    <strong class="w-36">Ngày khám:</strong>
                                    <span>{{ $appointment->appointment_time->format('d/m/Y H:i') }}</span>
                                </div>

                                <div class="flex items-center mb-2 text-sm text-gray-600">
                                    <i class="bx bx-user mr-2 text-indigo-500"></i>
                                    <strong class="w-36">Bác sĩ:</strong>
                                    <span>{{ $appointment->doctor->user->full_name ?? 'Chưa rõ' }}</span>
                                </div>

                                <div class="flex items-center mb-2 text-sm text-gray-600">
                                    <i class="bx bx-notepad mr-2 text-indigo-500"></i>
                                    <strong class="w-36">Chẩn đoán:</strong>
                                    <span>{{ $appointment->medicalRecord->diagnosis ?? 'Chưa có thông tin' }}</span>
                                </div>

                                <div class="flex items-center mb-2 text-sm text-gray-600">
                                    <i class="bx bx-credit-card mr-2 text-indigo-500"></i>
                                    <strong class="w-36">Thanh toán:</strong>
                                    <span>{{ $appointment->payment->status ?? 'Chưa thanh toán' }}</span>
                                </div>

                                <a href="{{ route('client.appointments.detail', $appointment->id) }}"
                                    class="inline-block mt-4 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md transition">
                                    <i class="bx bx-detail mr-1"></i> Xem chi tiết
                                </a>

                                <div class="mt-4">
                                    <a href="{{ route('doctor.show', $appointment->doctor->id) }}"
                                        class="inline-block text-sm font-medium bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition">
                                        <i class="bx bx-star mr-1"></i> Đánh giá bác sĩ
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Load Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
@endsection
