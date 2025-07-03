<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê bác sĩ</title>
</head>
<body style="font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #000;">
    <table width="100%" style="margin-bottom: 20px;">
        <tr>
            <td colspan="2" align="center" style="font-size: 18px; font-weight: bold; color: #4472C4;">
                THỐNG KÊ BÁC SĨ
            </td>
        </tr>
        <tr>
            <td><strong>Tên bác sĩ:</strong> {{ $doctor->user->full_name }}</td>
            <td><strong>Chuyên khoa:</strong> {{ $doctor->specialization ?? 'Chưa cập nhật' }}</td>
        </tr>
    </table>

    <table width="100%" border="1" cellpadding="8" cellspacing="0" style="margin-bottom: 20px; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #4472C4; color: #fff; font-weight: bold;">
                <th colspan="2">TỔNG QUAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Tổng bệnh nhân</strong></td>
                <td align="right">{{ $totalPatients }}</td>
            </tr>
            <tr>
                <td><strong>Lịch hẹn hôm nay</strong></td>
                <td align="right">{{ $todayAppointments }}</td>
            </tr>
            <tr>
                <td><strong>Tổng doanh thu</strong></td>
                <td align="right">{{ number_format($totalRevenue, 0, ',', '.') }}đ</td>
            </tr>
        </tbody>
    </table>

    <table width="100%" border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
        <thead>
            <tr style="background-color: #4472C4; color: #fff; font-weight: bold;">
                <th colspan="2">LỊCH HẸN HOÀN THÀNH 7 NGÀY GẦN NHẤT</th>
            </tr>
            <tr style="background-color: #e3f2fd;">
                <th>Ngày</th>
                <th>Số lịch hoàn thành</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitsChart as $row)
                <tr>
                    <td>{{ $row['day'] }}</td>
                    <td align="right">{{ $row['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
