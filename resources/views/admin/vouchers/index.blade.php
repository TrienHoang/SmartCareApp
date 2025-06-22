@extends('admin.dashboard')
@section('title', 'Danh sách voucher')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary">🎁 Danh sách Voucher</h2>

    <!-- Form tìm kiếm và lọc -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="code" class="form-control" placeholder="Tìm theo mã voucher..."
                   value="{{ request('code') }}">
        </div>
        <div class="col-md-4">
            <input type="number" name="quantity" class="form-control" placeholder="Số lượng tối thiểu..."
                   value="{{ request('quantity') }}">
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-search"></i> Tìm kiếm
            </button>
        </div>
    </form>
    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success mb-4">
        <i class="bx bx-plus"></i> Thêm mới
    </a>
    <!-- Bảng danh sách voucher -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light text-center">
                <tr>
                    <th>ID</th>
                    <th>Mã</th>
                    <th>Giảm giá</th>
                    <th>Số lượng</th>
                    <th>Giá tối thiểu</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $voucher)
                    <tr class="align-middle text-center">
                        <td>{{ $voucher->id }}</td>
                        <td class="fw-bold text-primary">{{ $voucher->code }}</td>
                        <td><span class="badge bg-success">{{ $voucher->discount }}%</span></td>
                        <td>{{ number_format($voucher->quantity) }}</td>
                        <td>{{ number_format($voucher->min_price) }} đ</td>
                        <td>{{ $voucher->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="btn btn-sm btn-info">Xem</a>
                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không tìm thấy voucher nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="mt-3">
        {{ $vouchers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
