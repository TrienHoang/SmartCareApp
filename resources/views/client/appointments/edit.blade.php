@extends('client.layouts.profile-layout')

@section('title', 'Chi tiết khám bệnh')

@section('profile-content')
 <div class="lg:w-3/4">
                    <div class="gradient-bg text-white py-8 mb-8">
                        <div class="max-w-6xl mx-auto px-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-3xl font-bold mb-2">
                                        <i class="fas fa-edit mr-3"></i>Sửa lịch hẹn
                                    </h1>
                                    <p class="text-blue-100">Cập nhật thông tin lịch hẹn của bạn</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-blue-100">Mã lịch hẹn</div>
                                    <div class="text-2xl font-bold">#{{ $appointment->id ?? 'AP001' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="max-w-6xl mx-auto px-6 pb-12">
                        <!-- Current Status Card -->
                        <div class="mb-8">
                            <div class="bg-white rounded-2xl card-shadow p-6 border-l-4 border-blue-500">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div
                                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                            <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800">Thông tin hiện tại</h3>
                                            <p class="text-gray-600">
                                                {{ $appointment->formatted_time ?? 'Thứ 2, 25/12/2023 - 09:00' }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-4 py-2 text-sm font-semibold rounded-full {{ $appointment->statusClass() ?? 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $appointment->status_text ?? 'Chờ xác nhận' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Form sửa lịch hẹn -->
                        <form action="{{ route('client.appointments.update', $appointment->id ?? 1) }}" method="POST"
                            class="space-y-8">
                            @csrf
                            @method('PUT')

                            <!-- Thông tin bệnh nhân -->
                            <div class="form-card bg-white rounded-2xl card-shadow p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-user-injured text-green-600 text-xl"></i>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-800">Thông tin bệnh nhân</h2>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="form-label block text-sm mb-2">
                                            <i class="fas fa-user mr-2 text-gray-400"></i>Họ và tên
                                        </label>
                                        <input type="text" name="patient_name"
                                            value="{{ old('patient_name', $appointment->patient->full_name ?? 'Nguyễn Văn A') }}"
                                            class="form-input w-full px-4 py-3 rounded-xl outline-none"
                                            placeholder="Nhập họ và tên" required>
                                        @error('patient_name')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="form-label block text-sm mb-2">
                                            <i class="fas fa-phone mr-2 text-gray-400"></i>Số điện thoại
                                        </label>
                                        <input type="tel" name="patient_phone"
                                            value="{{ old('patient_phone', $appointment->patient->phone ?? '0123456789') }}"
                                            class="form-input w-full px-4 py-3 rounded-xl outline-none"
                                            placeholder="Nhập số điện thoại" required>
                                        @error('patient_phone')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="form-label block text-sm mb-2">
                                            <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                                        </label>
                                        <input type="email" name="patient_email"
                                            value="{{ old('patient_email', $appointment->patient->email ?? 'nguyenvana@email.com') }}"
                                            class="form-input w-full px-4 py-3 rounded-xl outline-none"
                                            placeholder="Nhập email">
                                        @error('patient_email')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="form-label block text-sm mb-2">
                                            <i class="fas fa-venus-mars mr-2 text-gray-400"></i>Giới tính
                                        </label>
                                        <select name="patient_gender"
                                            class="form-input w-full px-4 py-3 rounded-xl outline-none">
                                            <option value="Nam"
                                                {{ old('patient_gender', $appointment->patient->gender ?? '') == 'Nam' ? 'selected' : '' }}>
                                                Nam</option>
                                            <option value="Nữ"
                                                {{ old('patient_gender', $appointment->patient->gender ?? '') == 'Nữ' ? 'selected' : '' }}>
                                                Nữ</option>
                                            <option value="Khác"
                                                {{ old('patient_gender', $appointment->patient->gender ?? '') == 'Khác' ? 'selected' : '' }}>
                                                Khác</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin lịch hẹn -->
                            <div class="form-card bg-white rounded-2xl card-shadow p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-800">Thông tin lịch hẹn</h2>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="form-label block text-sm mb-2">
                                            <i class="fas fa-calendar mr-2 text-gray-400"></i>Ngày hẹn
                                        </label>
                                        <input type="date" name="appointment_date"
                                            value="{{ old('appointment_date', $appointment->appointment_date ?? date('Y-m-d')) }}"
                                            class="form-input w-full px-4 py-3 rounded-xl outline-none"
                                            min="{{ date('Y-m-d') }}" required>
                                        @error('appointment_date')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="form-label block text-sm mb-2">
                                            <i class="fas fa-clock mr-2 text-gray-400"></i>Giờ hẹn
                                        </label>
                                        <select name="appointment_time"
                                            class="form-input w-full px-4 py-3 rounded-xl outline-none" required>
                                            <option value="">Chọn giờ hẹn</option>
                                            @for ($hour = 8; $hour <= 17; $hour++)
                                                @for ($minute = 0; $minute < 60; $minute += 30)
                                                    @php
                                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                                        $selected =
                                                            old(
                                                                'appointment_time',
                                                                $appointment->appointment_time ?? '',
                                                            ) == $time
                                                                ? 'selected'
                                                                : '';
                                                    @endphp
                                                    <option value="{{ $time }}" {{ $selected }}>
                                                        {{ $time }}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                        @error('appointment_time')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="form-label block text-sm mb-2">
                                            <i class="fas fa-user-md mr-2 text-gray-400"></i>Bác sĩ
                                        </label>
                                        <select name="doctor_id"
                                            class="form-input w-full px-4 py-3 rounded-xl outline-none" required>
                                            <option value="">Chọn bác sĩ</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor['id'] }}"
                                                    {{ old('doctor_id', $appointment->doctor_id ?? '') == $doctor['id'] ? 'selected' : '' }}>
                                                    {{ $doctor->user['full_name'] }}-{{ $doctor['speciality'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('doctor_id')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="form-label block text-sm mb-2">
                                            <i class="fas fa-medical-bag mr-2 text-gray-400"></i>Dịch vụ
                                        </label>
                                        <select name="service_id"
                                            class="form-input w-full px-4 py-3 rounded-xl outline-none" required>
                                            <option value="">Chọn dịch vụ</option>
                                            @foreach ($services ?? [['id' => 1, 'name' => 'Khám tổng quát', 'price' => 500000]] as $service)
                                                <option value="{{ $service['id'] }}"
                                                    {{ old('service_id', $appointment->service_id ?? '') == $service['id'] ? 'selected' : '' }}>
                                                    {{ $service['name'] }} -
                                                    {{ number_format($service['price'], 0, ',', '.') }}₫
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_id')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <label class="form-label block text-sm mb-2">
                                        <i class="fas fa-sticky-note mr-2 text-gray-400"></i>Ghi chú / Lý do khám
                                    </label>
                                    <textarea name="reason" rows="4" class="form-input w-full px-4 py-3 rounded-xl outline-none resize-none"
                                        placeholder="Nhập ghi chú hoặc lý do khám bệnh...">{{ old('reason', $appointment->reason ?? 'Khám tổng quát định kỳ') }}</textarea>
                                    @error('reason')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-4 justify-center pt-6">
                                <a href="{{ route('client.appointments.show', $appointment->id ?? 1) }}"
                                    class="flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                                    <i class="fas fa-times mr-2"></i>
                                    Hủy bỏ
                                </a>

                                <button type="reset"
                                    class="flex items-center px-6 py-3 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                                    <i class="fas fa-undo mr-2"></i>
                                    Đặt lại
                                </button>

                                <button type="submit"
                                    class="btn-gradient flex items-center px-8 py-3 text-white rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                                    <i class="fas fa-save mr-2"></i>
                                    Cập nhật lịch hẹn
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

    @push('styles')
        <style>
            /* General Container */
            .appointment-list-wrapper {
                padding: 0;
                /* Remove padding as outer container handles it */
                background: none;
                /* Remove background as outer container handles it */
            }

            /* Page Header */
            .page-header-content {
                margin-bottom: 2rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid #e5e7eb;
            }

            .page-title-main {
                font-size: 2rem;
                font-weight: 700;
                color: #1f2937;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin: 0;
            }

            /* Table Styling */
            table {
                width: 100%;
                border-spacing: 0;
            }

            th,
            td {
                text-align: left;
                padding: 0.75rem 1rem;
            }

            th {
                background-color: #f3f4f6;
                color: #374151;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
            }

            tr:last-child td {
                border-bottom: none;
            }

            /* Status Badges */
            .status-badge-appointments {
                display: inline-flex;
                align-items: center;
                padding: 0.3em 0.7em;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
                line-height: 1;
            }

            .status-pending {
                background-color: #fef3c7;
                /* yellow-100 */
                color: #d97706;
                /* yellow-700 */
            }

            .status-confirmed {
                background-color: #dbeafe;
                /* blue-100 */
                color: #2563eb;
                /* blue-700 */
            }

            .status-completed {
                background-color: #d1fae5;
                /* green-100 */
                color: #059669;
                /* green-700 */
            }

            .status-canceled {
                background-color: #fee2e2;
                /* red-100 */
                color: #dc2626;
                /* red-700 */
            }

            .status-info {
                background-color: #e0f2fe;
                /* light blue for general info */
                color: #0284c7;
                /* darker blue */
            }

            /* Service Badge */
            .service-badge {
                background-color: #eff6ff;
                /* blue-50 */
                color: #1e40af;
                /* blue-800 */
                padding: 0.25rem 0.6rem;
                border-radius: 6px;
                font-size: 0.75rem;
                font-weight: 500;
            }

            /* Action Buttons */
            .action-btn-view,
            .action-btn-edit,
            .action-btn-cancel {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.5rem;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
                text-decoration: none;
            }

            .action-btn-view {
                background-color: #eff6ff;
                /* blue-50 */
                color: #2563eb;
                /* blue-600 */
            }

            .action-btn-view:hover {
                background-color: #dbeafe;
                /* blue-100 */
            }

            .action-btn-edit {
                background-color: #fffbeb;
                /* yellow-50 */
                color: #d97706;
                /* yellow-600 */
            }

            .action-btn-edit:hover {
                background-color: #fef3c7;
                /* yellow-100 */
            }

            .action-btn-cancel {
                background-color: #fef2f2;
                /* red-50 */
                color: #ef4444;
                /* red-600 */
                border: none;
                /* remove default button border */
                cursor: pointer;
            }

            .action-btn-cancel:hover {
                background-color: #fee2e2;
                /* red-100 */
            }

            /* Empty State */
            .empty-state-appointments {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 4rem 2rem;
                border: 2px dashed #e5e7eb;
                border-radius: 12px;
                margin-top: 2rem;
                color: #6b7280;
            }

            .empty-icon-appointments {
                margin-bottom: 1.5rem;
            }

            .btn-primary-lg {
                display: inline-flex;
                align-items: center;
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .btn-primary-lg:hover {
                background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            }

            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .card-shadow {
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .form-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .form-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            }

            .form-input {
                transition: all 0.3s ease;
                border: 2px solid #e5e7eb;
            }

            .form-input:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            }

            .form-label {
                font-weight: 600;
                color: #374151;
            }

            .btn-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                transition: all 0.3s ease;
            }

            .btn-gradient:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .page-title-main {
                    font-size: 1.75rem;
                    justify-content: center;
                }

                .page-header-content {
                    text-align: center;
                }

                table,
                thead,
                tbody,
                th,
                td,
                tr {
                    display: block;
                }

                thead {
                    display: none;
                }

                tr {
                    margin-bottom: 1rem;
                    border: 1px solid #e5e7eb;
                    border-radius: 0.5rem;
                    overflow: hidden;
                }

                td {
                    border-bottom: 1px solid #e5e7eb;
                    position: relative;
                    padding-left: 50%;
                    text-align: right;
                }

                td:before {
                    content: attr(data-label);
                    position: absolute;
                    left: 0;
                    width: 45%;
                    padding-left: 1rem;
                    font-weight: 600;
                    text-align: left;
                    color: #4b5563;
                }

                td:last-child {
                    border-bottom: none;
                }

                .action-btn-view,
                .action-btn-edit,
                .action-btn-cancel {
                    padding: 0.6rem 1rem;
                    font-size: 0.875rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Khởi tạo lại Lucide icons nếu chúng được thêm động hoặc nếu trang này tải qua AJAX
            // Đảm bảo thư viện Lucide đã được tải trước đó trong client.layouts.app
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        </script>
    @endpush
    <script>
        // Form validation và animations
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = document.querySelectorAll('.form-input');
            const cards = document.querySelectorAll('.form-card');

            // Animation cho cards khi load
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.6s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 200);
            });

            // Real-time validation
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                });
            });

            function validateField(field) {
                const value = field.value.trim();
                const fieldName = field.name;

                // Reset trạng thái
                field.classList.remove('error', 'success');

                if (field.hasAttribute('required') && !value) {
                    showFieldError(field, 'Trường này không được để trống');
                    return false;
                }

                // Validation theo từng loại field
                switch (fieldName) {
                    case 'patient_email':
                        if (value && !isValidEmail(value)) {
                            showFieldError(field, 'Email không đúng định dạng');
                            return false;
                        }
                        break;
                    case 'patient_phone':
                        if (value && !isValidPhone(value)) {
                            showFieldError(field, 'Số điện thoại không đúng định dạng');
                            return false;
                        }
                        break;
                    case 'appointment_date':
                        if (value && new Date(value) < new Date().setHours(0, 0, 0, 0)) {
                            showFieldError(field, 'Ngày hẹn không được trong quá khứ');
                            return false;
                        }
                        break;
                }

                showFieldSuccess(field);
                return true;
            }

            function showFieldError(field, message) {
                field.classList.add('error');
                field.style.borderColor = '#ef4444';

                // Hiển thị thông báo lỗi nếu chưa có
                let errorMsg = field.parentNode.querySelector('.error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('p');
                    errorMsg.className = 'error-message text-red-500 text-sm mt-1';
                    field.parentNode.appendChild(errorMsg);
                }
                errorMsg.textContent = message;
            }

            function showFieldSuccess(field) {
                field.classList.add('success');
                field.style.borderColor = '#10b981';

                // Xóa thông báo lỗi
                const errorMsg = field.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }

            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function isValidPhone(phone) {
                return /^[0-9]{10,11}$/.test(phone.replace(/\s/g, ''));
            }

            // Form submit validation
            form.addEventListener('submit', function(e) {
                let isValid = true;

                inputs.forEach(input => {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    // Scroll to first error
                    const firstError = document.querySelector('.form-input.error');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                }
            });

            // Auto-format phone number
            const phoneInput = document.querySelector('input[name="patient_phone"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, '');
                    if (value.length > 10) value = value.slice(0, 11);
                    this.value = value;
                });
            }
        });
    </script>
@endsection
