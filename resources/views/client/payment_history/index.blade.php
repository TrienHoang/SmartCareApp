@extends('client.layouts.profile-layout')


@section('title', 'Lịch sử thanh toán')



@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
    }

    .table-container {
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }

    h2 {
        color: #0d6efd;
        font-weight: bold;
    }

    .form-label {
        font-weight: 500;
        color: #0d6efd;
    }

    .badge {
        border-radius: 0.5rem;
        padding: 0.4em 0.8em;
        font-size: 0.85rem;
    }

    .btn-outline-info {
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .btn-outline-info:hover {
        background-color: #0d6efd;
        color: white;
    }

    .no-data {
        font-size: 1rem;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        h2 { font-size: 1.5rem; }
        .table-container { padding: 1rem; }
        .table-responsive { font-size: 0.9rem; }
    }
</style>
@endpush

@section('profile-content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-credit-card me-2"></i>Lịch sử thanh toán</h2>

    {{-- Bộ lọc --}}
    <form method="GET" action="{{ route('client.payment_history.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="payment_date" class="form-label">Lọc theo ngày thanh toán</label>
            <input type="date" name="payment_date" id="payment_date" value="{{ request('payment_date') }}" class="form-control">
        </div>
        <div class="col-md-8 d-flex align-items-end gap-2">
            <button class="btn btn-primary">
                <i class="fas fa-filter me-1"></i> Lọc
            </button>
            <a href="{{ route('client.payment_history.index') }}" class="btn btn-secondary">
                <i class="fas fa-sync-alt me-1"></i> Đặt lại
            </a>
        </div>
    </form>

    {{-- Bảng dữ liệu --}}
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="text-center table-light">
                    <tr>
                        <th>Ngày thanh toán</th>
                        <th>Phương thức</th>
                        <th>Số tiền</th>
                        <th>Trạng thái</th>
                        {{-- <th>Chi tiết</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentHistories as $payment)
                        <tr>
                            <td class="text-center">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $payment->payment_method === 'cash' ? 'secondary' : ($payment->payment_method === 'card' ? 'info text-dark' : 'primary') }}">
                                    {{ ucfirst($payment->payment_method) }}
                                </span>
                            </td>
                            <td class="text-end text-success">{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</td>
                            <td class="text-center">
                                <span class="badge bg-success">Thành công</span>
                            </td>
                            {{-- <td class="text-center">
                                <a href="{{ route('client.payment_history.show', $payment->id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <span class="no-data"><i class="fas fa-money-bill-wave-slash me-1"></i> Không có dữ liệu thanh toán.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $paymentHistories->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://kit.fontawesome.com/a2d9d6a06d.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
