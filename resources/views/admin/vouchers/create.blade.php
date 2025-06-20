@extends('admin.dashboard')

@section('content')
<div class="container py-4">
    <h2>Thêm Voucher mới</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vouchers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Mã Voucher</label>
            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
        </div>

        <div class="mb-3">
            <label>Giảm giá (%)</label>
            <input type="number" name="discount" class="form-control" value="{{ old('discount') }}" min="0" required>
        </div>

        <div class="mb-3">
            <label>Số lượng</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" min="1" required>
        </div>

        <div class="mb-3">
            <label>Giá tối thiểu (áp dụng)</label>
            <input type="number" name="min_price" class="form-control" value="{{ old('min_price') }}" min="0" required>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Tạo mới</button>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
