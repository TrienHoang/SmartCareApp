@extends('admin.dashboard')

@section('content')
<div class="container py-4">
    <h2>Cập nhật Voucher</h2>

    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Mã Voucher</label>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $voucher->code) }}" >
            @error('code')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Giảm giá (%)</label>
            <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $voucher->discount) }}" min="0" >
            @error('discount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Số lượng</label>
            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $voucher->quantity) }}" min="1" >
            @error('quantity')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Giá tối thiểu (áp dụng)</label>
            <input type="number" name="min_price" class="form-control @error('min_price') is-invalid @enderror" value="{{ old('min_price', $voucher->min_price) }}" min="0" >
            @error('min_price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $voucher->description) }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
