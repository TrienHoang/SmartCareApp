@extends('admin.dashboard')

@section('title', 'Chi tiết lịch sử thanh toán')

@section('content')
<div class="container-xl mt-4">
    <h2 class="text-center text-primary fw-bold mb-4">Chi tiết thanh toán #{{ $history->id }}</h2>

    <div class="card shadow-lg">
        <div class="card-body">
            <!-- Thông tin hóa đơn -->
            <h5 class="text-success border-bottom pb-2 mb-3">🧾 Thông tin hóa đơn</h5>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Mã hóa đơn:</strong> {{ $history->payment_id }}</div>
                <div class="col-md-6"><strong>Trạng thái thanh toán:</strong> {{ ucfirst($history->payment->status ?? 'Chưa xác định') }}</div>
            </div>

            <!-- Bệnh nhân -->
            <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">🧍‍♂️ Thông tin bệnh nhân</h5>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Họ tên:</strong> {{ optional($history->payment->patient)->full_name ?? '---' }}</div>
                <div class="col-md-4"><strong>Email:</strong> {{ optional($history->payment->patient)->email ?? '---' }}</div>
                <div class="col-md-4"><strong>SĐT:</strong> {{ optional($history->payment->patient)->phone ?? '---' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Ngày sinh:</strong>
                    {{ optional(optional($history->payment->appointment->patient)->date_of_birth)->format('d/m/Y') ?? '---' }}
                </div>
            </div>

            <!-- Dịch vụ -->
            <h5 class="text-success border-bottom pb-2 mt-4 mb-3">🛎️ Dịch vụ</h5>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Tên dịch vụ:</strong> {{ optional($history->payment->service)->name ?? '---' }}</div>
                <div class="col-md-6"><strong>Mô tả:</strong> {{ optional($history->payment->service)->description ?? '---' }}</div>
            </div>

            <!-- Bác sĩ -->
            <h5 class="text-info border-bottom pb-2 mt-4 mb-3">👨‍⚕️ Thông tin bác sĩ</h5>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Họ tên:</strong> {{ optional($history->payment->doctor->user)->full_name ?? '---' }}</div>
                <div class="col-md-4"><strong>Chuyên môn:</strong> {{ optional($history->payment->doctor)->specialization ?? '---' }}</div>
                <div class="col-md-4"><strong>Phòng ban:</strong> {{ optional($history->payment->doctor->department)->name ?? '---' }}</div>
            </div>

            <!-- Thanh toán -->
            <h5 class="text-warning border-bottom pb-2 mt-4 mb-3">💰 Thông tin thanh toán</h5>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Số tiền:</strong> <span class="text-success fw-bold">{{ number_format($history->amount, 0, ',', '.') }} ₫</span></div>
                <div class="col-md-4"><strong>Phương thức:</strong> {{ $history->payment_method ?? '---' }}</div>
                <div class="col-md-4">
                    <strong>Ngày thanh toán:</strong>
                    {{ $history->payment_date ? \Carbon\Carbon::parse($history->payment_date)->format('d/m/Y H:i') : 'Chưa thanh toán' }}
                </div>
            </div>

            <!-- Khuyến mãi -->
            <h5 class="text-secondary border-bottom pb-2 mt-4 mb-3">🎁 Khuyến mãi (nếu có)</h5>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Tên chương trình:</strong> {{ optional($history->payment->promotion)->title ?? 'Không áp dụng' }}</div>
                <div class="col-md-6"><strong>Giảm giá:</strong> {{ optional($history->payment->promotion)->discount_percentage ?? 0 }}%</div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.payment_histories.index') }}" class="btn btn-secondary">
            ← Quay lại danh sách
        </a>
        <a href="{{ route('admin.payment_histories.exportDetailPdf', $history->id) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Xuất PDF
        </a>
    </div>
</div>
@endsection
