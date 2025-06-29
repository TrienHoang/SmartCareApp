@extends('admin.dashboard')

@section('title', 'Cập nhật Voucher')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-gift me-2 text-primary" style="font-size: 24px;"></i>
                        <h2 class="content-header-title mb-0">Cập nhật Voucher</h2>
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
                                <li class="breadcrumb-item active">Cập nhật</li>
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
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bx bx-edit me-2"></i> Thông tin Voucher</h4>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Lỗi!</strong>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mã Voucher <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $voucher->code) }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Giảm giá (%) <span class="text-danger">*</span></label>
                                <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $voucher->discount) }}" min="0" required>
                                @error('discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $voucher->quantity) }}" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Giá tối thiểu (áp dụng)</label>
                                <input type="number" name="min_price" class="form-control @error('min_price') is-invalid @enderror" value="{{ old('min_price', $voucher->min_price) }}" min="0">
                                @error('min_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mô tả</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $voucher->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-center gap-3 mt-4 pt-2 border-top border-gray-300">
                                <button type="submit" class="btn btn-success btn-lg px-5 py-3 rounded-3 shadow-sm">
                                    <i class="bx bx-save me-2"></i> Cập nhật
                                </button>
                                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary btn-lg px-5 py-3 rounded-3 shadow-sm">
                                    <i class="bx bx-x me-2"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 12px;
    }
    .card-header {
        border-radius: 12px 12px 0 0;
    }
    .btn-success {
        background: linear-gradient(135deg, #39DA8A 0%, #55a3ff 100%);
        border: none;
    }
    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(57,218,138,0.2);
    }
    .btn-secondary:hover {
        transform: translateY(-1px);
    }
    .invalid-feedback {
        display: block;
    }
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
</style>
@endpush

@push('scripts')
<script>
    document.querySelector('form.needs-validation')?.addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('was-validated');
        }
    });
</script>
@endpush
