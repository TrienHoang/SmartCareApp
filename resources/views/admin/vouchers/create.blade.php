@extends('admin.dashboard')

@section('title', 'Thêm Voucher mới')

@section('content')
<div class="content-wrapper">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bx bx-plus me-2"></i> Tạo Voucher Mới</h4>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Quay lại danh sách
            </a>
        </div>
        <div class="card-body p-4">
            {{-- Flash Message --}}
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-1"></i>
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf

                {{-- Mã Voucher --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mã Voucher <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}"
                        class="form-control @error('code') is-invalid @enderror"
                        placeholder="VD: GIAM10">
                    @error('code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Giảm Giá --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Phần trăm giảm giá (%) <span class="text-danger">*</span></label>
                    <input type="number" name="discount_percentage"
                        value="{{ old('discount_percentage') }}"
                        class="form-control @error('discount_percentage') is-invalid @enderror"
                        placeholder="VD: 10">
                    @error('discount_percentage') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Khoảng thời gian hiệu lực --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Thời gian hiệu lực <span class="text-danger">*</span></label>
                    <input type="text" name="date_range" id="date_range"
                        value="{{ old('date_range') }}"
                        class="form-control @error('date_range') is-invalid @enderror"
                        placeholder="Chọn ngày bắt đầu - ngày kết thúc">
                    @error('date_range') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" rows="3" class="form-control"
                        placeholder="Nhập mô tả nếu có...">{{ old('description') }}</textarea>
                </div>

                {{-- Nút hành động --}}
                <div class="d-flex justify-content-center gap-3 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-success px-5 py-2">
                        <i class="bx bx-save me-1"></i> Tạo mới
                    </button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary px-5 py-2">
                        <i class="bx bx-x me-1"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        minDate: "today",
        defaultDate: @json(explode(' to ', old('date_range') ?? '')),
    });
</script>
@endpush
