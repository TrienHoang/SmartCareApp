<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>SmartCare | Quên mật khẩu</title>
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
            */
        }

        /* FORM QUÊN MẬT KHẨU (chuyển sang bên trái) */
        .right-side {
            order: 1;
            flex: 1;
            /* background: #f9fcff; */
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
            /* box-shadow: 0 4px 16px rgba(2,0,0,0.0); */


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

        .form-box input[type=text] {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 15px;
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

        .form-box .back {
            margin-top: 20px;
            text-align: center;
        }

        .form-box .back a {
            color: #0d6efd;
            text-decoration: none;
            font-size: 14px;
        }

        /* PHẦN GIỚI THIỆU (chuyển sang phải) */
        .left-side {
            order: 2;
            flex: 1;
            /* background: #fff; */
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

            .right-side {
                order: 1;
                padding: 30px 20px;
            }

            .left-side {
                order: 2;
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

        /* Ảnh trung tâm */
        .orbit-center {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 160px;
            transform: translate(-50%, -50%);
            /* box-shadow:  rgba(0, 13, 255, 0.2); */
            z-index: 2;
        }

        .orbit-center img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
        }

        /* Các icon cố định xung quanh ảnh */
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

        /* Đặt icon đều quanh hình ảnh */
        .icon1 {
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Trên */
        .icon2 {
            top: 25%;
            right: 0;
            transform: translateY(-50%);
        }

        /* Trên phải */
        .icon3 {
            bottom: 25%;
            right: 0;
            transform: translateY(50%);
        }

        /* Dưới phải */
        .icon4 {
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Dưới */
        .icon5 {
            bottom: 25%;
            left: 0;
            transform: translateY(50%);
        }

        /* Dưới trái */
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

        .input-error {
            border: 1px solid red;
            background-color: #fff4f4;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Trên trái */
    </style>
</head>

<body>
    <div class="container">

        <!-- FORM: BÊN TRÁI -->
        <div class="right-side">
            <div class="form-box">
                <div class="logo">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <h2>Quên mật khẩu?</h2>
                <p class="desc">Nhập email bạn đã đăng ký để nhận liên kết khôi phục mật khẩu</p>

                @if (session('message'))
                    <p class="message">{{ session('message') }}</p>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <input type="text" name="email" placeholder="Email đăng ký...">
                    @error('email')
                        <p class="error-message" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                    <button type="submit">Gửi liên kết</button>
                </form>


            </div>
        </div>

        <!-- GIỚI THIỆU: BÊN PHẢI -->
        <div class="left-side">
            <div class="orbit-static-container">
                <!-- Icon xếp quanh -->
                <div class="static-icon icon1"><i class="fas fa-heartbeat"></i></div>
                <div class="static-icon icon2"><i class="fas fa-user-nurse"></i></div>
                <div class="static-icon icon3"><i class="fas fa-notes-medical"></i></div>
                <div class="static-icon icon4"><i class="fas fa-syringe"></i></div>
                <div class="static-icon icon5"><i class="fas fa-dna"></i></div>
                <div class="static-icon icon6"><i class="fas fa-pills"></i></div>

                <!-- Ảnh trung tâm -->
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
</body>

</html>
