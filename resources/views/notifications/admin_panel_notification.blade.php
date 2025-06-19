<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Thông báo mới | {{ config('app.name') }}</title>
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
        h2 {
            color: #2d3748;
            margin-top: 30px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            background-color: #2b6cb0;
            color: white !important;
            padding: 14px 28px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            margin: 30px auto;
        }
        .support, .footer {
            font-size: 14px;
            text-align: center;
            color: #4a5568;
            margin-top: 40px;
        }
        .footer {
            font-size: 13px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
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
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo">
        </div>

        <h1>📢  Thông báo mới từ {{ config('app.name') }}</h1>

        <p>Xin chào <strong>{{ $user->full_name ?? 'bạn' }}</strong>,</p>

        <p>Bạn có một thông báo mới từ hệ thống. Dưới đây là nội dung chi tiết:</p>

        <h2>Tiêu đề:</h2>
        <p><strong>{{ $notification['title'] }}</strong></p>

        <h2>Nội dung:</h2>
        <div>{!! $notification['content'] !!}</div>

        <div style="text-align: center;">
            <a href="{{ url('/my-notifications') }}" class="btn">Xem tất cả thông báo</a>
        </div>

        <div class="support">
            Cần hỗ trợ? Vui lòng liên hệ <a href="mailto:support@smartcare.com">support@smartcare.com</a> hoặc gọi <strong>0334596973</strong>.
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            252 Đường Mỹ Đình, Quận Nam Từ Liêm, Hà Nội
        </div>
    </div>
</body>
</html>
