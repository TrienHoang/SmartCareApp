@extends('admin.dashboard')
@section('title', 'Chi tiết voucher')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-primary">
            <i class="bx bx-health"></i> Chi tiết Voucher khuyến mãi
        </h4>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bx bx-arrow-back"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card border border-primary shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bx bx-purchase-tag-alt"></i> Thông tin chi tiết voucher</h5>
        </div>
        <div class="card-body bg-light">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="text-muted small">ID</div>
                    <div class="fw-semibold fs-5">{{ $voucher->id }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Mã voucher</div>
                    <div class="fw-semibold fs-5 text-primary">{{ $voucher->code }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Giảm giá</div>
                    <div class="badge bg-success fs-6">{{ $voucher->discount }}%</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Số lượng</div>
                    <div class="fs-6">{{ number_format($voucher->quantity) }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Giá tối thiểu áp dụng</div>
                    <div class="fs-6 text-danger">{{ number_format($voucher->min_price) }} đ</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Ngày hết hạn</div>
                    <div class="fs-6">
                        @if($voucher->expiry_days ?? false)
                            {{ $voucher->created_at->addDays($voucher->expiry_days)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">Không xác định</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="text-muted small">Mô tả</div>
                    <div class="fs-6">{{ $voucher->description ?? 'Không có' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Ngày tạo</div>
                    <div class="fs-6">{{ $voucher->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Ngày cập nhật</div>
                    <div class="fs-6">{{ $voucher->updated_at->format('d/m/Y H:i:s') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
