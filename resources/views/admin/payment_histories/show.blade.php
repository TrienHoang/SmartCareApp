@extends('admin.dashboard')

@section('title', 'Chi tiết lịch sử thanh toán')

@section('content')
    <h1 class="mb-4">Chi tiết thanh toán #{{ $history->id }}</h1>

    <table class="table table-bordered">
        {{-- THÔNG TIN HÓA ĐƠN --}}
        <tr>
            <th>Mã hóa đơn</th>
            <td>{{ $history->payment_id }}</td>
        </tr>
        <tr>
            <th>Trạng thái thanh toán</th>
            <td>{{ ucfirst($history->payment->status ?? 'Chưa xác định') }}</td>
        </tr>

        {{-- BỆNH NHÂN --}}
        <tr class="table-primary">
            <th colspan="2">Thông tin bệnh nhân</th>
        </tr>
        <tr>
            <th>Họ tên</th>
            <td>{{ optional($history->payment->patient)->full_name ?? '---' }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ optional($history->payment->patient)->email ?? '---' }}</td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td>{{ optional($history->payment->patient)->phone ?? '---' }}</td>
        </tr>
        <tr>
            <th>Ngày sinh</th>
            <td>{{ optional(optional($history->payment->appointment->patient)->date_of_birth)->format('d/m/Y') ?? '---' }}
            </td>
        </tr>

        {{-- DỊCH VỤ --}}
        <tr class="table-success">
            <th colspan="2">Dịch vụ</th>
        </tr>
        <tr>
            <th>Tên dịch vụ</th>
            <td>{{ optional($history->payment->service)->name ?? '---' }}</td>
        </tr>
        <tr>
            <th>Mô tả</th>
            <td>{{ optional($history->payment->service)->description ?? '---' }}</td>
        </tr>

        {{-- BÁC SĨ --}}
        <tr class="table-info">
            <th colspan="2">Thông tin bác sĩ</th>
        </tr>
        <tr>
            <th>Họ tên</th>
            <td>{{ optional($history->payment->doctor->user)->full_name ?? '---' }}</td>
        </tr>
        <tr>
            <th>Chuyên môn</th>
            <td>{{ optional($history->payment->doctor)->specialization ?? '---' }}</td>
        </tr>
        <tr>
            <th>Phòng ban</th>
            <td>{{ optional($history->payment->doctor->department)->name ?? '---' }}</td>
        </tr>

        {{-- THANH TOÁN --}}
        <tr class="table-warning">
            <th colspan="2">Thông tin thanh toán</th>
        </tr>
        <tr>
            <th>Số tiền</th>
            <td>{{ number_format($history->amount, 0, ',', '.') }} ₫</td>
        </tr>
        <tr>
            <th>Phương thức</th>
            <td>{{ $history->payment_method ?? '---' }}</td>
        </tr>
        <tr>
            <th>Ngày thanh toán</th>
            <td>
                {{ $history->payment_date ? \Carbon\Carbon::parse($history->payment_date)->format('d/m/Y H:i') : 'Chưa thanh toán' }}
            </td>
        </tr>

        {{-- KHUYẾN MÃI --}}
        <tr class="table-secondary">
            <th colspan="2">Khuyến mãi (nếu có)</th>
        </tr>
        <tr>
            <th>Tên chương trình</th>
            <td>{{ optional($history->payment->promotion)->title ?? 'Không áp dụng' }}</td>
        </tr>
        <tr>
            <th>Giảm giá (%)</th>
            <td>{{ optional($history->payment->promotion)->discount_percentage ?? 0 }}%</td>
        </tr>

    </table>

    <a href="{{ route('admin.payment_histories.index') }}" class="btn btn-secondary">← Quay lại danh sách</a>
@endsection