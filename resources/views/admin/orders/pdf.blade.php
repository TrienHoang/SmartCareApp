<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        h2, h4 { margin: 0 0 10px 0; }
    </style>
</head>
<body>
    <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>

    <table>
        <tbody>
            <tr>
                <th>Người đặt</th>
                <td>{{ $order->user->full_name }}</td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td>{{ ucfirst($order->status) }}</td>
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

    <h4>Dịch vụ đã đặt:</h4>
    <table>
        <thead>
            <tr>
                <th>STT</th>
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
</body>
</html>
