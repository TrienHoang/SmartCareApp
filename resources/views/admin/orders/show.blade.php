@extends('admin.dashboard')

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0" style="color: rgb(75, 75, 75); font-weight: bold;">
                Chi tiết đơn hàng #{{ $order->id }}
            </h4>
        </div>
        <div class="card-body">

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Thông tin đơn hàng --}}
            <table class="table table-bordered mb-4">
                <tbody>
                    <tr>
                        <th>Người đặt</th>
                        <td>{{ $order->user->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @php
                                $statusLabels = [
                                    'pending' => 'Chờ xác nhận',
                                    'paid' => 'Đã thanh toán',
                                    'completed' => 'Hoàn tất',
                                    'cancelled' => 'Đã hủy',
                                ];
                            @endphp
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </td>
                    </tr>
                    <tr>
                        <th>Thời gian đặt</th>
                        <td>{{ $order->ordered_at }}</td>
                    </tr>
                    <tr>
                        <th>Tổng tiền</th>
                        <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                    </tr>
                </tbody>
            </table>

            {{-- Dịch vụ đã chọn --}}
            <h5>Dịch vụ đã chọn:</h5>
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên dịch vụ</th>
                        <th>Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->services as $index => $service)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ number_format($service->price, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Cập nhật trạng thái --}}
            @php
                $statusTransitions = [
                    'pending' => ['paid', 'cancelled'],
                    'paid' => ['completed', 'cancelled'],
                    'completed' => [],
                    'cancelled' => [],
                ];
                $nextStatuses = $statusTransitions[$order->status] ?? [];
            @endphp

            <form method="POST" action="{{ route('orders.updateStatus', $order) }}" class="mt-4">
                @csrf

                @if (empty($nextStatuses))
                    <div class="alert alert-info">
                        Đơn hàng đã {{ $order->status === 'completed' ? 'hoàn tất' : 'bị hủy' }}. Không thể thay đổi trạng thái.
                    </div>
                @else
                    <div class="mb-3">
                        <label for="status" class="form-label">Cập nhật trạng thái:</label>
                        <select name="status" id="status" class="form-select">
                            @foreach ($nextStatuses as $status)
                                <option value="{{ $status }}">
                                    {{ $statusLabels[$status] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success">Cập nhật trạng thái</button>
                @endif

                <a href="{{ route('orders.exportPdf', $order) }}" class="btn btn-secondary ms-2">
                    <i class="bx bxs-file-pdf"></i> Xuất PDF
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary ms-2">
                    <i class="bx bx-arrow-back"></i> Quay lại danh sách
                </a>
            </form>

        </div>
    </div>
</div>
@endsection
