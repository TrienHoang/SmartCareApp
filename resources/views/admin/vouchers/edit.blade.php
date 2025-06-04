@extends('admin.dashboard')

@section('content')
<div class="container py-4">
    <h2>Cập nhật Voucher</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Mã Voucher</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $voucher->code) }}" required>
        </div>

        <div class="mb-3">
            <label>Giảm giá (%)</label>
            <input type="number" name="discount" class="form-control" value="{{ old('discount', $voucher->discount) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label>Số lượng</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $voucher->quantity) }}" min="1" required>
        </div>

        <div class="mb-3">
            <label>Giá tối thiểu</label>
            <input type="number" name="min_price" class="form-control" value="{{ old('min_price', $voucher->min_price) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description', $voucher->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
