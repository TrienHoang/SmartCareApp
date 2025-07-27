@extends('client.layouts.app')

@section('content')
<div class="container">
    <h1>Xác nhận đặt lịch</h1>
    <div class="card">
        <div class="card-body">
            <h5>Dịch vụ: {{ $service->name }}</h5>
            <p>Bác sĩ: {{ $doctor->name }}</p>
            <p>Thời gian: {{ $appointment_time->format('d/m/Y H:i') }}</p>
            <p>Người đặt: {{ $user->name }} ({{ $user->email }})</p>
            @if ($booking_confirm['reason'])
                <p>Ghi chú: {{ $booking_confirm['reason'] }}</p>
            @endif

            <form method="POST" action="{{ route('booking.save') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Tiếp tục</button>
                <a href="{{ route('booking.create') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection