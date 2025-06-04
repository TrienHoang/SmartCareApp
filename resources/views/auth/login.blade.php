{{-- resources/views/auth/login.blade.php --}}
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<div class="container" data-form-type="{{ old('form_type') }}">
    <div class="singin-singup">
        <form action="{{ route('postLogin') }}" method="POST" class="sign-in-form">
            @csrf
            @if (session('message'))
                <p class="text-danger">{{ session('message') }}</p>
            @elseif (old('form_type') === 'login' && $errors->any())
                @if ($errors->has('username'))
                    <p class="text-danger">{{ $errors->first('username') }}</p>
                @endif
            @endif
            
            <input type="hidden" name="form_type" value="login">

            <h2 class="title">SmartCare | Đăng nhập</h2>

            <div class="input-field">
                <i class="fas fa-user-md"></i>
                <input type="text" placeholder="Tên đăng nhập" name="username">
            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Mật khẩu" name="password">
            </div>
            <input type="submit" value="Đăng nhập" class="btn">

            <div style="text-align: right; margin-top: 10px;">
                <a href="{{ route('password.request') }}" style="color: #0d6efd;">Quên mật khẩu?</a>
            </div>

            <p class="social-text">Hoặc đăng nhập bằng nền tảng</p>
            <div class="social-media">
                <a href="{{ route('facebook.login') }}" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="{{ route('google.login') }}" class="social-icon"><i class="fab fa-google"></i></a>
            </div>

            <p class="account-text">Chưa có tài khoản? - <a href="#" id="sign-up-btn2">Đăng ký</a></p>
        </form>

        <form action="{{ route('postRegister') }}" method="POST" class="sign-up-form">
            @csrf
            @if (session('success'))
                <p class="text-success">{{ session('success') }}</p>
            @elseif (old('form_type') === 'register' && $errors->any())
                <ul class="text-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <input type="hidden" name="form_type" value="register">
            <h2 class="title">SmartCare | Đăng ký</h2>

            <div class="input-field">
                <i class="fas fa-user-md"></i>
                <input type="text" name="username" placeholder="Tên đăng nhập">
            </div>
            <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Mật khẩu">
            </div>
            <input type="submit" value="Đăng ký" class="btn">

            <p class="social-text">Hoặc đăng nhập bằng nền tảng</p>
            <div class="social-media">
                <a href="{{ route('facebook.login') }}" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="{{ route('google.login') }}" class="social-icon"><i class="fab fa-google"></i></a>
            </div>
        </form>
    </div>

    <div class="panels-container">
        <div class="panel left-panel">
            <div class="content">
                <h3>Đã có tài khoản?</h3>
                <p>Chào mừng đến với SmartCare - cửa hàng điện thoại số 1 Việt Nam.</p>
                <button class="btn" id="sign-in-btn">Đăng nhập</button>
            </div>
            <img src="{{ asset('LayoutClient/img/icon1.png') }}" alt="" class="image">
        </div>

        <div class="panel right-panel">
            <div class="content">
                <h3>Chưa có tài khoản?</h3>
                <p>Chào mừng đến với SmartCare - cửa hàng điện thoại số 1 Việt Nam.</p>
                <button class="btn" id="sign-up-btn">Đăng ký</button>
            </div>
            <img src="{{ asset('LayoutClient/img/icon2.png') }}" alt="" class="image">
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</div>
