<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Đặt lại mật khẩu | SmartCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #2d3748;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            max-height: 60px;
        }

        h1 {
            text-align: center;
            color: #2c5282;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        .btn {
            display: block;
            width: fit-content;
            margin: 30px auto;
            background-color: #2b6cb0;
            color: white;
            padding: 14px 28px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }

        .note {
            font-size: 14px;
            color: #718096;
            margin-top: 30px;
            text-align: center;
        }

        .support {
            margin-top: 40px;
            font-size: 14px;
            text-align: center;
            color: #4a5568;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #a0aec0;
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        .text-btn{
          color: azure;
        }

        @media (max-width: 640px) {
            .container {
                padding: 24px;
                margin: 20px;
            }

            .btn {
                padding: 12px 20px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Logo thương hiệu --}}
            <img src="{{ asset('images/logo.png') }}" alt="SmartCare">
        </div>

        <h1>Đặt lại mật khẩu</h1>

        <p>Xin chào,</p>
        <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản SmartCare. Nhấn vào nút bên dưới để tiến hành:</p>

        <a href="{{ $resetLink }}" class="btn"><p class="text-btn">Đặt lại mật khẩu</p></a>
        

        <p>Nếu bạn không thực hiện yêu cầu này, bạn có thể bỏ qua email này. Liên kết đặt lại mật khẩu sẽ hết hạn sau <strong>60 phút</strong>.</p>

        <div class="support">
            Cần hỗ trợ? Vui lòng liên hệ chúng tôi tại  
            <a href="mailto:khanhndph50171@gmail.com">khanhndph50171@gmail.com</a> hoặc gọi <strong>0963786035</strong>.
        </div>

        <div class="footer">
            © {{ date('Y') }} SmartCare. All rights reserved.<br>
            252 Đưỡng Mỹ Đình, Quận Nam Từ Liêm, TP.Hà Nội
        </div>
    </div>
</body>
</html>
