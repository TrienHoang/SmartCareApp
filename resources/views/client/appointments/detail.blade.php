@extends('client.layouts.app')

@section('title', 'Chi tiết khám bệnh')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #83ccde 30%, #b4e0f5 50%);
    min-height: 100vh;
    color: #333;
}

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        color: white;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        animation: fadeInDown 0.8s ease;
    }

    .page-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        animation: fadeInUp 0.8s ease 0.2s both;
    }

    .appointment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .appointment-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    .info-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        animation: slideInUp 0.6s ease;
    }

    .info-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.15);
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f8f9fa;
    }

    .card-icon {
        font-size: 2rem;
        margin-right: 1rem;
        color: #667eea;
        animation: pulse 2s infinite;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: rgba(102, 126, 234, 0.05);
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .info-item:hover {
        background: rgba(102, 126, 234, 0.1);
        transform: translateX(5px);
    }

    .info-item i {
        font-size: 1.2rem;
        margin-right: 0.75rem;
        color: #667eea;
        width: 25px;
        text-align: center;
    }

    .info-label {
        font-weight: 600;
        color: #555;
        margin-right: 0.5rem;
    }

    .info-value {
        color: #333;
        flex: 1;
    }

    .prescription-item {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid #28a745;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .prescription-item:hover {
        background: rgba(255, 255, 255, 0.95);
        transform: translateX(5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .medicine-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #28a745;
        margin-bottom: 0.5rem;
    }

    .medicine-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .medicine-detail {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: #666;
    }

    .medicine-detail i {
        margin-right: 0.5rem;
        color: #28a745;
    }

    .payment-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    .payment-paid {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .payment-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .rating-stars {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-bottom: 1rem;
    }

    .star {
        color: #ffc107;
        font-size: 1.2rem;
    }

    .rating-text {
        margin-left: 0.5rem;
        font-weight: 600;
        color: #333;
    }

    .review-comment {
        background: rgba(255, 193, 7, 0.1);
        border-radius: 10px;
        padding: 1rem;
        border-left: 4px solid #ffc107;
        font-style: italic;
        color: #333;
    }

    .back-button {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .back-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .back-button:active {
        transform: translateY(-1px);
    }

    .amount-display {
        font-size: 1.5rem;
        font-weight: 700;
        color: #28a745;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    .full-width-card {
        grid-column: 1 / -1;
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

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

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .info-card {
            padding: 1.5rem;
        }
        
        .medicine-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="bx bx-clipboard"></i> Chi tiết khám bệnh</h1>
        <p>Thông tin chi tiết về cuộc hẹn khám bệnh của bạn</p>
    </div>

    <div class="appointment-grid">
        <!-- Thông tin cuộc hẹn -->
        <div class="info-card">
            <div class="card-header">
                <i class="bx bx-calendar-check card-icon"></i>
                <h3 class="card-title">Thông tin cuộc hẹn</h3>
            </div>
            <div class="info-item">
                <i class="bx bx-medical"></i>
                <span class="info-label">Dịch vụ:</span>
                <span class="info-value">{{ $appointment->service->name ?? 'Dịch vụ khám' }}</span>
            </div>
            <div class="info-item">
                <i class="bx bx-time"></i>
                <span class="info-label">Thời gian:</span>
                <span class="info-value">{{ $appointment->appointment_time->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-item">
                <i class="bx bx-user-circle"></i>
                <span class="info-label">Bác sĩ:</span>
                <span class="info-value">{{ $appointment->doctor->user->full_name ?? 'Chưa rõ' }}</span>
            </div>
            <div class="info-item">
                <i class="bx bx-note"></i>
                <span class="info-label">Lý do khám:</span>
                <span class="info-value">{{ $appointment->reason ?? 'Không có ghi chú' }}</span>
            </div>
        </div>

        <!-- Thông tin thanh toán -->
        @if ($appointment->payment)
        <div class="info-card">
            <div class="card-header">
                <i class="bx bx-credit-card card-icon"></i>
                <h3 class="card-title">Thanh toán</h3>
            </div>
            <div class="info-item">
                <i class="bx bx-money"></i>
                <span class="info-label">Số tiền:</span>
                <span class="info-value amount-display">{{ number_format($appointment->payment->amount, 0, ',', '.') }} đ</span>
            </div>
            <div class="info-item">
                <i class="bx bx-wallet"></i>
                <span class="info-label">Phương thức:</span>
                <span class="info-value">{{ $appointment->payment->payment_method ?? 'Không rõ' }}</span>
            </div>
            <div class="info-item">
                <i class="bx bx-check-circle"></i>
                <span class="info-label">Trạng thái:</span>
                <span class="payment-status {{ $appointment->payment->status == 'paid' ? 'payment-paid' : 'payment-pending' }}">
                    {{ $appointment->payment->status ?? 'Chưa thanh toán' }}
                </span>
            </div>
        </div>
        @endif

        <!-- Hồ sơ khám -->
        @if ($appointment->medicalRecord)
        <div class="info-card full-width-card">
            <div class="card-header">
                <i class="bx bx-file-medical card-icon"></i>
                <h3 class="card-title">Hồ sơ khám bệnh</h3>
            </div>
            <div class="info-item">
                <i class="bx bx-body"></i>
                <span class="info-label">Triệu chứng:</span>
                <span class="info-value">{{ $appointment->medicalRecord->symptoms }}</span>
            </div>
            <div class="info-item">
                <i class="bx bx-search-alt"></i>
                <span class="info-label">Chẩn đoán:</span>
                <span class="info-value">{{ $appointment->medicalRecord->diagnosis }}</span>
            </div>
            <div class="info-item">
                <i class="bx bx-first-aid"></i>
                <span class="info-label">Hướng điều trị:</span>
                <span class="info-value">{{ $appointment->medicalRecord->treatment }}</span>
            </div>
        </div>
        @endif

        <!-- Đơn thuốc -->
        @if ($appointment->prescription && $appointment->prescription->items->count())
        <div class="info-card full-width-card">
            <div class="card-header">
                <i class="bx bx-capsule card-icon"></i>
                <h3 class="card-title">Đơn thuốc</h3>
            </div>
            @foreach ($appointment->prescription->items as $item)
                <div class="prescription-item">
                    <div class="medicine-name">
                        <i class="bx bx-plus-medical"></i>
                        {{ $item->medicine->name ?? 'Không rõ' }}
                    </div>
                    <div class="medicine-details">
                        <div class="medicine-detail">
                            <i class="bx bx-hash"></i>
                            <strong>Số lượng:</strong> {{ $item->quantity }}
                        </div>
                        <div class="medicine-detail">
                            <i class="bx bx-info-circle"></i>
                            <strong>Cách dùng:</strong> {{ $item->usage_instructions }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Đánh giá -->
        @if ($appointment->review)
        <div class="info-card full-width-card">
            <div class="card-header">
                <i class="bx bx-star card-icon"></i>
                <h3 class="card-title">Đánh giá dịch vụ</h3>
            </div>
            <div class="rating-stars">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="bx {{ $i <= $appointment->review->rating ? 'bxs-star' : 'bx-star' }} star"></i>
                @endfor
                <span class="rating-text">{{ $appointment->review->rating }}/5</span>
            </div>
            <div class="review-comment">
                <i class="bx bx-quote-alt-left"></i>
                {{ $appointment->review->comment }}
                <i class="bx bx-quote-alt-right"></i>
            </div>
        </div>
        @endif
    </div>

    <!-- Nút quay lại -->
    <div class="text-center mt-4">
        <a href="{{ route('client.appointments.history') }}" class="back-button">
            <i class="bx bx-arrow-back"></i>
            Quay lại lịch sử khám
        </a>
    </div>
</div>
@endsection
