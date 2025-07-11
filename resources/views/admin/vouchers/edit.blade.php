@extends('admin.dashboard')

@section('title', 'Cập nhật Voucher')

@section('content')
<div class="content-wrapper">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bx bx-edit me-2"></i> Cập nhật Voucher</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Mã voucher --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mã Voucher <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $voucher->code) }}" class="form-control @error('code') is-invalid @enderror">
                    @error('code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Giảm giá --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Phần trăm giảm (%) <span class="text-danger">*</span></label>
                    <input type="number" name="discount_percentage" value="{{ old('discount_percentage', $voucher->discount_percentage) }}" class="form-control @error('discount_percentage') is-invalid @enderror" min="0" max="100">
                    @error('discount_percentage') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Ngày hiệu lực --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Thời gian hiệu lực <span class="text-danger">*</span></label>
                    <input type="text" name="date_range" id="date_range"
                        value="{{ old('date_range', $voucher->valid_from->format('Y-m-d') . ' to ' . $voucher->valid_until->format('Y-m-d')) }}"
                        class="form-control @error('date_range') is-invalid @enderror"
                        placeholder="Chọn ngày bắt đầu - ngày kết thúc">
                    @error('date_range') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $voucher->description) }}</textarea>
                </div>

                {{-- Nút --}}
                <div class="d-flex justify-content-center gap-3 mt-4 pt-2 border-top border-gray-300">
                    <button type="submit" class="btn btn-success px-5 py-2">
                        <i class="bx bx-save me-2"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary px-5 py-2">
                        <i class="bx bx-x me-2"></i> Hủy
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
        defaultDate: @json(explode(' to ', old('date_range', $voucher->valid_from->format('Y-m-d') . ' to ' . $voucher->valid_until->format('Y-m-d')))),
    });
</script>
@endpush
