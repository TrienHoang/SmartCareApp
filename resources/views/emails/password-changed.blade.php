<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo thay đổi mật khẩu</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; border-radius: 8px;">
        <h2 style="color: #0d6efd;">Xin chào {{ $user->name ?? $user->email }},</h2>

        <p>
            Chúng tôi ghi nhận rằng bạn đã <strong>thay đổi mật khẩu</strong> tài khoản SmartCare vào lúc 
            <strong>{{ now()->format('H:i \n\g\à\y d/m/Y') }}</strong>.
        </p>

        <p>
            Nếu đây là hành động do chính bạn thực hiện, vui lòng bỏ qua email này.
        </p>

        <p>
            <strong>Nếu bạn không thực hiện thay đổi này</strong>, tài khoản của bạn có thể đã bị truy cập trái phép. 
            Vui lòng <a href="mailto:support@smartcare.vn">liên hệ ngay với chúng tôi</a> để được hỗ trợ kịp thời.
        </p>

        <p>
            Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của SmartCare.
        </p>

        <p style="margin-top: 30px;">
            Trân trọng,<br>
            <strong>Đội ngũ SmartCare</strong><br>
            <small style="color: #888;">Email tự động - vui lòng không phản hồi</small>
        </p>
    </div>
</body>
</html>
