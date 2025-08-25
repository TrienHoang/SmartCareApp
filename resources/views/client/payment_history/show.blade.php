@extends('client.layouts.profile-layout')

@section('title', 'Chi tiết thanh toán')

@section('profile-content')
@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #f8fcff 0%, #e8f4f8 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }

    .medical-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .page-header {
        background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(44, 90, 160, 0.3);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .page-title {
        font-size: 2rem;
        font-weight: 600;
        margin: 0;
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .payment-details-card {
        background: #ffffff;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border-top: 4px solid #27a844;
    }

    .card-body {
        padding: 3rem 2rem;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2.5rem;
        margin-bottom: 2rem;
    }

    .detail-item {
        background: #f8fcff;
        padding: 2rem;
        border-radius: 15px;
        border-left: 5px solid #17a2b8;
        transition: all 0.3s ease;
        position: relative;
    }

    .detail-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.15);
        background: #f0f9ff;
    }

    .detail-item.amount-item {
        border-left-color: #28a745;
        background: linear-gradient(135deg, #f8fff9 0%, #f0f8f1 100%);
    }

    .detail-item.status-item {
        border-left-color: #28a745;
    }

    .detail-item.method-item {
        border-left-color: #6f42c1;
    }

    .detail-item.date-item {
        border-left-color: #fd7e14;
    }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .label-icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .amount-item .label-icon {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .status-item .label-icon {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .method-item .label-icon {
        background: linear-gradient(135deg, #6f42c1 0%, #6610f2 100%);
    }

    .date-item .label-icon {
        background: linear-gradient(135deg, #fd7e14 0%, #e83e8c 100%);
    }

    .detail-value {
        font-size: 1.3rem;
        font-weight: 600;
        color: #212529;
        line-height: 1.4;
    }

    .amount-value {
        font-size: 2rem;
        font-weight: 700;
        color: #28a745;
        text-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
    }

    .payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-cash {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }

    .badge-card {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }

    .badge-online {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
    }

    .status-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .notes-section {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        padding: 2rem;
        border-radius: 15px;
        border-left: 5px solid #ffc107;
        margin-top: 2rem;
    }

    .notes-section .detail-label {
        color: #856404;
        margin-bottom: 1rem;
    }

    .notes-section .label-icon {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }

    .notes-section .detail-value {
        color: #856404;
        font-style: italic;
        line-height: 1.6;
    }

    .action-section {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid #e9ecef;
        text-align: center;
    }

    .btn-back {
        background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(44, 90, 160, 0.3);
        border: none;
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #1e4080 0%, #2c5aa0 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(44, 90, 160, 0.4);
        color: white;
        text-decoration: none;
    }

    .medical-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 60px;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.1) 50%, transparent 100%);
        background-size: 30px 30px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .medical-container {
            padding: 1rem 0.5rem;
        }
        
        .page-header {
            padding: 2rem 1.5rem;
            border-radius: 15px 15px 0 0;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .card-body {
            padding: 2rem 1.5rem;
        }
        
        .details-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .detail-item {
            padding: 1.5rem;
        }
        
        .detail-value {
            font-size: 1.1rem;
        }
        
        .amount-value {
            font-size: 1.5rem;
        }
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div class="medical-container">
    <div class="page-header">
        <div class="medical-pattern"></div>
        <h1 class="page-title">
            <div class="header-icon">
                <i class="fas fa-file-medical-alt"></i>
            </div>
            Chi tiết thanh toán y tế
        </h1>
    </div>

    <div class="payment-details-card">
        <div class="card-body">
            <div class="details-grid">
                <div class="detail-item date-item">
                    <div class="detail-label">
                        <div class="label-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        Ngày thanh toán
                    </div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($paymentHistory->payment_date)->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="detail-item method-item">
                    <div class="detail-label">
                        <div class="label-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        Phương thức thanh toán
                    </div>
                    <div class="detail-value">
                       <span class="badge badge-method
                        {{ $paymentHistory->payment_method === 'cash' ? 'bg-secondary' :
                           ($paymentHistory->payment_method === 'card' ? 'bg-info text-dark' : 'bg-primary') }}">
                        {{ ucfirst($paymentHistory->payment_method) }}
                    </span>
                    </div>
                </div>

                <div class="detail-item amount-item pulse-animation">
                    <div class="detail-label">
                        <div class="label-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        Số tiền thanh toán
                    </div>
                    <div class="detail-value amount-value">
                        {{ number_format($paymentHistory->amount, 0, ',', '.') }} VNĐ
                    </div>
                </div>

                <div class="detail-item status-item">
                    <div class="detail-label">
                        <div class="label-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        Trạng thái
                    </div>
                    <div class="detail-value">
                        <span class="">
                            Thanh toán thành công
                        </span>
                    </div>
                </div>
            </div>

            @isset($paymentHistory->note)
            <div class="notes-section">
                <div class="detail-label">
                    <div class="label-icon">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    Ghi chú từ phòng khám
                </div>
                <div class="detail-value">{{ $paymentHistory->note }}</div>
            </div>
            @endisset

            <div class="action-section">
                <a href="{{ route('client.payment_history.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại lịch sử thanh toán
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://kit.fontawesome.com/a2d9d6a06d.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Thêm hiệu ứng fade-in cho các elements
    const detailItems = document.querySelectorAll('.detail-item');
    detailItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
</script>
@endpush