@extends('admin.dashboard')

@section('title', '🧾 Lịch sử thanh toán')

@section('content')

<style>
/* ===== GIAO DIỆN DANH SÁCH THANH TOÁN ===== */

/* Table style */
.table th {
  background-color: #f5f7fa;
  color: #333;
  font-weight: 600;
  font-size: 14px;
  white-space: nowrap;
}
.table td {
  font-size: 13.5px;
  vertical-align: middle;
  padding: 10px 12px;
}
.table tbody tr:hover {
  background-color: #eaf3ff !important;
  transition: background-color 0.25s ease-in-out;
}

/* Nút */
.btn {
  transition: all 0.2s ease;
  border-radius: 6px;
}
.btn:hover {
  transform: scale(1.02);
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.btn-outline-info {
  color: #0dcaf0;
  border-color: #0dcaf0;
}
.btn-outline-info:hover {
  background-color: #0dcaf0;
  color: #fff;
}

/* Badge mã hóa đơn */
.badge.bg-label-primary {
  background-color: #007bff1a;
  color: #007bff;
  font-weight: 500;
}

/* Badge trạng thái */
.badge.bg-success    { background-color: #28a745 !important; color: #fff !important; }
.badge.bg-warning    { background-color: #ffc107 !important; color: #212529 !important; }
.badge.bg-danger     { background-color: #dc3545 !important; color: #fff !important; }
.badge.bg-secondary  { background-color: #6c757d !important; color: #fff !important; }

/* Card */
.card {
  border-radius: 10px !important;
  box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

/* Phân trang */
.pagination .page-link {
  color: #007bff;
  font-size: 14px;
  border-radius: 5px !important;
}
.pagination .page-link:hover {
  background-color: #eaf4ff;
  border-color: #d0e7ff;
}

/* Responsive */
@media (max-width: 768px) {
  .table th, .table td {
    font-size: 13px !important;
    padding: 8px;
  }
  .d-none.d-md-table-cell {
    display: none !important;
  }
  h4 {
    font-size: 18px;
  }
}
</style>

<div class="container-fluid py-4 animate__animated animate__fadeIn">

  {{-- Tiêu đề --}}
  <h4 class="mb-4">
    <i class="bx bx-history text-primary me-2"></i> 
    <span class="text-dark fw-semibold">Lịch sử thanh toán</span>
  </h4>

  {{-- Form lọc --}}
  <form action="{{ route('admin.payment_histories.index') }}" method="GET" class="row g-2 align-items-end mb-4">
    <div class="col-12 col-md-3">
      <label class="form-label">Tên bệnh nhân</label>
      <input type="text" name="patient_name" class="form-control" value="{{ request('patient_name') }}">
    </div>
    <div class="col-6 col-md-2">
      <label class="form-label">Từ ngày</label>
      <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="col-6 col-md-2">
      <label class="form-label">Đến ngày</label>
      <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
    <div class="col-6 col-md-2">
      <label class="form-label">Dịch vụ</label>
      <select name="service_id" class="form-select">
        <option value="">Tất cả</option>
        @foreach($services as $service)
          <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
            {{ $service->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-6 col-md-2">
      <label class="form-label">Bác sĩ</label>
      <select name="doctor_id" class="form-select">
        <option value="">Tất cả</option>
        @foreach($doctors as $doctor)
          <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
            {{ $doctor->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-12 col-md-1">
      <button type="submit" class="btn btn-primary w-100">
        <i class="bx bx-filter-alt"></i>
      </button>
    </div>
  </form>

  {{-- Thông báo --}}
  @foreach (['success', 'error'] as $msg)
    @if(session($msg))
      <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
        {{ session($msg) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  @endforeach

  {{-- Bảng dữ liệu --}}
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center mb-0">
          <thead class="table-light text-dark">
            <tr>
              <th>#</th>
              <th>Mã</th>
              <th class="text-start">Bệnh nhân</th>
              <th>Dịch vụ</th>
              <th class="d-none d-md-table-cell">Bác sĩ</th>
              <th>Ngày</th>
              <th>Trạng thái</th>
              <th>Số tiền</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @forelse($histories as $history)
              @php
                $status = $history->payment->status ?? null;
                $statusMap = [
                  'paid'    => ['label' => 'Đã thanh toán', 'class' => 'bg-success'],
                  'pending' => ['label' => 'Chờ xử lý',      'class' => 'bg-warning text-dark'],
                  'failed'  => ['label' => 'Thất bại',       'class' => 'bg-danger'],
                ];
                $statusLabel = $statusMap[$status] ?? ['label' => 'Không xác định', 'class' => 'bg-secondary'];
              @endphp
              <tr>
                <td>{{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}</td>
                <td><span class="badge bg-label-primary">#{{ $history->payment->id }}</span></td>
                <td class="text-start">
                  <strong>{{ optional($history->payment->appointment->patient)->full_name ?? 'N/A' }}</strong><br>
                  <small class="text-muted">{{ optional($history->payment->appointment->patient)->phone }}</small>
                </td>
                <td>{{ optional($history->payment->appointment->service)->name ?? 'N/A' }}</td>
                <td class="d-none d-md-table-cell">{{ optional($history->payment->appointment->doctor->user)->full_name ?? 'N/A' }}</td>
                <td>{{ optional($history->payment_date)->format('d/m/Y H:i') ?? 'Chưa TT' }}</td>
                <td><span class="badge {{ $statusLabel['class'] }}">{{ $statusLabel['label'] }}</span></td>
                <td><strong class="text-success">{{ number_format($history->amount, 0, ',', '.') }}₫</strong></td>
                <td>
                  <a href="{{ route('admin.payment_histories.show', $history->id) }}" class="btn btn-sm btn-outline-info" title="Chi tiết">
                    <i class="bx bx-show"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-muted py-4">Không có dữ liệu phù hợp.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
   @endsection
