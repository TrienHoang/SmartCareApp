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
            width: 50%;
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

        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        {{-- <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo"> --}}
        <h1>THỐNG KÊ BÁC SĨ</h1>
        <p><strong>Tên bác sĩ:</strong> {{ $doctor->user->full_name }}</p>
        <p><strong>Chuyên khoa:</strong> {{ $doctor->specialization ?? 'Chưa cập nhật' }}</p>
    </div>

    <div class="section-title">Tổng quan</div>
    <table class="info-table">
        <tr>
            <td class="label">Tổng bệnh nhân</td>
            <td class="value">{{ $totalPatients }}</td>
        </tr>
        <tr>
            <td class="label">Lịch hẹn hôm nay</td>
            <td class="value">{{ $todayAppointments }}</td>
        </tr>
        <tr>
            <td class="label">Tổng doanh thu</td>
            <td class="value">{{ number_format($totalRevenue, 0, ',', '.') }} đ</td>
        </tr>
    </table>

    <div class="section-title">Lịch hẹn hoàn thành 7 ngày gần nhất</div>
    <table class="data-table">
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

    <div class="footer">
        SmartCare System - {{ now()->format('d/m/Y') }}
    </div>

</body>
</html>
