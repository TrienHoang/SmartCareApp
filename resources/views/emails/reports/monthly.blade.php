<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Báo cáo thống kê</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Báo cáo thống kê theo tháng</h2>

    <table>
        <thead>
            <tr>
                <th>Ngày</th>
                <th>Số bác sĩ</th>
                <th>Số bệnh nhân</th>
                <th>Lịch hẹn</th>
                <th>Doanh thu</th>
                <th>Đang chờ</th>
                <th>Đã khám</th>
                <th>Đã huỷ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($statistics as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</td>
                    <td>{{ $row->total_doctors }}</td>
                    <td>{{ $row->total_patients }}</td>
                    <td>{{ $row->total_appointments }}</td>
                    <td>{{ number_format($row->total_revenue, 0, ',', '.') }} đ</td>
                    <td>{{ $row->appointments_pending }}</td>
                    <td>{{ $row->appointments_completed }}</td>
                    <td>{{ $row->appointments_cancelled }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
