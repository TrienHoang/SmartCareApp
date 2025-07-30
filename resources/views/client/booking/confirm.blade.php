@extends('client.layouts.app')

@section('title', 'Xác nhận đặt lịch')

@push('styles')
    <style>
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            /* gray-700 */
            font-weight: 600;
            /* semi-bold */
            /* Add any other styles you want for labels */
        }

        .form-input {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            /* p-3 p-4 equivalent */
            border-width: 1px;
            /* border */
            border-color: #e2e8f0;
            /* border-gray-200 */
            border-radius: 0.5rem;
            /* rounded-lg */
            font-size: 1rem;
            line-height: 1.5;
            /* Add focus styles, etc. */
        }

        .error-message {
            color: #ef4444;
            /* red-500 */
            font-size: 0.875rem;
            /* text-sm */
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            color: #ffffff;
            background-color: #3b82f6;
            /* blue-500 */
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            /* blue-600 */
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            color: #4a5568;
            background-color: #edf2f7;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
            /* gray-300 */
        }

        .floating-element {
            /* Initial state for animation */
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto my-10 m-5 p-4 py-8 max-w-6xl rounded-xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="page-header">
            <div class="flex items-center justify-center mb-4">
                <h1 class="text-4xl m-4 font-bold">Xác nhận đặt lịch khám bệnh</h1>
            </div>
            <p class="text-blue-500 m-3 text-lg">Vui lòng kiểm tra thông tin và xác nhận lịch hẹn của bạn</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="info-card floating-element">
                    <div class="section-header text-2xl font-bold m-2">
                        Dịch vụ đã chọn
                    </div>
                    <div class="space-y-3">
                        <div class="info-item">
                            <span class="info-label">Tên dịch vụ:</span>
                            <span class="info-value font-bold text-blue-700">{{ $service->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Giá dịch vụ:</span>
                            <span
                                class="info-value text-green-600 font-bold">{{ number_format($service->price, 0, ',', '.') }}
                                VNĐ</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Thời lượng:</span>
                            <span class="info-value">{{ $service->duration }} phút</span>
                        </div>
                    </div>
                </div>

                <div class="info-card floating-element">
                    <div class="section-header text-2xl font-bold m-2">
                        Bác sĩ phụ trách
                    </div>
                    @if ($doctor)
                        <div class="doctor-card">
                            <div class="flex items-center space-x-4">
                                @if ($doctor->user->avatar)
                                    <img src="{{ asset('storage/' . $doctor->user->avatar) }}" alt="Avatar bác sĩ"
                                        class="w-20 h-20 rounded-full object-cover shadow-lg border-4 border-white">
                                @else
                                    <div
                                        class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xl font-bold shadow-lg flex-shrink-0">
                                        {{ substr($doctor->user->full_name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $doctor->user->full_name }}</h3>
                                    @if ($doctor->department)
                                        <p class="text-blue-700 font-medium mb-2">{{ $doctor->department->name }}</p>
                                    @endif
                                    <span class="status-badge text-green-600">
                                        Đã được chỉ định
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-amber-600 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-amber-800 font-medium">Bác sĩ sẽ được chỉ định tự động khi xác nhận lịch hẹn
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="info-card">
                    <div class="section-header text-2xl font-bold m-2">
                        Thông tin bệnh nhân
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <p class="text-blue-800 text-sm flex items-center">
                            <svg class="w-4 h-4 inline mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            Vui lòng kiểm tra và cập nhật thông tin cá nhân. Thông tin này sẽ được lưu cho các lần đặt lịch
                            sau.
                        </p>
                    </div>

                    <form action="{{ route('booking.save') }}" method="POST">
                        @csrf
                        {{-- Hidden fields for booking data --}}
                        <input type="hidden" name="service_id" value="{{ $booking_confirm['service_id'] }}">
                        <input type="hidden" name="doctor_id" value="{{ $booking_confirm['doctor_id'] }}">
                        <input type="hidden" name="appointment_time" value="{{ $appointment_time->toDateTimeString() }}">
                        <input type="hidden" name="reason" value="{{ $booking_confirm['reason'] ?? '' }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label for="full_name" class="form-label">
                                    <svg class="w-4 h-4 inline mr-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="full_name" name="full_name" class="form-input"
                                    value="{{ old('full_name', $user->full_name) }}" required
                                    placeholder="Nhập họ và tên đầy đủ">
                                @error('full_name')
                                    <p class="error-message">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="form-label">
                                    <svg class="w-4 h-4 inline mr-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                    Số điện thoại <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" class="form-input"
                                    value="{{ old('phone', $user->phone) }}" required placeholder="Nhập số điện thoại">
                                @error('phone')
                                    <p class="error-message">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="form-label">
                                    <svg class="w-4 h-4 inline mr-2 text-gray-600" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6a2 2 0 114 0v1H8V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Giới tính
                                </label>
                                <select id="gender" name="gender" class="form-input">
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam" {{ old('gender', $user->gender) == 'Nam' ? 'selected' : '' }}>
                                        Nam</option>
                                    <option value="Nữ" {{ old('gender', $user->gender) == 'Nữ' ? 'selected' : '' }}>Nữ
                                    </option>
                                    <option value="Khác" {{ old('gender', $user->gender) == 'Khác' ? 'selected' : '' }}>
                                        Khác</option>
                                </select>
                                @error('gender')
                                    <p class="error-message">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_of_birth" class="form-label">
                                    <svg class="w-4 h-4 inline mr-2 text-gray-600" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Ngày sinh
                                </label>
                                <input type="date" id="date_of_birth" name="date_of_birth" class="form-input"
                                    value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">
                                @error('date_of_birth')
                                    <p class="error-message">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="form-label">
                                    <svg class="w-4 h-4 inline mr-2 text-gray-600" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Địa chỉ
                                </label>
                                <textarea id="address" name="address" rows="3" class="form-input resize-none"
                                    placeholder="Nhập địa chỉ đầy đủ">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="error-message">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-between pt-6 border-t-2 border-gray-100">
                            <a href="{{ route('booking.showService', $booking_confirm['service_id']) }}"
                                class="btn-secondary w-full sm:w-auto">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                        clip-rule="evenodd" />
                                </svg>
                                Quay lại chỉnh sửa
                            </a>
                            <button type="submit" class="btn-primary w-full sm:w-auto">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Xác nhận đặt lịch
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="info-card sticky top-8">
                    <div class="section-header text-2xl font-bold m-2">
                        Thời gian hẹn
                    </div>
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                        <div class="text-center mb-4">
                            <div class="text-3xl font-bold text-blue-700 mb-1">
                                {{ $appointment_time->format('H:i') }}
                            </div>
                            <div class="text-lg font-semibold text-gray-700">
                                {{ $appointment_time->translatedFormat('l') }}
                            </div>
                            <div class="text-gray-600 text-sm">
                                {{ $appointment_time->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        @if (isset($booking_confirm['reason']) && $booking_confirm['reason'])
                            <div class="mt-4 pt-4 border-t border-blue-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Lý do khám:</h4>
                                <p class="text-sm text-gray-700 bg-white rounded-lg p-3 border border-gray-200">
                                    {{ $booking_confirm['reason'] }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Tóm tắt lịch hẹn</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Dịch vụ:</span>
                                <span
                                    class="font-semibold text-gray-800 text-right max-w-[60%] truncate">{{ $service->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Thời lượng:</span>
                                <span class="font-semibold text-gray-800">{{ $service->duration }} phút</span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                <span class="text-gray-800 font-bold text-base">Tổng chi phí:</span>
                                <span
                                    class="font-extrabold text-green-600 text-xl">{{ number_format($service->price, 0, ',', '.') }}
                                    VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add floating animation to cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all floating elements
            document.querySelectorAll('.floating-element').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.6s ease-out';
                observer.observe(el);
            });

            // Form submission loading state
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', function(e) {
                // Optional: Basic client-side validation before showing loading
                // You might already have server-side validation, but this enhances UX
                let isValid = true;
                form.querySelectorAll('[required]').forEach(input => {
                    if (!input.value) {
                        isValid = false;
                        // Add some visual feedback for invalid fields (e.g., red border)
                        input.classList.add('border-red-500', 'ring-red-200');
                    } else {
                        input.classList.remove('border-red-500', 'ring-red-200');
                    }
                });

                if (!isValid) {
                    e.preventDefault(); // Prevent form submission if not valid
                    // Scroll to the first invalid element if needed
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                    return;
                }

                submitBtn.innerHTML = `
                    <svg class="animate-spin w-5 h-5 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Đang xử lý...
                `;
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            });
        });
    </script>
@endpush
