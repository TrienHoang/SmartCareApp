@extends('admin.dashboard')
@section('content')
<h1>Chi tiết thanh toán #{{ $payment->id }}</h1>
<ul>
    <li><b>ID:</b> {{ $payment->id }}</li>
    <li><b>ID Cuộc hẹn:</b> {{ $payment->appointment_id }}</li>
    <li><b>ID Khuyến mãi:</b> {{ $payment->promotion_id ?? 'Không có' }}</li>
    <li><b>Tổng tiền:</b> {{ number_format($payment->amount) }}</li>
    <li><b>Phương thức:</b> {{ $payment->payment_method }}</li>
    <li><b>Trạng thái:</b> {{ $payment->status }}</li>
    <li><b>Thời gian thanh toán:</b> {{ $payment->paid_at }}</li>
</ul>
<a href="{{ route('admin.payments.index') }}">Quay lại danh sách</a>
@endsection
