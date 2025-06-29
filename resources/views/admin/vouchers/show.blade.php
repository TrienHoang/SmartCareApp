@extends('admin.dashboard')
@section('title', 'Chi tiết Voucher')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-gift me-2 text-primary" style="font-size: 24px;"></i>
                        <h2 class="content-header-title mb-0">Chi tiết Voucher</h2>
                    </div>
                    <div class="breadcrumb-wrapper d-flex align-items-center" style="min-height: 32px;">
                        <nav aria-label="breadcrumb" class="w-100">
                            <ol class="breadcrumb mb-0">
                                <li class="">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                        Trang chủ >
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('admin.vouchers.index') }}" class="text-decoration-none">
                                        Voucher >
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Chi tiết</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-body">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bx bx-purchase-tag-alt"></i> Thông tin chi tiết voucher</h5>
                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light btn-sm">
                            <i class="bx bx-arrow-back"></i> Quay lại
                        </a>
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
                                        {{ $voucher->created_at ? $voucher->created_at->addDays($voucher->expiry_days)->format('d/m/Y') : 'Không xác định' }}
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
                                <div class="fs-6">{{ $voucher->created_at ? $voucher->created_at->format('d/m/Y H:i:s') : '' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Ngày cập nhật</div>
                                <div class="fs-6">{{ $voucher->updated_at ? $voucher->updated_at->format('d/m/Y H:i:s') : '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .breadcrumb-wrapper {
        min-height: 32px;
        display: flex;
        align-items: center;
    }
    .content-header-title {
        line-height: 1.2;
    }
    .breadcrumb {
        margin-bottom: 0;
        background: transparent;
        padding-left: 0;
    }
    .breadcrumb li,
    .breadcrumb .breadcrumb-item {
        display: inline-block;
        vertical-align: middle;
    }
    .card {
        border-radius: 12px;
    }
    .card-header {
        border-radius: 12px 12px 0 0;
    }
</style>
@endpush
