@extends('client.layouts.profile-layout')

@section('title', 'Chi tiết khám bệnh')

@section('profile-content')
     <div class="md:col-span-3" style="padding: 20px;">
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <!-- Thông tin cuộc hẹn -->
                    <div
                        style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
                        <div style="display: flex; align-items: center; margin-bottom: 16px;">
                            <i class="bx bx-calendar-check" style="font-size: 24px; margin-right: 8px;"></i>
                            <h3 style="font-weight: 600;">Thông tin cuộc hẹn</h3>
                        </div>
                        <div style="margin-bottom: 10px;"><i class="bx bx-medical"></i> <strong>Dịch vụ:</strong>
                            {{ $appointment->service->name ?? 'Dịch vụ khám' }}</div>
                        <div style="margin-bottom: 10px;"><i class="bx bx-time"></i> <strong>Thời gian:</strong>
                            {{ $appointment->appointment_time->format('d/m/Y H:i') }}</div>
                        <div style="margin-bottom: 10px;"><i class="bx bx-user-circle"></i> <strong>Bác sĩ:</strong>
                            {{ $appointment->doctor->user->full_name ?? 'Chưa rõ' }}</div>
                        <div><i class="bx bx-note"></i> <strong>Lý do khám:</strong>
                            {{ $appointment->reason ?? 'Không có ghi chú' }}</div>
                    </div>

                    <!-- Thông tin thanh toán -->
                    @if ($appointment->payment)
                        <div
                            style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
                            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                                <i class="bx bx-credit-card" style="font-size: 24px; margin-right: 8px;"></i>
                                <h3 style="font-weight: 600;">Thanh toán</h3>
                            </div>
                            <div style="margin-bottom: 10px;"><i class="bx bx-money"></i> <strong>Số tiền:</strong>
                                <span>{{ number_format($appointment->payment->amount, 0, ',', '.') }} đ</span></div>
                            <div style="margin-bottom: 10px;"><i class="bx bx-wallet"></i> <strong>Phương thức:</strong>
                                {{ $appointment->payment->payment_method ?? 'Không rõ' }}</div>
                            <div>
                                <i class="bx bx-check-circle"></i> <strong>Trạng thái:</strong>
                                <span
                                    style="color: {{ $appointment->payment->status == 'paid' ? '#16a34a' : '#f59e0b' }}; font-weight: bold;">
                                    {{ $appointment->payment->status ?? 'Chưa thanh toán' }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Hồ sơ khám -->
                    @if ($appointment->symptom_note)
                        <div
                            style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
                            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                                <i class="bx bx-file-medical" style="font-size: 24px; margin-right: 8px;"></i>
                                <h3 style="font-weight: 600;">Hồ sơ khám bệnh</h3>
                            </div>
                            <div style="margin-bottom: 10px;"><i class="bx bx-search-alt"></i> <strong>Chẩn đoán:</strong>
                                {{ $appointment->symptom_note}}</div>
                        </div>
                    @endif

                    <!-- Đơn thuốc -->
                    @if (
                        $appointment->medicalRecord &&
                            $appointment->medicalRecord->prescription &&
                            $appointment->medicalRecord->prescription->items->count())
                        <div
                            style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
                            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                                <i class="bx bx-capsule" style="font-size: 24px; margin-right: 8px;"></i>
                                <h3 style="font-weight: 600;">Đơn thuốc</h3>
                            </div>
                            @foreach ($appointment->medicalRecord->prescription->items as $item)
                                <div style="margin-bottom: 12px; padding-left: 12px; border-left: 3px solid #93c5fd;">
                                    <div><i class="bx bx-plus-medical"></i>
                                        <strong>{{ $item->medicine->name ?? 'Không rõ' }}</strong></div>
                                    <div style="font-size: 14px; margin-left: 20px;">
                                        <div><i class="bx bx-hash"></i> <strong>Số lượng:</strong> {{ $item->quantity }}
                                        </div>
                                        <div><i class="bx bx-info-circle"></i> <strong>Cách dùng:</strong>
                                            {{ $item->usage_instructions }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Đánh giá -->
                    @if ($appointment->review)
                        <div
                            style="background-color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
                            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                                <i class="bx bx-star" style="font-size: 24px; margin-right: 8px;"></i>
                                <h3 style="font-weight: 600;">Đánh giá dịch vụ</h3>
                            </div>
                            <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 8px;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bx {{ $i <= $appointment->review->rating ? 'bxs-star' : 'bx-star' }}"
                                        style="color: #facc15;"></i>
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

    {{-- Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
@endsection
