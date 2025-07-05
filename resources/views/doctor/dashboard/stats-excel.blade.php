<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê bác sĩ</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; padding: 20px 0; border-bottom: 3px solid #4472C4; }
        .header h1 { color: #4472C4; font-size: 22px; font-weight: bold; margin-bottom: 10px; }
        .section-title { background: #4472C4; color: white; padding: 10px 15px; font-size: 15px; font-weight: bold; margin-bottom: 15px; border-radius: 5px; }
        .stats-grid { display: table; width: 100%; margin-bottom: 20px; }
        .stats-row { display: table-row; }
        .stats-cell { display: table-cell; padding: 10px 15px; border: 1px solid #ddd; vertical-align: middle; }
        .stats-label { background: #f1f3f4; font-weight: 600; width: 40%; }
        .stats-value { text-align: right; font-weight: bold; color: #2e7d32; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { background: #e3f2fd; color: #1565c0; padding: 10px 8px; text-align: center; font-weight: bold; border: 1px solid #ddd; font-size: 11px; }
        .table td { padding: 8px 8px; border: 1px solid #ddd; text-align: center; font-size: 10px; }
        .table tbody tr:nth-child(even) { background: #f9f9f9; }
        .table tbody tr:hover { background: #e1f5fe; }
        .positive { color: #388e3c; }
        .warning { color: #f57c00; }
        .metric-highlight { color: #d32f2f; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>THỐNG KÊ BÁC SĨ</h1>
        <div style="color: #666; font-size: 13px;">
            <strong>Tên bác sĩ:</strong> {{ $doctor->user->full_name }}<br>
            <strong>Chuyên khoa:</strong> {{ $doctor->specialization ?? 'Chưa cập nhật' }}
        </div>
    </div>

    <div class="section-title">📊 Tổng Quan</div>
    <div class="stats-grid">
        <div class="stats-row">
            <div class="stats-cell stats-label">Tổng bệnh nhân</div>
            <div class="stats-cell stats-value">{{ $totalPatients }}</div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Lịch hẹn hôm nay</div>
            <div class="stats-cell stats-value">{{ $todayAppointments }}</div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Tổng doanh thu</div>
            <div class="stats-cell stats-value">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
        </div>
    </div>

    <div class="section-title">📅 Lịch hẹn hoàn thành 7 ngày gần nhất</div>
    <table class="table">
        <thead>
            <tr>
                <th>Ngày</th>
                <th>Số lịch hoàn thành</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitsChart as $row)
                <tr>
                    <td>{{ $row['day'] }}</td>
                    <td>{{ $row['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>