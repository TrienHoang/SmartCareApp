{{-- resources/views/auth/verify_otp.blade.php --}}

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .otp-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .otp-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin: 0 auto;
        }

        .otp-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .otp-title {
            color: #333;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .otp-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            color: #333;
            background: #f8f9fa;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .otp-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: scale(1.05);
        }

        .otp-input.filled {
            border-color: #28a745;
            background: #f8fff9;
        }

        .otp-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 20px;
        }

        .otp-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .otp-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .otp-timer {
            color: #dc3545;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .otp-resend {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .otp-resend:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .otp-resend:disabled {
            color: #ccc;
            cursor: not-allowed;
            pointer-events: none;
        }

        .otp-back {
            display: inline-block;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .otp-back:hover {
            color: #667eea;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }
    </style>
</head>

<div class="otp-container">
    <div class="otp-card">
        <h2 class="otp-title">Xác thực OTP</h2>
        <p class="otp-subtitle">Vui lòng nhập mã 6 số đã được gửi đến email của bạn</p>

        <form action="{{ route('verify.otp') }}" method="POST" id="otpForm">
            @csrf

            <div class="otp-inputs">
                <input type="text" class="otp-input" maxlength="1" data-index="0">
                <input type="text" class="otp-input" maxlength="1" data-index="1">
                <input type="text" class="otp-input" maxlength="1" data-index="2">
                <input type="text" class="otp-input" maxlength="1" data-index="3">
                <input type="text" class="otp-input" maxlength="1" data-index="4">
                <input type="text" class="otp-input" maxlength="1" data-index="5">
            </div>

            <input type="hidden" name="otp" id="hiddenOtp">

            <div class="loading-spinner"></div>

            <button type="submit" class="otp-submit" id="submitBtn">
                <i class="fas fa-shield-check"></i> Xác nhận
            </button>
        </form>

        <div class="otp-timer" id="timer" style="display: none;">
            Gửi lại sau: <span id="countdown">60</span>s
        </div>

        <form action="{{ route('resend.otp') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="otp-resend" style="background:none;border:none;color:#0d6efd;cursor:pointer;">
                <i class="fas fa-redo"></i> Gửi lại mã OTP
            </button>
        </form>


        <a href="{{ route('login') }}" class="otp-back">
            <i class="fas fa-arrow-left"></i> Quay lại đăng nhập
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
            $('.otp-card').addClass('shake');
            setTimeout(() => $('.otp-card').removeClass('shake'), 500);
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
            $('.otp-card').addClass('shake');
            setTimeout(() => $('.otp-card').removeClass('shake'), 500);
        @endif

        $(document).ready(function() {
            const inputs = $('.otp-input');
            let countdown = 60;

            // Auto focus first input
            inputs.first().focus();

            // Handle input events
            inputs.on('input', function() {
                const value = $(this).val();
                const index = parseInt($(this).data('index'));

                // Only allow numbers
                if (!/^\d$/.test(value) && value !== '') {
                    $(this).val('');
                    return;
                }

                // Add filled class
                if (value) {
                    $(this).addClass('filled');
                    // Move to next input
                    if (index < 5) {
                        inputs.eq(index + 1).focus();
                    }
                } else {
                    $(this).removeClass('filled');
                }

                updateHiddenInput();
                updateSubmitButton();
            });

            // Handle backspace
            inputs.on('keydown', function(e) {
                const index = parseInt($(this).data('index'));

                if (e.key === 'Backspace' && !$(this).val() && index > 0) {
                    inputs.eq(index - 1).focus().val('').removeClass('filled');
                    updateHiddenInput();
                    updateSubmitButton();
                }
            });

            // Handle paste
            inputs.on('paste', function(e) {
                e.preventDefault();
                const paste = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
                const digits = paste.replace(/\D/g, '').slice(0, 6);

                digits.split('').forEach((digit, index) => {
                    if (index < 6) {
                        inputs.eq(index).val(digit).addClass('filled');
                    }
                });

                updateHiddenInput();
                updateSubmitButton();

                if (digits.length === 6) {
                    inputs.eq(5).focus();
                }
            });

            function updateHiddenInput() {
                const otp = inputs.map(function() {
                    return $(this).val();
                }).get().join('');
                $('#hiddenOtp').val(otp);
            }

            function updateSubmitButton() {
                const otp = $('#hiddenOtp').val();
                $('#submitBtn').prop('disabled', otp.length !== 6);
            }

            // Form submission
            $('#otpForm').on('submit', function() {
                $('.loading-spinner').show();
                $('#submitBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Đang xác thực...');
            });

            // Bắt đầu đếm ngược dựa theo localStorage (để khi reload vẫn giữ thời gian còn lại)
            function startCountdown() {
                let storedExpire = localStorage.getItem('otp_expire_time');
                let now = Math.floor(Date.now() / 1000);

                // Nếu chưa có hoặc đã hết hạn => đặt lại thời gian hết hạn mới
                if (!storedExpire || now >= storedExpire) {
                    storedExpire = now + 60;
                    localStorage.setItem('otp_expire_time', storedExpire);
                }

                const timer = setInterval(() => {
                    let nowTime = Math.floor(Date.now() / 1000);
                    let remaining = storedExpire - nowTime;

                    if (remaining <= 0) {
                        clearInterval(timer);
                        $('#timer').hide();
                        localStorage.removeItem('otp_expire_time');
                        $('button[type="submit"].otp-resend').prop('disabled', false);
                    } else {
                        $('#timer').show();
                        $('#countdown').text(remaining);
                        $('button[type="submit"].otp-resend').prop('disabled', false);
                    }
                }, 1000);
            }

            // Gọi ngay khi load trang
            startCountdown();

            window.resendOTP = function() {
                let storedExpire = localStorage.getItem('otp_expire_time');
                let now = Math.floor(Date.now() / 1000);
                let remaining = storedExpire ? storedExpire - now : 0;

                if (remaining > 0) return; // chưa hết thời gian thì không gửi lại

                $.post('{{ route('resend.otp') }}', {
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    toastr.success('Mã OTP mới đã được gửi!');
                    // Clear inputs
                    inputs.val('').removeClass('filled');
                    inputs.first().focus();
                    updateHiddenInput();
                    updateSubmitButton();

                    // Đặt lại thời gian hết hạn và bắt đầu lại countdown
                    localStorage.setItem('otp_expire_time', Math.floor(Date.now() / 1000) + 60);
                    startCountdown();
                }).fail(function() {
                    toastr.error('Có lỗi xảy ra. Vui lòng thử lại!');
                });
            };


            window.resendOTP = function() {
                if (countdown > 0) return;

                $.post('{{ route('resend.otp') }}', {
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    toastr.success('Mã OTP mới đã được gửi!');
                    // Clear inputs
                    inputs.val('').removeClass('filled');
                    inputs.first().focus();
                    updateHiddenInput();
                    updateSubmitButton();
                    startCountdown();
                }).fail(function() {
                    toastr.error('Có lỗi xảy ra. Vui lòng thử lại!');
                });
            };
        });
    </script>
</div>
