@extends('client.layouts.app')

@section('title', 'Xác nhận đặt lịch')

@push('styles')
    <style>
        /* Custom styles for confirmation page */
        .form-input {
            @apply block w-full px-4 py-2 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded-lg transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none;
        }

        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Xác nhận đặt lịch khám bệnh</h1>

        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Thông tin dịch vụ và bác sĩ</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Service Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Dịch vụ đã chọn</h3>
                    <p class="text-gray-600"><strong>Tên dịch vụ:</strong> {{ $service->name }}</p>
                    <p class="text-gray-600"><strong>Giá:</strong> {{ number_format($service->price, 0, ',', '.') }} VNĐ</p>
                    <p class="text-gray-600"><strong>Thời lượng dự kiến:</strong> {{ $service->duration }} phút</p>
                </div>

                <!-- Doctor Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Bác sĩ phụ trách</h3>
                    @if ($doctor)
                        <div class="flex items-center space-x-4">
                            @if ($doctor->user->avatar)
                                <img src="{{ asset('storage/' . $doctor->user->avatar) }}" alt="Avatar bác sĩ"
                                    class="w-16 h-16 rounded-full object-cover shadow-md">
                            @else
                                <div
                                    class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-sm font-bold">
                                    {{ substr($doctor->user->full_name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-gray-800 font-semibold">{{ $doctor->user->full_name }}</p>
                                @if ($doctor->department)
                                    <p class="text-gray-600 text-sm">Chuyên khoa: {{ $doctor->department->name }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600">Bác sĩ sẽ được chỉ định sau (ngẫu nhiên).</p>
                    @endif
                </div>
            </div>

            <div class="mt-6 border-t pt-4">
                <h3 class="text-lg font-medium text-gray-800 mb-2">Thời gian đặt lịch</h3>
                <p class="text-gray-600">
                    <strong>Ngày:</strong> {{ $appointment_time->translatedFormat('l, d F Y') }}
                </p>
                <p class="text-gray-600">
                    <strong>Giờ:</strong> {{ $appointment_time->format('H:i') }}
                </p>
                @if (isset($booking_confirm['reason']) && $booking_confirm['reason'])
                    <p class="text-gray-600"><strong>Lý do khám:</strong> {{ $booking_confirm['reason'] }}</p>
                @endif
            </div>
        </div>

        <!-- Patient Information Form -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Thông tin bệnh nhân</h2>
            <p class="text-gray-600 text-sm mb-4">Vui lòng kiểm tra và cập nhật thông tin cá nhân của bạn. Thông tin này sẽ
                được lưu lại cho các lần đặt lịch sau.</p>

            {{-- Form to finalize booking and update user info --}}
            <form action="{{ route('booking.save') }}" method="POST">
                @csrf
                {{-- Hidden fields for booking data --}}
                <input type="hidden" name="service_id" value="{{ $booking_confirm['service_id'] }}">
                <input type="hidden" name="doctor_id" value="{{ $booking_confirm['doctor_id'] }}">
                <input type="hidden" name="appointment_time" value="{{ $appointment_time->toDateTimeString() }}">
                <input type="hidden" name="reason" value="{{ $booking_confirm['reason'] ?? '' }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="full_name" class="form-label">Họ và tên</label>
                        <input type="text" id="full_name" name="full_name" class="form-input"
                            value="{{ old('full_name', $user->full_name) }}" required>
                        @error('full_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" class="form-input"
                            value="{{ old('phone', $user->phone) }}" required>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="gender" class="form-label">Giới tính</label>
                        <select id="gender" name="gender" class="form-input">
                            <option value="">Chọn giới tính</option>
                            <option value="Nam" {{ old('gender', $user->gender) == 'Nam' ? 'selected' : '' }}>Nam
                            </option>
                            <option value="Nữ" {{ old('gender', $user->gender) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            <option value="Khác" {{ old('gender', $user->gender) == 'Khác' ? 'selected' : '' }}>Khác
                            </option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="date_of_birth" class="form-label">Ngày sinh</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-input"
                            value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">
                        @error('date_of_birth')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <textarea id="address" name="address" rows="3" class="form-input">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between mt-6">
                    <a href="{{ route('booking.showService', $booking_confirm['service_id']) }}"
                        class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                clip-rule="evenodd" />
                        </svg>
                        Quay lại chỉnh sửa
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Xác nhận đặt lịch
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- You can add any specific scripts here if needed for dynamic behavior --}}
@endpush
