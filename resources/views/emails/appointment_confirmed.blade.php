<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông báo xác nhận lịch hẹn</title>
</head>

<body>
    <h2>Kính gửi {{ $appointment->patient->full_name ?? 'Quý khách' }},</h2>

    <p>Chúng tôi xin thông báo lịch hẹn khám bệnh của Quý khách tại <strong>SmartCare</strong> đã được <strong>xác nhận
            thành công</strong>.</p>

    <p><strong>Chi tiết lịch hẹn:</strong></p>
    <ul>
        <li><strong>Ngày đặt lịch:</strong> {{ \Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y H:i') }}
        </li>
        <li><strong>Ngày khám:</strong> {{ $appointment->appointment_time }}</li>
        <li><strong>Dịch vụ:</strong> {{ $appointment->service->name ?? 'Không xác định' }}</li>
        <li><strong>Bác sĩ phụ trách:</strong> {{ $appointment->doctor->user->full_name ?? 'Không xác định' }}</li>
        <li><strong>Phòng khám:</strong> {{ $appointment->doctor->room->name ?? 'Không xác định' }}
        </li>
        <li><strong>Trạng thái:</strong> Đã xác nhận</li>
    </ul>

    <p><strong>Chúng tôi có đính kèm theo mã qr, hãy đến quầy lễ tân để check in</strong></p>


    <p>Quý khách vui lòng đến đúng giờ để được phục vụ tốt nhất.</p>

    <p>Xin cảm ơn Quý khách đã tin tưởng sử dụng dịch vụ của <strong>SmartCare</strong>.</p>

    <p>Trân trọng,<br>
        <strong>Đội ngũ SmartCare</strong>
    </p>
</body>

</html>