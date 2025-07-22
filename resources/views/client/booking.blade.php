@extends('client.layouts.app')

@section('title', 'Đặt lịch khám bệnh')

@push('styles')
    <style>

    </style>
@endpush

@section('content')

    <h1>Chọn ngày khám bệnh</h1>

    <h4>Thông tin dịch vụ</h4>

    tên dịch vụ: {{ $service->name }} <br>

    Giá: {{ number_format($service->price, 0, ',', '.') }} VNĐ <br>

    mô tả: {{ $service->description }} <br>

@endsection



@push('scripts')
@endpush
