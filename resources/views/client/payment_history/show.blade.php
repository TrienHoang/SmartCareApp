@extends('admin.dashboard')

@section('title', '🧾 Chi tiết hóa đơn - Cảm ơn bạn!')

@section('content')
<style>
  @media (max-width: 768px) {
    .card-body p {
      font-size: 14px;
    }
    h5 {
      font-size: 16px;
    }
  }
  .card {
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
  }
  .badge-status {
    font-size: 13px;
    padding: 4px 8px;
    border-radius: 4px;
  }
</style>

<div class="container-xl mt-4 animate__animated animate__fadeIn">

  {{-- Lời cảm ơn --}}
  <div class="text-center mb-4">
    <h2 class="text-success fw-bold fs-3">🎉 Thanh toán thành công!</h2>
    <p class="text-muted fs-5">Cảm ơn bạn đã tin tưởng sử dụng dịch vụ của chúng tôi 💌</p>
  </div>

  {{-- Thông tin hoá đơn --}}
  <div class="card border-0">
    <div class="card-body px-4 py-3">

      {{-- 1. Hoá đơn --}}
      <h5 class="text-primary border-bottom pb-2 mb-3"><i class="bx bx-receipt"></i> Thông tin hóa đơn</h5>
      <div class="row">
        <div class="col-md-6">
          <p><strong>Mã hóa đơn:</strong> #{{ $history->payment_id }}</p>
          <p><strong>Phương thức:</strong> {{ $history->payment_method ?? '---' }}</p>
          @php
            $status = $history->payment->status ?? null;
            $badgeColor = match($status) {
              'paid' => 'bg-success',
              'pending' => 'bg-warning text-dark',
              'failed' => 'bg-danger',
              default => 'bg-secondary'
            };
          @endphp
          <p><strong>Trạng thái:</strong> 
            <span class="badge {{ $badgeColor }} badge-status">
              {{ ucfirst($status) ?? 'Không rõ' }}
            </span>
          </p>
        </div>
        <div class="col-md-6">
          <p><strong>Ngày thanh toán:</strong> {{ optional($history->payment_date)->format('d/m/Y H:i') ?? '---' }}</p>
          <p><strong>Số tiền:</strong> 
            <span class="text-danger fw-bold">{{ number_format($history->amount, 0, ',', '.') }} ₫</span>
          </p>
        </div>
      </div>

      {{-- 2. Bệnh nhân --}}
      <h5 class="text-info mt-4 mb-3 border-bottom pb-2"><i class="bx bx-user"></i> Thông tin bệnh nhân</h5>
      <div class="row">
        <div class="col-md-6">
          <p><strong>Họ tên:</strong> {{ optional($history->payment->appointment->patient)->full_name ?? '---' }}</p>
          <p><strong>Điện thoại:</strong> {{ optional($history->payment->appointment->patient)->phone ?? '---' }}</p>
          <p><strong>Email:</strong> {{ optional($history->payment->appointment->patient)->email ?? '---' }}</p>
        </div>
        <div class="col-md-6">
          <p><strong>Ngày sinh:</strong> {{ optional($history->payment->appointment->patient->date_of_birth)->format('d/m/Y') ?? '---' }}</p>
          <p><strong>Địa chỉ:</strong> {{ optional($history->payment->appointment->patient)->address ?? '---' }}</p>
        </div>
      </div>

      {{-- 3. Dịch vụ --}}
      <h5 class="text-warning mt-4 mb-3 border-bottom pb-2"><i class="bx bx-spa"></i> Dịch vụ</h5>
      <p><strong>Tên dịch vụ:</strong> {{ optional($history->payment->appointment->service)->name ?? '---' }}</p>
      <p><strong>Mô tả:</strong> <span class="text-muted">{{ optional($history->payment->appointment->service)->description ?? '---' }}</span></p>

      {{-- 4. Bác sĩ --}}
      <h5 class="text-secondary mt-4 mb-3 border-bottom pb-2"><i class="bx bx-user-pin"></i> Bác sĩ phụ trách</h5>
      <p><strong>Họ tên:</strong> {{ optional($history->payment->appointment->doctor->user)->full_name ?? '---' }}</p>
      <p><strong>Chuyên môn:</strong> {{ optional($history->payment->appointment->doctor)->specialization ?? '---' }}</p>
      <p><strong>Phòng ban:</strong> {{ optional($history->payment->appointment->doctor->department)->name ?? '---' }}</p>

      {{-- 5. Khuyến mãi --}}
      <h5 class="text-success mt-4 mb-3 border-bottom pb-2"><i class="bx bx-gift"></i> Ưu đãi</h5>
      <p><strong>Chương trình:</strong> {{ optional($history->payment->promotion)->title ?? 'Không có' }}</p>
      <p><strong>Giảm giá:</strong> {{ optional($history->payment->promotion)->discount_percentage ?? 0 }}%</p>

      {{-- Lời cảm ơn cuối --}}
      <div class="alert alert-light text-center mt-4 border rounded">
        🌟 <strong>Chúc quý khách luôn mạnh khỏe và hạnh phúc!</strong><br>
        ❤️ Cảm ơn bạn vì đã đồng hành cùng chúng tôi.
      </div>
    </div>
  </div>

  {{-- Điều hướng --}}
  <div class="d-flex justify-content-between mt-4 print-hidden">
    <a href="{{ route('admin.payment_histories.index') }}" class="btn btn-outline-secondary">
      ← Trở về danh sách
    </a>
    <a href="{{ route('admin.payment_histories.exportDetailPdf', $history->id) }}" class="btn btn-danger">
      <i class="bi bi-file-earmark-pdf"></i> Tải hóa đơn PDF
    </a>
  </div>
</div>
@endsection