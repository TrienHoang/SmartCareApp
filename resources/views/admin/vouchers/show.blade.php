@extends('admin.dashboard')
@section('title', 'Chi tiết voucher')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Vouchers /</span> Chi tiết voucher
        </h4>
        <div class="card"></div>
            <div class="card-header">
                <h5 class="mb-0">Thông tin chi tiết voucher</h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $voucher->id }}</p>
                <p><strong>Mã voucher:</strong> {{ $voucher->code }}</p>
                <p><strong>Giảm giá:</strong> {{ $voucher->discount }}%</p>
                <p><strong>Ngày hết hạn:</strong> {{ $voucher->created_at->addDays($voucher->expiry_days)->format('d/m/Y') }}</p>
                <p><strong>Số lượng:</strong> {{ $voucher->quantity }}</p>
                <p><strong>Mô tả:</strong> {{ $voucher->description }}</p>
                <p><strong>Ngày tạo:</strong> {{ $voucher->created_at->format('d/m/Y H:i:s') }}</p>

        </div>
    </div>
@endsection
