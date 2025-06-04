@extends('admin.dashboard')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4">Quản lý Voucher</h1>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">+ Thêm mới Voucher</a>
    </div>

    <div class="card">
        <div class="card-header">Danh sách Voucher</div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover m-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Mã</th>
                        <th>Giảm giá (%)</th>
                        <th>Số lượng</th>
                        <th>Giá tối thiểu</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vouchers as $key => $voucher)
                        <tr>
                            <td>{{ $vouchers->firstItem() + $key }}</td>
                            <td>{{ $voucher->code }}</td>
                            <td>{{ $voucher->discount }}</td>
                            <td>{{ $voucher->quantity }}</td>
                            <td>{{ number_format($voucher->min_price) }} đ</td>
                            <td>{{ $voucher->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="btn btn-sm btn-info">Xem</a>
                                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-sm btn-warning">Sửa</a>


                                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Không có voucher nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer text-center">
            {{ $vouchers->links() }}
        </div>
    </div>
</div>
@endsection
