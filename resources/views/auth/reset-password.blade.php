<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>SmartCare | Đặt lại mật khẩu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('{{ asset('admin/assets/img/nen_yte.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            min-height: 100vh;
        }

        .container {
            display: flex;
            width: 100%;
            background: rgba(206, 221, 229, 0.618);
            backdrop-filter: blur(4px);
            border-radius: 16px;
        }

        .right-side {
            order: 1;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            animation: slideLeft 0.6s ease;
        }

        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(21, 0, 255, 0.1);
        }

        .form-box .logo {
            text-align: center;
            font-size: 40px;
            color: #0d6efd;
            margin-bottom: 10px;
        }

        .form-box h2 {
            text-align: center;
            color: #0d6efd;
            margin-bottom: 8px;
            font-size: 22px;
        }

        .form-box p.desc {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .form-box input {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        .form-box button {
            width: 100%;
            padding: 12px;
            background: #0d6efd;
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            transition: background 0.3s;
        }

        .form-box button:hover {
            background: #0846c3;
        }

        .form-box .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .form-box .message {
            color: green;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .field-wrapper {
            position: relative;
            margin-bottom: 16px;
        }

        .input-with-icon {
            width: 100%;
            padding: 12px 42px 12px 14px;
            /* chừa chỗ bên phải cho icon */
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            box-sizing: border-box;
        }


        .toggle-password {
            position: absolute;
            top: 23px;
            right: 16px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 16px;
            z-index: 2;
        }

        .left-side {
            order: 2;
            flex: 1;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            animation: slideRight 0.6s ease;
        }

        .left-side img {
            width: 180px;
            margin: 20px 0;
        }

        .left-side h3 {
            font-size: 20px;
            color: #0d6efd;
            margin-bottom: 20px;
        }

        .left-side p {
            color: #333;
            font-size: 15px;
            max-width: 350px;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .right-side,
            .left-side {
                order: unset;
                padding: 30px 20px;
            }
        }

        @keyframes slideLeft {
            from {
                transform: translateX(-50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideRight {
            from {
                transform: translateX(50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .orbit-static-container {
            position: relative;
            width: 360px;
            height: 360px;
            margin: 0 auto 40px;
        }

        .orbit-center {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 160px;
            transform: translate(-50%, -50%);
            z-index: 2;
        }

        .orbit-center img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .static-icon {
            position: absolute;
            width: 42px;
            height: 42px;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(55, 0, 255, 0.2);
            z-index: 1;
        }

        .icon1 {
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .icon2 {
            top: 25%;
            right: 0;
            transform: translateY(-50%);
        }

        .icon3 {
            bottom: 25%;
            right: 0;
            transform: translateY(50%);
        }

        .icon4 {
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .icon5 {
            bottom: 25%;
            left: 0;
            transform: translateY(50%);
        }

        .icon6 {
            top: 25%;
            left: 0;
            transform: translateY(-50%);
        }

        .back {
            margin-top: 20px;
            margin-left: 10px;
            text-align: center;
        }

        .back a {
            color: #0d6efd;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- FORM: RESET PASSWORD BÊN TRÁI -->
        <div class="right-side">
            <div class="form-box">
                <div class="logo"><i class="fas fa-shield-virus"></i></div>
                <h2>Đặt lại mật khẩu</h2>
                <p class="desc">Vui lòng nhập mật khẩu mới của bạn</p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="field-wrapper">
                        <input type="password" name="password" placeholder="Mật khẩu mới" id="password"
                            class="input-with-icon">
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                        @error('password')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field-wrapper">
                        <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu"
                            id="confirm">
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm')"></i>
                        @error('password_confirmation')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit">Xác nhận đặt lại</button>
                </form>
            </div>
        </div>

        <!-- BÊN PHẢI: HÌNH ẢNH VÀ MÔ TẢ -->
        <div class="left-side">
            <div class="orbit-static-container">
                <div class="static-icon icon1"><i class="fas fa-heartbeat"></i></div>
                <div class="static-icon icon2"><i class="fas fa-user-nurse"></i></div>
                <div class="static-icon icon3"><i class="fas fa-notes-medical"></i></div>
                <div class="static-icon icon4"><i class="fas fa-syringe"></i></div>
                <div class="static-icon icon5"><i class="fas fa-dna"></i></div>
                <div class="static-icon icon6"><i class="fas fa-pills"></i></div>

                <div class="orbit-center">
                    <img src="{{ asset('admin/assets/img/nen2.png') }}" alt="Ảnh bác sĩ">
                </div>
            </div>
            <h3>Đặt khám dễ dàng hơn với SmartCare</h3>
            <p>Trên ứng dụng SmartCare với hơn 500 bác sĩ, 80 phòng khám, 20 bệnh viện trên toàn quốc.</p>
        </div>
        <div class="back">
            <a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> Quay về đăng nhập</a>
        </div>

    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>
