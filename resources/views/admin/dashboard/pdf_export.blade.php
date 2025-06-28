<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            border-bottom: 3px solid #4472C4;
        }

        .header h1 {
            color: #4472C4;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .export-info {
            color: #666;
            font-size: 11px;
        }

        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #4472C4;
            color: white;
            padding: 12px 15px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            border-radius: 5px;
            /* text-transform: uppercase; */
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stats-row {
            display: table-row;
        }

        .stats-cell {
            display: table-cell;
            padding: 12px 15px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }

        .stats-header {
            background: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .stats-label {
            background: #f1f3f4;
            font-weight: 600;
            width: 40%;
        }

        .stats-value {
            text-align: right;
            font-weight: bold;
            color: #2e7d32;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th {
            background: #e3f2fd;
            color: #1565c0;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
        }

        .table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .table tbody tr:hover {
            background: #e1f5fe;
        }

        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-card {
            display: table-cell;
            width: 50%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .card-title {
            color: #1976d2;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            /* text-transform: uppercase; */
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: #f5f5f5;
            border-top: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }

        .metric-highlight {
            color: #d32f2f;
            font-weight: bold;
        }

        .positive {
            color: #388e3c;
        }

        .warning {
            color: #f57c00;
        }

        @page {
            margin: 20mm;
        }
    </style>
</head>

<body>


    <div class="header">
        <h1>BÁO CÁO THỐNG KÊ DASHBOARD</h1>
        <h2 style="color: #666; font-size: 18px; margin: 10px 0;">Hệ Thống Quản Lý Bệnh Viện</h2>
        <div class="export-info">
            <strong>Ngày xuất báo cáo:</strong> {{ $exportDate }}<br>
            <strong>Người xuất:</strong> Administrator
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="section">
        <div class="section-title">📊 Thống Kê Tổng Quan Hệ Thống</div>
        <div class="summary-cards">
            <div class="summary-row">
                <div class="summary-card">
                    <div class="card-title">💰 Tài Chính</div>
                    <div class="stats-grid">
                        <div class="stats-row">
                            <div class="stats-cell stats-label">Doanh thu hôm nay:</div>
                            <div class="stats-cell stats-value">{{ number_format($dailyStat->total_revenue) }} VND</div>
                        </div>
                        <div class="stats-row">
                            <div class="stats-cell stats-label">Doanh thu tổng:</div>
                            <div class="stats-cell stats-value positive">{{ number_format($globalStat->total_revenue) }}
                                VND</div>
                        </div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="card-title">👥 Nhân Sự</div>
                    <div class="stats-grid">
                        <div class="stats-row">
                            <div class="stats-cell stats-label">Tổng bác sĩ:</div>
                            <div class="stats-cell stats-value">{{ $globalStat->total_doctors }}</div>
                        </div>
                        <div class="stats-row">
                            <div class="stats-cell stats-label">Tổng bệnh nhân:</div>
                            <div class="stats-cell stats-value">{{ $globalStat->total_patients }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="section-title">📋 Thống Kê Lịch Hẹn Tổng</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Trạng thái</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Tổng lịch hẹn</strong></td>
                    <td>{{ $globalStat->total_appointments }}</td>
                </tr>
                <tr>
                    <td>Chờ xử lý</td>
                    <td>{{ $globalStat->appointments_pending }}</td>
                </tr>
                <tr>
                    <td>Đã xác nhận</td>
                    <td>{{ $globalStat->appointments_confirmed }}</td>
                </tr>
                <tr>
                    <td>Hoàn thành</td>
                    <td>{{ $globalStat->appointments_completed }}</td>
                </tr>
                <tr>
                    <td>Đã hủy</td>
                    <td>{{ $globalStat->appointments_cancelled }}</td>
                </tr>
            </tbody>
        </table>
    </div>


    <!-- Thống kê lịch hẹn hôm nay -->
    <div class="section">
        <div class="section-title">📅 Thống Kê Lịch Hẹn Hôm Nay</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell stats-header">Trạng thái</div>
                <div class="stats-cell stats-header">Số lượng</div>
                <div class="stats-cell stats-header">Tỷ lệ</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell stats-label">Tổng lịch hẹn</div>
                <div class="stats-cell stats-value">{{ $dailyStat->total_appointments }}</div>
                <div class="stats-cell stats-value">100%</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">Chờ xử lý</div>
                <div class="stats-cell warning">{{ $dailyStat->appointments_pending }}</div>
                <div class="stats-cell">
                    {{ $dailyStat->total_appointments > 0 ? round(($dailyStat->appointments_pending / $dailyStat->total_appointments) * 100, 1) : 0 }}%
                </div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">Đã xác nhận</div>
                <div class="stats-cell">{{ $dailyStat->appointments_confirmed }}</div>
                <div class="stats-cell">
                    {{ $dailyStat->total_appointments > 0 ? round(($dailyStat->appointments_confirmed / $dailyStat->total_appointments) * 100, 1) : 0 }}%
                </div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">Hoàn thành</div>
                <div class="stats-cell positive">{{ $dailyStat->appointments_completed }}</div>
                <div class="stats-cell">
                    {{ $dailyStat->total_appointments > 0 ? round(($dailyStat->appointments_completed / $dailyStat->total_appointments) * 100, 1) : 0 }}%
                </div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">Đã hủy</div>
                <div class="stats-cell metric-highlight">{{ $dailyStat->appointments_cancelled }}</div>
                <div class="stats-cell">
                    {{ $dailyStat->total_appointments > 0 ? round(($dailyStat->appointments_cancelled / $dailyStat->total_appointments) * 100, 1) : 0 }}%
                </div>
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Thống kê theo thời gian -->
    <div class="section">
        <div class="section-title">📈 Thống Kê Theo Thời Gian</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Số lượng đặt lịch</th>
                    <th>Doanh thu (VND)</th>
                    <th>Tăng trưởng</th>
                </tr>
            </thead>
            <tbody>
                @php $previousRevenue = 0; @endphp
                @foreach ($statTable as $stat)
                    <tr>
                        <td style="font-weight: bold;">{{ $stat['label'] }}</td>
                        <td>{{ number_format($stat['bookings']) }}</td>
                        <td style="text-align: right;">{{ number_format($stat['revenue']) }}</td>
                        <td>
                            @if ($previousRevenue > 0)
                                @php $growth = round((($stat['revenue'] - $previousRevenue) / $previousRevenue) * 100, 1); @endphp
                                <span class="{{ $growth >= 0 ? 'positive' : 'metric-highlight' }}">
                                    {{ $growth >= 0 ? '+' : '' }}{{ $growth }}%
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @php $previousRevenue = $stat['revenue']; @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Thống kê bác sĩ -->
    <div class="section">
        <div class="section-title">👨‍⚕️ Thống Kê Hiệu Suất Bác Sĩ</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên bác sĩ</th>
                    <th>Chuyên khoa</th>
                    <th>Lịch hoàn thành</th>
                    <th>Tổng lịch hẹn</th>
                    <th>Tỷ lệ hoàn thành</th>
                    <th>Đánh giá TB</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($doctorStats as $doctor)
                    <tr>
                        <td style="text-align: left; font-weight: bold;">{{ $doctor['name'] }}</td>
                        <td style="text-align: left;">{{ $doctor['specialization'] }}</td>
                        <td>{{ $doctor['completed_appointments'] }}</td>
                        <td>{{ $doctor['total_appointments'] }}</td>
                        <td>
                            @php $rate = $doctor['total_appointments'] > 0 ? round(($doctor['completed_appointments'] / $doctor['total_appointments']) * 100, 1) : 0; @endphp
                            {{ $rate }}%
                        </td>
                        <td>{{ $doctor['average_rating'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="section">
        <div class="section-title">🏥 Thống Kê Dịch Vụ Phổ Biến</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên dịch vụ</th>
                    <th>Lượt đặt</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($serviceStats ?? [] as $service)
                    <tr>
                        <td style="text-align: left;">{{ $service['name'] }}</td>
                        <td>{{ $service['bookings'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Không có dữ liệu dịch vụ</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="section">
        <div class="section-title">⚙️ Hiệu Suất Hoạt Động Hệ Thống</div>
        <table class="table">
            <tr>
                <th>Chỉ số</th>
                <th>Giá trị</th>
            </tr>
            <tr>
                <td>Tỷ lệ hủy lịch</td>
                <td>{{ $performanceStats['cancel_rate'] ?? '--' }}%</td>
            </tr>
            <tr>
                <td>Tỷ lệ khám đúng hẹn</td>
                <td>{{ $performanceStats['on_time_rate'] ?? '--' }}%</td>
            </tr>
            @if (isset($performanceStats['avg_waiting_time']))
                <tr>
                    <td>Thời gian chờ trung bình</td>
                    <td>{{ $performanceStats['avg_waiting_time'] }} phút</td>
                </tr>
            @endif
        </table>
    </div>
    <div class="section">
        <div class="section-title">👤 Thống Kê Bệnh Nhân</div>
        <table class="table">
            <tr>
                <th>Chỉ số</th>
                <th>Giá trị</th>
            </tr>
            <tr>
                <td>Bệnh nhân mới</td>
                <td>{{ $patientStats['new_this_week'] ?? '--' }}</td>
            </tr>
            <tr>
                <td>Tỷ lệ quay lại</td>
                <td>{{ $patientStats['return_rate'] ?? '--' }}%</td>
            </tr>
        </table>

        <h4 style="margin-top: 15px;">Phân Bố Bệnh Nhân Theo Khu Vực</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Khu vực</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patientStats['area'] ?? [] as $region => $count)
                    <tr>
                        <td style="text-align: left;">{{ ucfirst(trim($region)) }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Không có dữ liệu khu vực</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</body>

</html>
