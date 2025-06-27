@extends('admin.dashboard')

@section('title', '🧾 Chi tiết hóa đơn - Cảm ơn bạn!')

@section('content')
<div class="container-xl mt-4">
    {{-- Lời cảm ơn đầu trang --}}
    <div class="text-center mb-4">
        <h2 class="text-success fw-bold fs-3">🎉 Thanh toán thành công!</h2>
        <p class="text-dark fs-5">Cảm ơn bạn đã tin tưởng sử dụng dịch vụ của chúng tôi. Dưới đây là thông tin chi tiết hóa đơn của bạn 💌</p>
    </div>

    <div class="card shadow p-4 rounded-4">

        {{-- Thông tin hóa đơn --}}
        <h5 class="text-primary border-bottom pb-2 mb-3 fs-5">🧾 Thông tin hóa đơn</h5>
        <p><strong>Mã hóa đơn:</strong> {{ $history->payment_id }}</p>
        <p><strong>Trạng thái:</strong> <span class="badge bg-success">{{ ucfirst($history->payment->status ?? 'Không rõ') }}</span></p>
        <p><strong>Ngày thanh toán:</strong> {{ $history->payment_date ? \Carbon\Carbon::parse($history->payment_date)->format('d/m/Y H:i') : '---' }}</p>
        <p><strong>Phương thức:</strong> {{ $history->payment_method ?? '---' }}</p>
        <p><strong>Số tiền thanh toán:</strong> <span class="text-danger fw-bold">{{ number_format($history->amount, 0, ',', '.') }} ₫</span></p>

        {{-- Thông tin bệnh nhân --}}
        <h5 class="text-info border-bottom pb-2 mb-3 mt-4 fs-5">🧍‍♂️ Thông tin bệnh nhân</h5>
        <p><strong>Họ tên:</strong> {{ optional($history->payment->appointment->patient)->full_name ?? '---' }}</p>
        <p><strong>Số điện thoại:</strong> {{ optional($history->payment->appointment->patient)->phone ?? '---' }}</p>
        <p><strong>Email:</strong> {{ optional($history->payment->appointment->patient)->email ?? '---' }}</p>
        <p><strong>Ngày sinh:</strong> {{ optional($history->payment->appointment->patient->date_of_birth)->format('d/m/Y') ?? '---' }}</p>
        <p><strong>Địa chỉ:</strong> {{ optional($history->payment->appointment->patient)->address ?? '---' }}</p>

        {{-- Dịch vụ --}}
        <h5 class="text-warning border-bottom pb-2 mb-3 mt-4 fs-5">🛎️ Dịch vụ sử dụng</h5>
        <p><strong>Tên dịch vụ:</strong> {{ optional($history->payment->appointment->service)->name ?? '---' }}</p>
        <p><strong>Mô tả:</strong> {{ optional($history->payment->appointment->service)->description ?? '---' }}</p>

        {{-- Bác sĩ --}}
        <h5 class="text-secondary border-bottom pb-2 mb-3 mt-4 fs-5">👨‍⚕️ Bác sĩ phụ trách</h5>
        <p><strong>Họ tên:</strong> {{ optional($history->payment->appointment->doctor->user)->full_name ?? '---' }}</p>
        <p><strong>Chuyên môn:</strong> {{ optional($history->payment->appointment->doctor)->specialization ?? '---' }}</p>
        <p><strong>Phòng ban:</strong> {{ optional($history->payment->appointment->doctor->department)->name ?? '---' }}</p>

        {{-- Khuyến mãi --}}
        <h5 class="text-success border-bottom pb-2 mb-3 mt-4 fs-5">🎁 Ưu đãi áp dụng</h5>
        <p><strong>Chương trình:</strong> {{ optional($history->payment->promotion)->title ?? 'Không có' }}</p>
        <p><strong>Giảm giá:</strong> {{ optional($history->payment->promotion)->discount_percentage ?? 0 }}%</p>

        {{-- Lời tri ân --}}
        <div class="alert alert-light border mt-4 rounded-3 text-center">
            🌟 <strong>Chúc quý khách luôn mạnh khỏe và hạnh phúc!</strong><br>
            ❤️ Cảm ơn bạn vì đã đồng hành cùng chúng tôi. Hẹn gặp lại bạn vào lần khám tiếp theo!
        </div>
    </div>

    {{-- Nút điều hướng --}}
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.payment_histories.index') }}" class="btn btn-outline-secondary">
            ← Trở về danh sách
        </a>
        <a href="{{ route('admin.payment_histories.exportDetailPdf', $history->id) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Tải hóa đơn PDF
        </a>
    </div>
</div>
@endsection
