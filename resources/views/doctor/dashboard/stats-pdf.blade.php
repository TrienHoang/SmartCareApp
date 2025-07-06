<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê bác sĩ</title>
    <style>
        * {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            box-sizing: border-box;
        }
        body {
            margin: 20px;
            color: #2c3e50;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            color: #1a73e8;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 13px;
            margin: 0;
            color: #555;
        }
        .section-title {
            background-color: #1a73e8;
            color: #fff;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            margin: 25px 0 10px;
            border-radius: 4px;
        }
        .info-table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .info-table td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        .info-table td.label {
            background-color: #f1f3f4;
            font-weight: bold;
            width: 60%;
        }
        .info-table td.value {
            text-align: right;
            font-weight: bold;
            color: #0f9d58;
        }
        .data-table th, .data-table td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        .data-table th {
            background-color: #e8f0fe;
            font-weight: bold;
            text-align: center;
            color: #1a237e;
        }
        .data-table td {
            text-align: center;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #999;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>THỐNG KÊ BÁC SĨ</h1>
    <p><strong>Tên bác sĩ:</strong> {{ $doctor->user->full_name ?? 'Không rõ' }}</p>
    <p><strong>Chuyên khoa:</strong> {{ $doctor->specialization ?? 'Chưa cập nhật' }}</p>
</div>

{{-- Tổng quan --}}
<div class="section-title">Tổng quan</div>
<table class="info-table">
    <tr><td class="label">Tổng lịch hẹn</td><td class="value">{{ $totalAppointments }}</td></tr>
    <tr><td class="label">Lịch hẹn hôm nay</td><td class="value">{{ $todayAppointments }}</td></tr>
    <tr><td class="label">Lịch hẹn trong tuần</td><td class="value">{{ $weekAppointments }}</td></tr>
    <tr><td class="label">Lịch hẹn trong tháng</td><td class="value">{{ $monthAppointments }}</td></tr>
    <tr><td class="label">Tổng bệnh nhân</td><td class="value">{{ $totalPatients }}</td></tr>
    <tr><td class="label">Tổng đơn thuốc</td><td class="value">{{ $totalPrescriptions }}</td></tr>
    <tr><td class="label">Đơn thuốc hôm nay</td><td class="value">{{ $todayPrescriptions }}</td></tr>
</table>

{{-- Tỷ lệ lịch hẹn --}}
<div class="section-title">Tỷ lệ lịch hẹn</div>
<table class="info-table">
    <tr><td class="label">Lịch hẹn hoàn thành</td><td class="value">{{ $successAppointments }} ({{ $successRate }}%)</td></tr>
    <tr><td class="label">Lịch hẹn bị hủy</td><td class="value">{{ $cancelAppointments }} ({{ $cancelRate }}%)</td></tr>
</table>

{{-- Trạng thái lịch hẹn --}}
<div class="section-title">Trạng thái lịch hẹn hiện tại</div>
<table class="info-table">
    <tr><td class="label">Đang chờ xác nhận</td><td class="value">{{ $appointments_pending }}</td></tr>
    <tr><td class="label">Đã xác nhận</td><td class="value">{{ $appointments_confirmed }}</td></tr>
    <tr><td class="label">Đã hoàn thành</td><td class="value">{{ $appointments_completed }}</td></tr>
    <tr><td class="label">Đã hủy</td><td class="value">{{ $appointments_cancelled }}</td></tr>
</table>

{{-- Top thuốc --}}
<div class="section-title">Top 5 thuốc được kê nhiều nhất</div>
@if($topPrescribed->count())
    <table class="data-table">
        <thead>
        <tr>
            <th>Tên thuốc</th>
            <th>Số lần kê</th>
        </tr>
        </thead>
        <tbody>
        @foreach($topPrescribed as $item)
            <tr>
                <td>{{ $item->medicine_name }}</td>
                <td>{{ $item->total }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p style="text-align:center; color:#888;">Không có dữ liệu đơn thuốc.</p>
@endif

{{-- Nghỉ phép & Ca làm --}}
<div class="section-title">Nghỉ phép & Ca làm</div>
<table class="info-table">
    <tr><td class="label">Tổng yêu cầu nghỉ phép</td><td class="value">{{ $totalLeaves }}</td></tr>
    <tr><td class="label">Số yêu cầu đã duyệt</td><td class="value">{{ $approvedLeaves }}</td></tr>
    <tr><td class="label">Tổng ca làm việc</td><td class="value">{{ $totalHoursWorked }}</td></tr>
</table>

{{-- Tăng trưởng --}}
<div class="section-title">Tăng trưởng</div>
<table class="info-table">
    <tr><td class="label">Tăng trưởng lịch hẹn</td><td class="value">{{ $growthValue }}% ({{ $growthLabel }})</td></tr>
</table>

{{-- Biểu đồ --}}
<div class="section-title">Thống kê {{ $type == 'month' ? 'theo ngày' : 'theo tháng' }}</div>
<table class="data-table">
    <thead>
        <tr>
            <th>Thời gian</th>
            <th>Số lịch hẹn</th>
        </tr>
    </thead>
    <tbody>
        @foreach($statLabels as $i => $label)
            <tr>
                <td>{{ $label }}</td>
                <td>{{ $statBookings[$i] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    SmartCare System - {{ now()->format('d/m/Y') }}
</div>

</body>
</html>
