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

            <h2 class="title">SmartCare | Sign in</h2>

            <div class="input-field">
                <i class="fas fa-user-md"></i>
                <input type="text" placeholder="Username" name="username">
            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Password" name="password">
            </div>
            <input type="submit" value="Login" class="btn">

            <p class="social-text">Hoặc đăng nhập bằng nền tảng</p>
            <div class="social-media">
                <a href="{{ route('facebook.login') }}" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="{{ route('google.login') }}" class="social-icon"><i class="fab fa-google"></i></a>
            </div>

            <p class="account-text">Quên mật khẩu? - <a href="#" id="sign-up-btn2">Đăng ký</a></p>
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
            <h2 class="title">SmartCare | Sign up</h2>

            <div class="input-field">
                <i class="fas fa-user-md"></i>
                <input type="text" name="username" placeholder="Username">
            </div>
            <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password">
            </div>
            <input type="submit" value="Sign up" class="btn">

            <p class="social-text">Hoặc đăng nhập bằng nền tảng</p>
            <div class="social-media">
                <a href="{{ route('facebook.login') }}" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="{{ route('google.login') }}" class="social-icon"><i class="fab fa-google"></i></a>
            </div>
        </form>
    </div>

    <div class="panels-container">
        <div class="panel left-panel">
            <div class="content">
                <h3>Member of Brand?</h3>
                <p>Welcome to SmartCare, Vietnam's number 1 phone store.</p>
                <button class="btn" id="sign-in-btn">Sign in</button>
            </div>
            <img src="{{ asset('LayoutClient/img/icon1.png') }}" alt="" class="image">
        </div>

        <div class="panel right-panel">
            <div class="content">
                <h3>New to Brand?</h3>
                <p>Welcome to SmartCare, Vietnam's number 1 phone store.</p>
                <button class="btn" id="sign-up-btn">Sign up</button>
            </div>
            <img src="{{ asset('LayoutClient/img/icon2.png') }}" alt="" class="image">
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</div>
