@extends('client.layouts.app')

@section('title', 'Lịch sử thanh toán')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        table.dataTable tbody tr:hover {
            background-color: #e6f2ff !important;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            h2 { font-size: 1.4rem; }
            .table-responsive { font-size: 0.9rem; }
        }
    </style>
@endpush

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary">💳 Lịch sử thanh toán đã hoàn thành</h2>

    {{-- Form lọc --}}
    <form id="filterForm" method="GET" action="{{ route('client.payment_history.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="date" name="payment_date" value="{{ request('payment_date') }}" class="form-control" placeholder="Lọc theo ngày">
        </div>
        <div class="col-md-4 d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-filter me-1"></i> Lọc
            </button>
            <a href="{{ route('client.payment_history.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-undo me-1"></i> Đặt lại
            </a>
        </div>
    </form>

    {{-- Bảng dữ liệu --}}
    <div class="table-responsive">
        <table id="paymentTable" class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Phương thức</th>
                    <th>Ngày thanh toán</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($paymentHistories as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>
                            <span class="badge
                                {{ $payment->payment_method === 'cash' ? 'bg-secondary' :
                                   ($payment->payment_method === 'card' ? 'bg-info text-dark' : 'bg-primary') }}">
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</td>
                        <td>
                            <span class="badge bg-success">Thành công</span>
                        </td>
                        <td>
                            <a href="{{ route('client.payment_history.show', $payment->id) }}"
                               class="btn btn-sm btn-outline-info"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Không có thanh toán nào được ghi nhận.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-4">
        {{ $paymentHistories->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://kit.fontawesome.com/a2d9d6a06d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function () {
            // Tooltip Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // DataTable init
            $('#paymentTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                ordering: true,
                columnDefs: [
                    { orderable: false, targets: 5 }
                ]
            });

            // SweetAlert loading khi submit
            $('#filterForm').on('submit', function () {
                Swal.fire({
                    title: 'Đang lọc dữ liệu...',
                    didOpen: () => Swal.showLoading(),
                    allowOutsideClick: false
                });
            });

            // Fade-in từng hàng
            $('#paymentTable tbody tr').each(function (i) {
                $(this).css('opacity', 0);
                setTimeout(() => {
                    $(this).css({ transition: 'opacity 0.5s ease-in', opacity: 1 });
                }, 100 * i);
            });
        });
    </script>
@endpush
