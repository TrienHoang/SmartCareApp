@extends('client.layouts.app')

@section('title', 'Chi tiết khám bệnh')


@section('content')
    <div class="container mx-auto px-4 py-8">
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
                        <a href="#thong-tin-ca-nhan"
                            class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600 border-l-4 border-blue-600">
                            <i class="bx bx-user text-lg"></i>
                            <span class="font-medium">Thông Tin Cá Nhân</span>
                        </a>
                        <a href="{{ route('client.appointments.history') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600">
                            <i class="bx bx-calendar text-lg"></i>
                            <span>Lịch Sử Khám</span>
                        </a>
                        <a href="#lich-hen"
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
                        <a href="#thong-bao"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600">
                            <i class="bx bx-bell text-lg"></i>
                            <span>Thông Báo</span>
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
<div class="md:col-span-3" style="padding: 20px;">
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Thông tin cuộc hẹn -->
        <div style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                <i class="bx bx-calendar-check" style="font-size: 24px; margin-right: 8px;"></i>
                <h3 style="font-weight: 600;">Thông tin cuộc hẹn</h3>
            </div>
            <div style="margin-bottom: 10px;"><i class="bx bx-medical"></i> <strong>Dịch vụ:</strong> {{ $appointment->service->name ?? 'Dịch vụ khám' }}</div>
            <div style="margin-bottom: 10px;"><i class="bx bx-time"></i> <strong>Thời gian:</strong> {{ $appointment->appointment_time->format('d/m/Y H:i') }}</div>
            <div style="margin-bottom: 10px;"><i class="bx bx-user-circle"></i> <strong>Bác sĩ:</strong> {{ $appointment->doctor->user->full_name ?? 'Chưa rõ' }}</div>
            <div><i class="bx bx-note"></i> <strong>Lý do khám:</strong> {{ $appointment->reason ?? 'Không có ghi chú' }}</div>
        </div>

        <!-- Thông tin thanh toán -->
        @if ($appointment->payment)
        <div style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                <i class="bx bx-credit-card" style="font-size: 24px; margin-right: 8px;"></i>
                <h3 style="font-weight: 600;">Thanh toán</h3>
            </div>
            <div style="margin-bottom: 10px;"><i class="bx bx-money"></i> <strong>Số tiền:</strong> <span>{{ number_format($appointment->payment->amount, 0, ',', '.') }} đ</span></div>
            <div style="margin-bottom: 10px;"><i class="bx bx-wallet"></i> <strong>Phương thức:</strong> {{ $appointment->payment->payment_method ?? 'Không rõ' }}</div>
            <div>
                <i class="bx bx-check-circle"></i> <strong>Trạng thái:</strong>
                <span style="color: {{ $appointment->payment->status == 'paid' ? '#16a34a' : '#f59e0b' }}; font-weight: bold;">
                    {{ $appointment->payment->status ?? 'Chưa thanh toán' }}
                </span>
            </div>
        </div>
        @endif

        <!-- Hồ sơ khám -->
        @if ($appointment->medicalRecord)
        <div style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                <i class="bx bx-file-medical" style="font-size: 24px; margin-right: 8px;"></i>
                <h3 style="font-weight: 600;">Hồ sơ khám bệnh</h3>
            </div>
            <div style="margin-bottom: 10px;"><i class="bx bx-body"></i> <strong>Triệu chứng:</strong> {{ $appointment->medicalRecord->symptoms }}</div>
            <div style="margin-bottom: 10px;"><i class="bx bx-search-alt"></i> <strong>Chẩn đoán:</strong> {{ $appointment->medicalRecord->diagnosis }}</div>
            <div><i class="bx bx-first-aid"></i> <strong>Hướng điều trị:</strong> {{ $appointment->medicalRecord->treatment }}</div>
        </div>
        @endif

        <!-- Đơn thuốc -->
        @if (
            $appointment->medicalRecord &&
            $appointment->medicalRecord->prescription &&
            $appointment->medicalRecord->prescription->items->count()
        )
        <div style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                <i class="bx bx-capsule" style="font-size: 24px; margin-right: 8px;"></i>
                <h3 style="font-weight: 600;">Đơn thuốc</h3>
            </div>
            @foreach ($appointment->medicalRecord->prescription->items as $item)
            <div style="margin-bottom: 12px; padding-left: 12px; border-left: 3px solid #93c5fd;">
                <div><i class="bx bx-plus-medical"></i> <strong>{{ $item->medicine->name ?? 'Không rõ' }}</strong></div>
                <div style="font-size: 14px; margin-left: 20px;">
                    <div><i class="bx bx-hash"></i> <strong>Số lượng:</strong> {{ $item->quantity }}</div>
                    <div><i class="bx bx-info-circle"></i> <strong>Cách dùng:</strong> {{ $item->usage_instructions }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Đánh giá -->
        @if ($appointment->review)
        <div style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                <i class="bx bx-star" style="font-size: 24px; margin-right: 8px;"></i>
                <h3 style="font-weight: 600;">Đánh giá dịch vụ</h3>
            </div>
            <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 8px;">
                @for ($i = 1; $i <= 5; $i++)
                <i class="bx {{ $i <= $appointment->review->rating ? 'bxs-star' : 'bx-star' }}" style="color: #facc15;"></i>
                @endfor
                <span style="margin-left: 8px;">{{ $appointment->review->rating }}/5</span>
            </div>
            <div style="font-style: italic; color: #4b5563;">
                <i class="bx bx-quote-alt-left"></i>
                {{ $appointment->review->comment }}
                <i class="bx bx-quote-alt-right"></i>
            </div>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('client.appointments.history') }}"
               style="display: inline-block; margin-top: 20px; background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                <i class="bx bx-arrow-back"></i> Quay lại lịch sử khám
            </a>
        </div>
    </div>
</div>

        </div>
    </div>

    {{-- Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
@endsection