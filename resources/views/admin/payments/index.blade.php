@extends('admin.dashboard')
@section('content')
<h1>Lịch sử thanh toán</h1>
<form method="GET" class="mb-3">
    <input type="text" name="appointment_id" placeholder="ID Cuộc hẹn" value="{{ request('appointment_id') }}">
    <select name="status">
        <option value="">-- Trạng thái --</option>
        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Thành công</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
    </select>
    <select name="payment_method">
        <option value="">-- Phương thức --</option>
        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
        <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Chuyển khoản</option>
    </select>
    <input type="date" name="from_date" value="{{ request('from_date') }}">
    <input type="date" name="to_date" value="{{ request('to_date') }}">
    <button type="submit">Lọc</button>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID Cuộc hẹn</th>
            <th>Khuyến mãi</th>
            <th>Tổng tiền</th>
            <th>Phương thức</th>
            <th>Trạng thái</th>
            <th>Thời gian thanh toán</th>
            <th>Chi tiết</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->appointment_id }}</td>
            <td>{{ $payment->promotion_id ?? 'Không' }}</td>
            <td>{{ number_format($payment->amount) }}</td>
            <td>{{ $payment->payment_method }}</td>
            <td>{{ $payment->status }}</td>
            <td>{{ $payment->paid_at }}</td>
            <td><a href="{{ route('admin.payments.show', $payment) }}">Xem</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $payments->links() }}
@endsection
