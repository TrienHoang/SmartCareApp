@extends('client.layouts.app')

@section('title', 'Chi tiết thanh toán')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body {
        background-color: #f8f9fa;
    }

    .payment-card {
        background: #fff;
        border-left: 6px solid #0d6efd;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
    }

    .payment-card:hover {
        transform: scale(1.01);
    }

    .section-title {
        color: #0d6efd;
        font-weight: 700;
        margin-bottom: 2rem;
    }

    .detail-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.95rem;
    }

    .detail-value {
        font-weight: 500;
        font-size: 1.1rem;
        color: #212529;
    }

    .badge-method {
        padding: 0.45rem 0.9rem;
        font-size: 0.95rem;
        border-radius: 0.5rem;
    }

    .btn-back {
        background-color: #0d6efd;
        color: #fff;
        border-radius: 0.5rem;
    }

    .btn-back:hover {
        background-color: #0b5ed7;
    }

    @media (max-width: 768px) {
        .detail-label {
            font-size: 0.85rem;
        }

        .detail-value {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <h2 class="section-title"><i class="fas fa-file-invoice-dollar me-2"></i>Chi tiết thanh toán</h2>

    <div class="payment-card">
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="detail-label">📅 Ngày thanh toán</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($paymentHistory->payment_date)->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="detail-label">💳 Phương thức thanh toán</div>
                <div class="detail-value">
                    <span class="badge badge-method
                        {{ $paymentHistory->payment_method === 'cash' ? 'bg-secondary' :
                           ($paymentHistory->payment_method === 'card' ? 'bg-info text-dark' : 'bg-primary') }}">
                        {{ ucfirst($paymentHistory->payment_method) }}
                    </span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="detail-label">💰 Số tiền</div>
                <div class="detail-value text-success">
                    {{ number_format($paymentHistory->amount, 0, ',', '.') }} VNĐ
                </div>
            </div>

            <div class="col-md-6">
                <div class="detail-label">✅ Trạng thái</div>
                <div class="detail-value">
                    <span class="badge bg-success px-3 py-2">Thành công</span>
                </div>
            </div>

            @isset($paymentHistory->note)
                <div class="col-12">
                    <div class="detail-label">📝 Ghi chú</div>
                    <div class="detail-value">{{ $paymentHistory->note }}</div>
                </div>
            @endisset
        </div>

        <div class="mt-5 text-end">
            <a href="{{ route('client.payment_history.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://kit.fontawesome.com/a2d9d6a06d.js" crossorigin="anonymous"></script>
@endpush
