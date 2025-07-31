<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông báo hoàn tiền lịch hẹn</title>
</head>

<body>
    <h2>Kính gửi {{ $appointment->patient->full_name ?? 'Quý khách' }},</h2>

    <p>Chúng tôi xin thông báo rằng lịch hẹn khám bệnh của Quý khách tại <strong>SmartCare</strong> đã được <strong>hoàn
            tiền thành công</strong>.</p>

    <p><strong>Chi tiết hoàn tiền:</strong></p>
    <ul>
        <li><strong>Mã lịch hẹn:</strong> #{{ $appointment->id }}</li>
        <li><strong>Ngày hoàn tiền:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</li>
        <li><strong>Số tiền hoàn lại:</strong> {{ number_format($appointment->payment->amount, 0, ',', '.') }} đ</li>
        <li><strong>Dịch vụ:</strong> {{ $appointment->service->name ?? 'Không xác định' }}</li>
        <li><strong>Bác sĩ:</strong> {{ $appointment->doctor->user->full_name ?? 'Không xác định' }}</li>
        <li><strong>Phòng khám:</strong> {{ $appointment->doctor->room->name ?? 'Không xác định' }}</li>
        <li><strong>Trạng thái lịch hẹn:</strong> Đã hoàn tiền</li>
    </ul>

    @if ($reason)
        <p><strong>Lý do hoàn tiền:</strong> {{ $reason }}</p>
    @endif

    <p>Chúng tôi rất tiếc nếu có bất kỳ sự bất tiện nào xảy ra. Nếu Quý khách cần hỗ trợ thêm, xin vui lòng liên hệ lại
        với chúng tôi.</p>

    <p>Cảm ơn Quý khách đã tin tưởng và sử dụng dịch vụ của <strong>SmartCare</strong>.</p>

    <p>Trân trọng,<br>
        <strong>Đội ngũ SmartCare</strong>
    </p>
</body>

</html>
