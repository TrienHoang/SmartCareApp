@extends('admin.dashboard')

@section('title', 'Chi tiết Voucher')

@section('content')
<div class="content-wrapper">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bx bx-detail me-2"></i> Chi tiết Voucher</h4>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Quay lại danh sách
            </a>
        </div>
        <div class="card-body p-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mã Voucher</label>
                    <div class="form-control-plaintext fw-bold text-primary">{{ $promotion->code }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phần trăm giảm</label>
                    <div class="form-control-plaintext">
                        <span class="badge bg-success fs-6">{{ $promotion->discount_percentage }}%</span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ngày bắt đầu</label>
                    <div class="form-control-plaintext">
                        {{ \Carbon\Carbon::parse($promotion->valid_from)->format('d/m/Y') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ngày kết thúc</label>
                    <div class="form-control-plaintext">
                        {{ \Carbon\Carbon::parse($promotion->valid_until)->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mô tả</label>
                <div class="form-control-plaintext">{{ $promotion->description ?? 'Không có mô tả' }}</div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4 pt-3 border-top">
                <a href="{{ route('admin.vouchers.edit', $promotion->id) }}" class="btn btn-warning px-4">
                    <i class="bx bx-edit me-1"></i> Chỉnh sửa
                </a>
                <form action="{{ route('admin.vouchers.destroy', $promotion->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa voucher này không?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bx bx-trash me-1"></i> Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
