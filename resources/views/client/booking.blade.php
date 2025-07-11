@extends('client.layouts.app')

@section('title', 'Đặt lịch khám bệnh')

@push('styles')
    <style>
        .gradient-text {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-bg {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            transition: all 0.3s ease;
        }

        .gradient-bg:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .step-circle {
            transition: all 0.3s ease;
        }

        .step-circle.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .step-circle.completed {
            background: #10b981;
            color: white;
        }

        .progress-line {
            transition: all 0.3s ease;
        }

        .progress-line.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .progress-line.completed {
            background: #10b981;
            color: white;
        }

        .form-input {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-input.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .step {
            display: none;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }

        .step.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: white;
            padding: 2.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 90%;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.show .modal-content {
            transform: translateY(0);
        }

        /* Guide styles */
        .guide-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .guide-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .guide-section {
            margin-bottom: 1.5rem;
        }

        .guide-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .guide-toggle {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .guide-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .guide-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: 400px;
            max-width: 90%;
            height: 100%;
            background: white;
            z-index: 1002;
            padding: 20px;
            box-shadow: -2px 0 6px rgba(0, 0, 0, 0.2);
            overflow-y: auto;

            /* ẩn ban đầu */
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .guide-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .guide-overlay.active {
            display: block;
            opacity: 1;
        }

        .guide-panel.active {
            transform: translateX(0);
        }


        .guide-panel.open {
            right: 0;
        }

        /*
            .guide-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            } */

        .guide-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .tip-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }

        .requirement-list {
            list-style: none;
            padding: 0;
        }

        .requirement-list li {
            display: flex;
            align-items: center;
            margin: 0.5rem 0;
            padding: 0.5rem;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 0.5rem;
        }

        .requirement-list li:before {
            content: "✓";
            color: #10b981;
            font-weight: bold;
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .guide-panel {
                width: 90%;
                right: -90%;
            }

            .guide-toggle {
                bottom: 1rem;
                right: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold mb-4 gradient-text">Đặt Lịch Khám Bệnh</h1>
                    <p class="text-xl text-gray-600">Điền thông tin để đặt lịch khám với bác sĩ chuyên khoa</p>
                </div>

                <!-- Guide Card -->
                <div class="guide-card">
                    <div class="flex items-center mb-4">
                        <div class="guide-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Hướng dẫn đặt lịch nhanh</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div
                                class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-user"></i>
                            </div>
                            <p class="text-sm text-gray-600">Điền thông tin cá nhân</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <p class="text-sm text-gray-600">Chọn bác sĩ và thời gian</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-check"></i>
                            </div>
                            <p class="text-sm text-gray-600">Xác nhận đặt lịch</p>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold bg-gray-200 text-gray-600 step-circle active"
                                data-step="1">1</div>
                            <div class="w-20 h-1 mx-2 bg-gray-200 progress-line" data-step="1"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold bg-gray-200 text-gray-600 step-circle"
                                data-step="2">2</div>
                            <div class="w-20 h-1 mx-2 bg-gray-200 progress-line" data-step="2"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold bg-gray-200 text-gray-600 step-circle"
                                data-step="3">3</div>
                        </div>
                    </div>
                    <div class="flex justify-center space-x-8 mt-4">
                        <span class="text-sm text-gray-500">Thông tin cá nhân</span>
                        <span class="text-sm text-gray-500">Chọn dịch vụ</span>
                        <span class="text-sm text-gray-500">Xác nhận</span>
                    </div>
                </div>

                <form id="appointmentForm" class="bg-white rounded-xl shadow-lg p-8" method="POST" action="#">
                    @csrf

                    <div class="step step-1 space-y-6 active">
                        <h3 class="text-2xl font-bold mb-6 flex items-center">
                            <span
                                class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 text-sm font-semibold">1</span>
                            Thông Tin Cá Nhân
                        </h3>

                        <div class="tip-box">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-info-circle text-amber-600 mr-2"></i>
                                <strong>Mẹo:</strong>
                            </div>
                            <p class="text-sm">Nhập đầy đủ thông tin để bác sĩ có thể tư vấn chính xác nhất. Các trường có
                                dấu (*) là bắt buộc.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="fullName" id="fullName"
                                    class="w-full p-3 border border-gray-300 rounded-lg form-input"
                                    placeholder="Nhập họ và tên" required value="{{ old('fullName') }}" />
                                <div class="error-message" id="fullName-error">Vui lòng nhập họ và tên</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Số điện thoại <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone"
                                    class="w-full p-3 border border-gray-300 rounded-lg form-input"
                                    placeholder="Nhập số điện thoại" required value="{{ old('phone') }}" />
                                <div class="error-message" id="phone-error">Vui lòng nhập số điện thoại</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email"
                                    class="w-full p-3 border border-gray-300 rounded-lg form-input" placeholder="Nhập email"
                                    value="{{ old('email') }}" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                                <input type="date" name="birthDate" id="birthDate"
                                    class="w-full p-3 border border-gray-300 rounded-lg form-input"
                                    value="{{ old('birthDate') }}" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
                                <select name="gender" id="gender"
                                    class="w-full p-3 border border-gray-300 rounded-lg form-input">
                                    <option value="">Chọn giới tính</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                            <input type="text" name="address" id="address"
                                class="w-full p-3 border border-gray-300 rounded-lg form-input" placeholder="Nhập địa chỉ"
                                value="{{ old('address') }}" />
                        </div>

                        <div class="flex justify-end">
                            <button type="button" id="step1-next"
                                class="gradient-bg text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                                Tiếp theo
                            </button>
                        </div>
                    </div>

                    <div class="step step-2 space-y-6">
                        <h3 class="text-2xl font-bold mb-6 flex items-center">
                            <span
                                class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 text-sm font-semibold">2</span>
                            Chọn Dịch Vụ & Thời Gian
                        </h3>

                        <div class="tip-box">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-clock text-amber-600 mr-2"></i>
                                <strong>Lưu ý:</strong>
                            </div>
                            <p class="text-sm">Vui lòng chọn ngày khám từ hôm nay trở đi. Khung giờ sáng: 8:00-11:30,
                                chiều: 14:00-17:00.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Chuyên khoa <span class="text-red-500">*</span>
                                </label>
                                <select name="specialty" id="specialty"
                                    class="w-full p-3 border border-gray-300 rounded-lg form-input" required>
                                    <option value="">Chọn chuyên khoa</option>
                                    <option value="cardiology" {{ old('specialty') == 'cardiology' ? 'selected' : '' }}>Tim
                                        mạch</option>
                                    <option value="dermatology" {{ old('specialty') == 'dermatology' ? 'selected' : '' }}>
                                        Da liễu</option>
                                    <option value="neurology" {{ old('specialty') == 'neurology' ? 'selected' : '' }}>Thần
                                        kinh</option>
                                    <option value="orthopedics" {{ old('specialty') == 'orthopedics' ? 'selected' : '' }}>
                                        Cơ xương khớp</option>
                                    <option value="pediatrics" {{ old('specialty') == 'pediatrics' ? 'selected' : '' }}>
                                        Nhi
                                        khoa</option>
                                    <option value="gynecology" {{ old('gynecology') == 'gynecology' ? 'selected' : '' }}>
                                        Phụ khoa</option>
                                    <option value="gastroenterology"
                                        {{ old('specialty') == 'gastroenterology' ? 'selected' : '' }}>Tiêu hóa</option>
                                    <option value="ophthalmology"
                                        {{ old('specialty') == 'ophthalmology' ? 'selected' : '' }}>Mắt</option>
                                    <option value="otolaryngology"
                                        {{ old('specialty') == 'otolaryngology' ? 'selected' : '' }}>Tai mũi họng</option>
                                    <option value="urology" {{ old('specialty') == 'urology' ? 'selected' : '' }}>Tiết
                                        niệu</option>
                                </select>
                                <div class="error-message" id="specialty-error">Vui lòng chọn chuyên khoa</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bác sĩ <span class="text-red-500">*</span>
                                </label>
                                <select name="doctor" id="doctor"
                                    class="w-full p-3 border border-gray-300 rounded-lg form-input" required>
                                    <option value="">Chọn bác sĩ</option>
                                </select>
                                <div class="error-message" id="doctor-error">Vui lòng chọn bác sĩ</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ngày khám <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date" id="date"
                                    class="w-full p-3 border border-gray-300 rounded-lg form-input" required
                                    value="{{ old('date') }}" />
                                <div class="error-message" id="date-error">Vui lòng chọn ngày khám</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Giờ khám <span class="text-red-500">*</span>
                                </label>
                                <select name="time" id="time"
                                    class="w-full p-3 border border-gray-300 rounded-lg form-input" required>
                                    <option value="">Chọn giờ khám</option>
                                    <option value="08:00" {{ old('time') == '08:00' ? 'selected' : '' }}>08:00</option>
                                    <option value="08:30" {{ old('time') == '08:30' ? 'selected' : '' }}>08:30</option>
                                    <option value="09:00" {{ old('time') == '09:00' ? 'selected' : '' }}>09:00</option>
                                    <option value="09:30" {{ old('time') == '09:30' ? 'selected' : '' }}>09:30</option>
                                    <option value="10:00" {{ old('time') == '10:00' ? 'selected' : '' }}>10:00</option>
                                    <option value="10:30" {{ old('time') == '10:30' ? 'selected' : '' }}>10:30</option>
                                    <option value="11:00" {{ old('time') == '11:00' ? 'selected' : '' }}>11:00</option>
                                    <option value="11:30" {{ old('time') == '11:30' ? 'selected' : '' }}>11:30</option>
                                    <option value="14:00" {{ old('time') == '14:00' ? 'selected' : '' }}>14:00</option>
                                    <option value="14:30" {{ old('time') == '14:30' ? 'selected' : '' }}>14:30</option>
                                    <option value="15:00" {{ old('time') == '15:00' ? 'selected' : '' }}>15:00</option>
                                    <option value="15:30" {{ old('time') == '15:30' ? 'selected' : '' }}>15:30</option>
                                    <option value="16:00" {{ old('time') == '16:00' ? 'selected' : '' }}>16:00</option>
                                    <option value="16:30" {{ old('time') == '16:30' ? 'selected' : '' }}>16:30</option>
                                    <option value="17:00" {{ old('time') == '17:00' ? 'selected' : '' }}>17:00</option>
                                </select>
                                <div class="error-message" id="time-error">Vui lòng chọn giờ khám</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Triệu chứng / Lý do khám</label>
                            <textarea name="symptoms" id="symptoms" rows="4"
                                class="w-full p-3 border border-gray-300 rounded-lg form-input"
                                placeholder="Mô tả triệu chứng hoặc lý do khám bệnh">{{ old('symptoms') }}</textarea>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" id="step2-prev"
                                class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                                Quay lại
                            </button>
                            <button type="button" id="step2-next"
                                class="gradient-bg text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                                Tiếp theo
                            </button>
                        </div>
                    </div>

                    <div class="step step-3 space-y-6">
                        <h3 class="text-2xl font-bold mb-6 flex items-center">
                            <span
                                class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 text-sm font-semibold">3</span>
                            Xác Nhận Thông Tin
                        </h3>

                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-lg mb-4">Thông tin đặt lịch:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div><strong>Họ tên:</strong> <span id="confirm-name">...</span></div>
                                <div><strong>Điện thoại:</strong> <span id="confirm-phone">...</span></div>
                                <div><strong>Email:</strong> <span id="confirm-email">...</span></div>
                                <div><strong>Ngày sinh:</strong> <span id="confirm-birthdate">...</span></div>
                                <div><strong>Giới tính:</strong> <span id="confirm-gender">...</span></div>
                                <div><strong>Địa chỉ:</strong> <span id="confirm-address">...</span></div>
                                <div><strong>Chuyên khoa:</strong> <span id="confirm-specialty">...</span></div>
                                <div><strong>Bác sĩ:</strong> <span id="confirm-doctor">...</span></div>
                                <div><strong>Ngày khám:</strong> <span id="confirm-date">...</span></div>
                                <div><strong>Giờ khám:</strong> <span id="confirm-time">...</span></div>
                            </div>
                            <div class="mt-4">
                                <strong>Triệu chứng:</strong>
                                <p class="mt-1 text-gray-600" id="confirm-symptoms">...</p>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <p class="text-sm text-yellow-700">
                                <strong>Lưu ý:</strong> Vui lòng đến trước giờ hẹn 15 phút và mang theo các giấy tờ cần
                                thiết.
                                Chúng tôi sẽ liên hệ với bạn để xác nhận lịch hẹn trong vòng 24h.
                            </p>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" id="step3-prev"
                                class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                                Quay lại
                            </button>
                            <button type="submit"
                                class="gradient-bg text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                                Xác Nhận Đặt Lịch
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Guide Toggle Button -->
    <button id="guideToggle" class="guide-toggle" title="Hướng dẫn sử dụng">
        <i class="fas fa-question"></i>
    </button>

    <!-- Guide Overlay -->
    <div id="guideOverlay" class="guide-overlay"></div>

    <!-- Guide Panel -->
    <div id="guidePanel" class="guide-panel">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold gradient-text">Hướng dẫn sử dụng</h2>
            <button id="closeGuide" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="guide-section">
            <h3 class="text-lg font-semibold mb-3 flex items-center">
                <div class="guide-icon mr-3">
                    <i class="fas fa-info-circle"></i>
                </div>
                <span>Hướng dẫn sử dụng</span>
            </h3>
            <p class="text-gray-600 mb-4">Để đặt lịch khám bệnh, bạn cần thực hiện các bước sau:</p>
            <ol class="list-decimal list-inside text-gray-600 mb-4">
                <li>Điền đầy đủ thông tin cá nhân trong bước 1.</li>
                <li>Chọn chuyên khoa, bác sĩ, ngày và giờ khám trong bước 2.</li>
                <li>Xác nhận thông tin trong bước 3.</li>
            </ol>
        </div>

        <div class="guide-section">
            <h3 class="text-lg font-semibold mb-3 flex items-center">
                <div class="guide-icon mr-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <span>Lưu ý khi đặt lịch</span>
            </h3>
            <ul class="requirement-list mb-4">
                <li>Thông tin có dấu <span class="text-red-500">*</span> là bắt buộc.</li>
                <li>Chỉ chọn ngày khám từ hôm nay trở đi.</li>
                <li>Đảm bảo số điện thoại chính xác để nhận xác nhận.</li>
            </ul>
        </div>

        <div class="guide-section">
            <h3 class="text-lg font-semibold mb-3 flex items-center">
                <div class="guide-icon mr-3">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <span>Mẹo sử dụng</span>
            </h3>
            <div class="tip-box">
                <p class="text-gray-700 text-sm">
                    Nếu không thấy bác sĩ mong muốn, hãy thử chọn chuyên khoa khác hoặc đổi ngày khám.
                </p>
            </div>
        </div>
    </div>
    <div id="successModal" class="modal-overlay">
        <div class="modal-content">
            <div class="text-green-500 text-6xl mb-4">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Đặt lịch thành công!</h2>
            <p class="text-gray-600 mb-6">Lịch hẹn của bạn đã được gửi đi. Chúng tôi sẽ liên hệ lại để xác nhận trong vòng
                24 giờ.</p>
            <button id="closeModal"
                class="gradient-bg text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                Đóng
            </button>
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('guideToggle');
            const overlay = document.getElementById('guideOverlay');
            const panel = document.getElementById('guidePanel');
            const closeBtn = document.getElementById('closeGuide');
            let currentStep = 1;
            const totalSteps = 3;

            function openGuide() {
                overlay.style.display = 'block';
                setTimeout(() => {
                    overlay.classList.add('active');
                    panel.classList.add('active');
                }, 10);
            }

            function closeGuide() {
                overlay.classList.remove('active');
                panel.classList.remove('active');
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 300);
            }

            toggleBtn.addEventListener('click', openGuide);
            closeBtn.addEventListener('click', closeGuide);
            overlay.addEventListener('click', closeGuide);

            // Doctors data for different specialties
            const doctors = {
                cardiology: [{
                        value: 'dr-nguyen-van-a',
                        name: 'BS. Nguyễn Văn A'
                    },
                    {
                        value: 'dr-tran-thi-b',
                        name: 'BS. Trần Thị B'
                    }
                ],
                dermatology: [{
                        value: 'dr-le-van-c',
                        name: 'BS. Lê Văn C'
                    },
                    {
                        value: 'dr-pham-thi-d',
                        name: 'BS. Phạm Thị D'
                    }
                ],
                neurology: [{
                        value: 'dr-hoang-van-e',
                        name: 'BS. Hoàng Văn E'
                    },
                    {
                        value: 'dr-vo-thi-f',
                        name: 'BS. Võ Thị F'
                    }
                ],
                orthopedics: [{
                        value: 'dr-dao-van-g',
                        name: 'BS. Đào Văn G'
                    },
                    {
                        value: 'dr-bui-thi-h',
                        name: 'BS. Bùi Thị H'
                    }
                ],
                pediatrics: [{
                        value: 'dr-ngo-van-i',
                        name: 'BS. Ngô Văn I'
                    },
                    {
                        value: 'dr-dang-thi-j',
                        name: 'BS. Đặng Thị J'
                    }
                ],
                gynecology: [{
                        value: 'dr-vu-thi-k',
                        name: 'BS. Vũ Thị K'
                    },
                    {
                        value: 'dr-mai-thi-l',
                        name: 'BS. Mai Thị L'
                    }
                ],
                gastroenterology: [{
                        value: 'dr-ly-van-m',
                        name: 'BS. Lý Văn M'
                    },
                    {
                        value: 'dr-ha-thi-n',
                        name: 'BS. Hà Thị N'
                    }
                ],
                ophthalmology: [{
                        value: 'dr-truong-van-o',
                        name: 'BS. Trương Văn O'
                    },
                    {
                        value: 'dr-quan-thi-p',
                        name: 'BS. Quản Thị P'
                    }
                ],
                otolaryngology: [{
                        value: 'dr-dinh-van-q',
                        name: 'BS. Đinh Văn Q'
                    },
                    {
                        value: 'dr-do-thi-r',
                        name: 'BS. Đỗ Thị R'
                    }
                ],
                urology: [{
                        value: 'dr-ta-van-s',
                        name: 'BS. Tạ Văn S'
                    },
                    {
                        value: 'dr-cao-thi-t',
                        name: 'BS. Cao Thị T'
                    }
                ]
            };

            // Clear validation errors
            const clearErrors = () => {
                document.querySelectorAll('.form-input').forEach(input => {
                    input.classList.remove('error');
                });
                document.querySelectorAll('.error-message').forEach(msg => {
                    msg.classList.remove('show');
                });
            };

            // Show validation error
            const showError = (fieldId, message) => {
                const field = document.getElementById(fieldId);
                const errorDiv = document.getElementById(fieldId + '-error');

                if (field) {
                    field.classList.add('error');
                    field.focus();
                }

                if (errorDiv) {
                    if (message) errorDiv.textContent = message;
                    errorDiv.classList.add('show');
                }
            };

            // Update progress bar
            const updateProgressBar = (step) => {
                document.querySelectorAll('.step-circle').forEach((circle, index) => {
                    const stepNumber = index + 1;
                    circle.classList.remove('active', 'completed');

                    if (stepNumber < step) {
                        circle.classList.add('completed');
                    } else if (stepNumber === step) {
                        circle.classList.add('active');
                    }
                });

                document.querySelectorAll('.progress-line').forEach((line, index) => {
                    const stepNumber = index + 1;
                    line.classList.remove('active', 'completed');

                    if (stepNumber < step) {
                        line.classList.add('completed');
                    } else if (stepNumber === step && stepNumber < totalSteps) {
                        line.classList.add('active');
                    }
                });
            };

            // Show specific step
            const showStep = (step) => {
                // Hide all steps
                document.querySelectorAll('.step').forEach(el => {
                    el.classList.remove('active');
                });

                // Show current step with animation
                setTimeout(() => {
                    const current = document.querySelector(`.step-${step}`);
                    if (current) {
                        current.classList.add('active');
                    }
                }, 100);

                updateProgressBar(step);
                currentStep = step;
            };

            // Validate step 1
            const validateStep1 = () => {
                clearErrors();
                let isValid = true;

                const fullName = document.getElementById('fullName').value.trim();
                const phone = document.getElementById('phone').value.trim();

                if (!fullName) {
                    showError('fullName', 'Vui lòng nhập họ và tên');
                    isValid = false;
                }

                if (!phone) {
                    showError('phone', 'Vui lòng nhập số điện thoại');
                    isValid = false;
                } else if (!/^[0-9]{10,11}$/.test(phone)) {
                    showError('phone', 'Số điện thoại không hợp lệ');
                    isValid = false;
                }

                return isValid;
            };

            // Validate step 2
            const validateStep2 = () => {
                clearErrors();
                let isValid = true;

                const specialty = document.getElementById('specialty').value;
                const doctor = document.getElementById('doctor').value;
                const date = document.getElementById('date').value;
                const time = document.getElementById('time').value;

                if (!specialty) {
                    showError('specialty', 'Vui lòng chọn chuyên khoa');
                    isValid = false;
                }

                if (!doctor) {
                    showError('doctor', 'Vui lòng chọn bác sĩ');
                    isValid = false;
                }

                if (!date) {
                    showError('date', 'Vui lòng chọn ngày khám');
                    isValid = false;
                } else {
                    const selectedDate = new Date(date);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (selectedDate < today) {
                        showError('date', 'Ngày khám phải từ hôm nay trở đi');
                        isValid = false;
                    }
                }

                if (!time) {
                    showError('time', 'Vui lòng chọn giờ khám');
                    isValid = false;
                }

                return isValid;
            };

            // Update confirmation info
            const updateConfirmation = () => {
                const getValue = (id) => document.getElementById(id)?.value || '...';
                const getText = (id) => {
                    const select = document.getElementById(id);
                    return select?.options[select.selectedIndex]?.text || '...';
                };

                document.getElementById('confirm-name').textContent = getValue('fullName');
                document.getElementById('confirm-phone').textContent = getValue('phone');
                document.getElementById('confirm-email').textContent = getValue('email');
                document.getElementById('confirm-birthdate').textContent = getValue('birthDate');
                document.getElementById('confirm-address').textContent = getValue('address');
                document.getElementById('confirm-specialty').textContent = getText('specialty');
                document.getElementById('confirm-doctor').textContent = getText('doctor');
                document.getElementById('confirm-date').textContent = getValue('date');
                document.getElementById('confirm-time').textContent = getValue('time');
                document.getElementById('confirm-symptoms').textContent = getValue('symptoms');

                // Gender
                const genderValue = getValue('gender');
                const genderText = genderValue === 'male' ? 'Nam' :
                    genderValue === 'female' ? 'Nữ' :
                    genderValue === 'other' ? 'Khác' : '...';
                document.getElementById('confirm-gender').textContent = genderText;
            };

            // Handle specialty change
            document.getElementById('specialty').addEventListener('change', function() {
                const specialty = this.value;
                const doctorSelect = document.getElementById('doctor');

                // Clear existing options
                doctorSelect.innerHTML = '<option value="">Chọn bác sĩ</option>';

                if (specialty && doctors[specialty]) {
                    doctors[specialty].forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.value;
                        option.textContent = doctor.name;
                        doctorSelect.appendChild(option);
                    });
                }
            });

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').setAttribute('min', today);

            // Navigation buttons
            document.getElementById('step1-next').addEventListener('click', () => {
                if (validateStep1()) {
                    showStep(2);
                }
            });

            document.getElementById('step2-prev').addEventListener('click', () => {
                showStep(1);
            });

            document.getElementById('step2-next').addEventListener('click', () => {
                if (validateStep2()) {
                    updateConfirmation();
                    showStep(3);
                }
            });

            document.getElementById('step3-prev').addEventListener('click', () => {
                showStep(2);
            });

            // Handle form submission
            document.getElementById('appointmentForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // You can add final validation here if needed
                if (validateStep1() && validateStep2()) {
                    // Simulate form submission success
                    // In a real application, you would send this data to your backend via AJAX
                    // and then show the modal upon successful response.
                    console.log('Form submitted successfully!');
                    console.log('Appointment Data:', {
                        fullName: document.getElementById('fullName').value,
                        phone: document.getElementById('phone').value,
                        email: document.getElementById('email').value,
                        birthDate: document.getElementById('birthDate').value,
                        gender: document.getElementById('gender').value,
                        address: document.getElementById('address').value,
                        specialty: document.getElementById('specialty').value,
                        doctor: document.getElementById('doctor').value,
                        date: document.getElementById('date').value,
                        time: document.getElementById('time').value,
                        symptoms: document.getElementById('symptoms').value,
                    });

                    showSuccessModal();
                    this.reset(); // Optionally reset the form after successful submission
                    showStep(1); // Return to the first step
                }
            });

            // Modal functionality
            const successModal = document.getElementById('successModal');
            const closeModalButton = document.getElementById('closeModal');

            function showSuccessModal() {
                successModal.classList.add('show');
            }

            function hideSuccessModal() {
                successModal.classList.remove('show');
            }

            closeModalButton.addEventListener('click', hideSuccessModal);

            // Close modal when clicking outside (on overlay)
            successModal.addEventListener('click', function(event) {
                if (event.target === successModal) {
                    hideSuccessModal();
                }
            });

            // Close modal when pressing ESC key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && successModal.classList.contains('show')) {
                    hideSuccessModal();
                }
            });
        });
    </script>
@endpush
