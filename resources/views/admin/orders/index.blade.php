@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0" style="color: rgb(62, 61, 61); font-weight: bold;"><i class="bx bx-receipt"></i> Danh sách đơn hàng</h4>
        </div>
        <div class="card-body">

            {{-- Bộ lọc --}}
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @php
                            $statusLabels = [
                                'pending' => 'Chờ xác nhận',
                                'paid' => 'Đã thanh toán',
                                'completed' => 'Hoàn tất',
                                'cancelled' => 'Đã hủy',
                            ];
                        @endphp
                        @foreach($statusLabels as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100"><i class="bx bx-filter-alt"></i> Lọc</button>
                </div>
            </form>

            {{-- Bảng đơn hàng --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Người đặt</th>
                            <th>Trạng thái</th>
                            <th>Thời gian</th>
                            <th class="text-center">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->full_name ?? 'Ẩn danh' }}</td>
                                <td>
                                    @php
                                        $badgeColors = [
                                            'pending' => 'warning',
                                            'paid' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $badgeColors[$order->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->ordered_at }}</td>
                                <td class="text-center">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Không có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $orders->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
