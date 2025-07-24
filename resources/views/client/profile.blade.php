{{-- resources/views/client/profile.blade.php --}}
@extends('client.layouts.app')

@section('title', 'Thông Tin Cá Nhân')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                {{-- Sidebar --}}
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                        {{-- Profile Avatar --}}
                        <div class="text-center mb-8">
                            <div class="relative inline-block">
                                <img id="profile-avatar" src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}"
                                    alt="Avatar"
                                    class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-blue-100 object-cover">
                                <button onclick="openAvatarModal()"
                                    class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 transition-colors shadow-lg">
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ auth()->user()->name ?? 'Người dùng' }}</h3>
                            <p class="text-gray-600">{{ auth()->user()->email ?? 'email@example.com' }}</p>
                        </div>

                        {{-- Menu --}}
                        <nav class="space-y-2">
                            <a href="#thong-tin-ca-nhan"
                                class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600 border-l-4 border-blue-600">
                                <i data-lucide="user" class="w-5 h-5"></i>
                                <span class="font-semibold">Thông Tin Cá Nhân</span>
                            </a>
                            <a href="#lich-su-kham"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                                <span>Lịch Sử Khám</span>
                            </a>
                            <a href="#lich-hen"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                                <span>Lịch Hẹn</span>
                            </a>
                            <a href="#ho-so-y-te"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <span>Hồ Sơ Y Tế</span>
                            </a>
                            <a href="{{ route('client.uploads.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="upload" class="w-5 h-5"></i>
                                <span>Upload File</span>
                            </a>
                            <a href="#thong-bao"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                <span>Thông Báo</span>
                            </a>
                            <a href="#cai-dat"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="settings" class="w-5 h-5"></i>
                                <span>Cài Đặt</span>
                            </a>
                        </nav>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="lg:w-3/4">
                    {{-- Header --}}
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h1 class="text-3xl font-bold gradient-text">Thông Tin Cá Nhân</h1>
                            <button onclick="openEditModal()"
                                class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors flex items-center space-x-2 shadow-lg hover:shadow-xl">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                                <span>Chỉnh Sửa</span>
                            </button>
                        </div>

                        {{-- Personal Information --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Họ và Tên</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->name ?? 'Nguyễn Văn A' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->email ?? 'nguyenvana@email.com' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Số Điện Thoại</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->phone ?? '0123456789' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày Sinh</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->birth_date ?? '01/01/1990' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Giới Tính</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->gender ?? 'Nam' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">CCCD/CMND</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->id_card ?? '123456789012' }}</p>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Địa Chỉ</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">
                                        {{ auth()->user()->address ?? 'Số 123, Phố ABC, Quận XYZ, Hà Nội' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Medical Information --}}
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold gradient-text">Thông Tin Y Tế</h2>
                            <button onclick="openMedicalModal()"
                                class="bg-green-600 text-white px-4 py-2 rounded-full hover:bg-green-700 transition-colors flex items-center space-x-2 text-sm">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                                <span>Chỉnh Sửa</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nhóm Máu</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->blood_type ?? 'A+' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Chiều Cao (cm)</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->height ?? '170' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Cân Nặng (kg)</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->weight ?? '65' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Người Liên Hệ Khẩn Cấp</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">
                                        {{ auth()->user()->emergency_contact ?? 'Nguyễn Thị B - 0987654321' }}</p>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tiền Sử Bệnh Án</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">
                                        {{ auth()->user()->medical_history ?? 'Không có tiền sử bệnh lý đặc biệt' }}</p>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Dị Ứng</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ auth()->user()->allergies ?? 'Không có dị ứng đã biết' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Appointments --}}
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold mb-6 gradient-text">Lịch Hẹn Gần Đây</h2>
                        <div class="space-y-4">
                            @php
                                $appointments = [
                                    [
                                        'date' => '2024-01-15',
                                        'time' => '14:30',
                                        'doctor' => 'BS. Nguyễn Văn An',
                                        'specialty' => 'Nội Khoa',
                                        'status' => 'Hoàn thành',
                                        'status_color' => 'bg-green-100 text-green-800',
                                    ],
                                    [
                                        'date' => '2024-01-20',
                                        'time' => '09:00',
                                        'doctor' => 'BS. Trần Thị Bình',
                                        'specialty' => 'Sản Phụ Khoa',
                                        'status' => 'Đã đặt',
                                        'status_color' => 'bg-blue-100 text-blue-800',
                                    ],
                                    [
                                        'date' => '2024-01-25',
                                        'time' => '16:00',
                                        'doctor' => 'BS. Lê Văn Cường',
                                        'specialty' => 'Ngoại Khoa',
                                        'status' => 'Đã đặt',
                                        'status_color' => 'bg-blue-100 text-blue-800',
                                    ],
                                ];
                            @endphp
                            @foreach ($appointments as $appointment)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i data-lucide="calendar" class="w-6 h-6 text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $appointment['doctor'] }}</h3>
                                            <p class="text-sm text-gray-600">{{ $appointment['specialty'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $appointment['date'] }} -
                                                {{ $appointment['time'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-semibold {{ $appointment['status_color'] }}">
                                            {{ $appointment['status'] }}
                                        </span>
                                        <button class="text-blue-600 hover:text-blue-800">
                                            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 text-center">
                            <a href="{{ url('/lich-hen') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Xem Tất Cả Lịch Hẹn →
                            </a>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold mb-6 gradient-text">Thao Tác Nhanh</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <a href="{{ url('/dat-lich') }}"
                                class="p-6 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all hover-scale">
                                <div class="flex items-center space-x-3">
                                    <i data-lucide="calendar-plus" class="w-8 h-8 text-blue-600"></i>
                                    <div>
                                        <h3 class="font-semibold text-blue-900">Đặt Lịch Khám</h3>
                                        <p class="text-sm text-blue-700">Đặt lịch hẹn mới</p>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('/lich-su-kham') }}"
                                class="p-6 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all hover-scale">
                                <div class="flex items-center space-x-3">
                                    <i data-lucide="file-text" class="w-8 h-8 text-green-600"></i>
                                    <div>
                                        <h3 class="font-semibold text-green-900">Xem Hồ Sơ</h3>
                                        <p class="text-sm text-green-700">Lịch sử khám bệnh</p>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('/lien-he') }}"
                                class="p-6 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all hover-scale">
                                <div class="flex items-center space-x-3">
                                    <i data-lucide="phone" class="w-8 h-8 text-purple-600"></i>
                                    <div>
                                        <h3 class="font-semibold text-purple-900">Liên Hệ</h3>
                                        <p class="text-sm text-purple-700">Hỗ trợ khách hàng</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile Modal --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-6 border-b border-gray-200 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold gradient-text">Chỉnh Sửa Thông Tin Cá Nhân</h2>
                    <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <form id="editProfileForm" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Họ và Tên *</label>
                        <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Số Điện Thoại *</label>
                        <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày Sinh</label>
                        <input type="date" name="birth_date" value="{{ auth()->user()->birth_date ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Giới Tính</label>
                        <select name="gender"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="Nam" {{ (auth()->user()->gender ?? '') == 'Nam' ? 'selected' : '' }}>Nam
                            </option>
                            <option value="Nữ" {{ (auth()->user()->gender ?? '') == 'Nữ' ? 'selected' : '' }}>Nữ
                            </option>
                            <option value="Khác" {{ (auth()->user()->gender ?? '') == 'Khác' ? 'selected' : '' }}>Khác
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">CCCD/CMND</label>
                        <input type="text" name="id_card" value="{{ auth()->user()->id_card ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Địa Chỉ</label>
                        <textarea name="address" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">{{ auth()->user()->address ?? '' }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Lưu Thay Đổi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Medical Info Modal --}}
    <div id="medicalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-6 border-b border-gray-200 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold gradient-text">Chỉnh Sửa Thông Tin Y Tế</h2>
                    <button onclick="closeMedicalModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <form id="editMedicalForm" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nhóm Máu</label>
                        <select name="blood_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                            <option value="">Chọn nhóm máu</option>
                            <option value="A+" {{ (auth()->user()->blood_type ?? '') == 'A+' ? 'selected' : '' }}>A+
                            </option>
                            <option value="A-" {{ (auth()->user()->blood_type ?? '') == 'A-' ? 'selected' : '' }}>A-
                            </option>
                            <option value="B+" {{ (auth()->user()->blood_type ?? '') == 'B+' ? 'selected' : '' }}>B+
                            </option>
                            <option value="B-" {{ (auth()->user()->blood_type ?? '') == 'B-' ? 'selected' : '' }}>B-
                            </option>
                            <option value="AB+" {{ (auth()->user()->blood_type ?? '') == 'AB+' ? 'selected' : '' }}>AB+
                            </option>
                            <option value="AB-" {{ (auth()->user()->blood_type ?? '') == 'AB-' ? 'selected' : '' }}>AB-
                            </option>
                            <option value="O+" {{ (auth()->user()->blood_type ?? '') == 'O+' ? 'selected' : '' }}>O+
                            </option>
                            <option value="O-" {{ (auth()->user()->blood_type ?? '') == 'O-' ? 'selected' : '' }}>O-
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Chiều Cao (cm)</label>
                        <input type="number" name="height" value="{{ auth()->user()->height ?? '' }}" min="50"
                            max="250"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cân Nặng (kg)</label>
                        <input type="number" name="weight" value="{{ auth()->user()->weight ?? '' }}" min="10"
                            max="500"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Người Liên Hệ Khẩn Cấp</label>
                        <input type="text" name="emergency_contact"
                            value="{{ auth()->user()->emergency_contact ?? '' }}" placeholder="Tên - Số điện thoại"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tiền Sử Bệnh Án</label>
                        <textarea name="medical_history" rows="4" placeholder="Mô tả tiền sử bệnh lý (nếu có)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">{{ auth()->user()->medical_history ?? '' }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dị Ứng</label>
                        <textarea name="allergies" rows="3" placeholder="Mô tả các loại dị ứng (nếu có)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">{{ auth()->user()->allergies ?? '' }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeMedicalModal()"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Lưu Thay Đổi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Avatar Upload Modal --}}
    <div id="avatarModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold gradient-text">Cập Nhật Avatar</h2>
                    <button onclick="closeAvatarModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <form id="avatarForm" class="p-6">
                @csrf
                <div class="text-center mb-6">
                    <div class="relative inline-block">
                        <img id="avatar-preview" src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}"
                            alt="Avatar Preview"
                            class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-blue-100 object-cover">
                        <div
                            class="absolute inset-0 bg-black bg-opacity-50 rounded-full opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <i data-lucide="camera" class="w-8 h-8 text-white"></i>
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Chọn ảnh mới</label>
                    <input type="file" id="avatar-input" name="avatar" accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <p class="text-sm text-gray-500 mt-2">Chấp nhận: JPG, PNG, GIF (tối đa 5MB)</p>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeAvatarModal()"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <i data-lucide="upload" class="w-4 h-4"></i>
                        <span>Cập Nhật</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-700 font-semibold">Đang xử lý...</p>
        </div>
    </div>

    {{-- Success Toast --}}
    <div id="successToast"
        class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg hidden z-50 transform translate-x-full transition-transform">
        <div class="flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span id="successMessage">Cập nhật thành công!</span>
        </div>
    </div>

    {{-- Error Toast --}}
    <div id="errorToast"
        class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg hidden z-50 transform translate-x-full transition-transform">
        <div class="flex items-center space-x-2">
            <i data-lucide="x-circle" class="w-5 h-5"></i>
            <span id="errorMessage">Có lỗi xảy ra!</span>
        </div>
    </div>
    @push('scripts')
            <script>
        // Smooth scrolling for sidebar links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Active menu item highlighting
        const menuItems = document.querySelectorAll('nav a');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                menuItems.forEach(mi => {
                    mi.classList.remove('bg-blue-50', 'text-blue-600', 'border-l-4',
                        'border-blue-600');
                    mi.classList.add('text-gray-700');
                });

                // Add active class to clicked item
                this.classList.remove('text-gray-700');
                this.classList.add('bg-blue-50', 'text-blue-600', 'border-l-4', 'border-blue-600');
            });
        });

        // Modal functions
        function openEditModal() {
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openMedicalModal() {
            document.getElementById('medicalModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMedicalModal() {
            document.getElementById('medicalModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openAvatarModal() {
            document.getElementById('avatarModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAvatarModal() {
            document.getElementById('avatarModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Toast functions
        function showToast(type, message) {
            const toast = document.getElementById(type + 'Toast');
            const messageElement = document.getElementById(type + 'Message');

            messageElement.textContent = message;
            toast.classList.remove('hidden');

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 300);
            }, 3000);
        }

        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        // Form validation
        function validateForm(formId) {
            const form = document.getElementById(formId);
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            return isValid;
        }

        // Handle profile form submission
        document.getElementById('editProfileForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!validateForm('editProfileForm')) {
                showToast('error', 'Vui lòng điền đầy đủ thông tin bắt buộc!');
                return;
            }

            showLoading();

            try {
                const formData = new FormData(this);

                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1500));

                // Here you would make the actual API call
                // const response = await fetch('/api/profile/update', {
                //     method: 'POST',
                //     body: formData
                // });

                // if (!response.ok) throw new Error('Update failed');

                // Update UI with new data
                updateProfileUI(formData);

                hideLoading();
                closeEditModal();
                showToast('success', 'Cập nhật thông tin cá nhân thành công!');

            } catch (error) {
                hideLoading();
                showToast('error', 'Có lỗi xảy ra khi cập nhật thông tin!');
            }
        });

        // Handle medical form submission
        document.getElementById('editMedicalForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            showLoading();

            try {
                const formData = new FormData(this);

                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1500));

                // Here you would make the actual API call
                // const response = await fetch('/api/profile/medical-update', {
                //     method: 'POST',
                //     body: formData
                // });

                // if (!response.ok) throw new Error('Update failed');

                hideLoading();
                closeMedicalModal();
                showToast('success', 'Cập nhật thông tin y tế thành công!');

            } catch (error) {
                hideLoading();
                showToast('error', 'Có lỗi xảy ra khi cập nhật thông tin y tế!');
            }
        });

        // Handle avatar form submission
        document.getElementById('avatarForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('avatar-input');
            if (!fileInput.files[0]) {
                showToast('error', 'Vui lòng chọn ảnh để upload!');
                return;
            }

            // Validate file size (5MB max)
            if (fileInput.files[0].size > 5 * 1024 * 1024) {
                showToast('error', 'Kích thước ảnh không được vượt quá 5MB!');
                return;
            }

            showLoading();

            try {
                const formData = new FormData(this);

                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 2000));

                // Here you would make the actual API call
                // const response = await fetch('/api/profile/avatar-update', {
                //     method: 'POST',
                //     body: formData
                // });

                // if (!response.ok) throw new Error('Upload failed');

                // Update avatar in UI
                const newAvatarUrl = URL.createObjectURL(fileInput.files[0]);
                document.getElementById('profile-avatar').src = newAvatarUrl;

                hideLoading();
                closeAvatarModal();
                showToast('success', 'Cập nhật avatar thành công!');

            } catch (error) {
                hideLoading();
                showToast('error', 'Có lỗi xảy ra khi cập nhật avatar!');
            }
        });

        // Avatar preview
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Update profile UI after successful form submission
        function updateProfileUI(formData) {
            const updates = {
                name: formData.get('name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                birth_date: formData.get('birth_date'),
                gender: formData.get('gender'),
                id_card: formData.get('id_card'),
                address: formData.get('address')
            };

            // Update display values (this would normally be handled by a page reload or AJAX update)
            for (const [key, value] of Object.entries(updates)) {
                if (value) {
                    // Update form values for next edit
                    const formField = document.querySelector(`[name="${key}"]`);
                    if (formField) {
                        formField.value = value;
                    }
                }
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
                closeEditModal();
                closeMedicalModal();
                closeAvatarModal();
            }
        });

        // Close modals with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
                closeMedicalModal();
                closeAvatarModal();
            }
        });

        // Phone number formatting
        document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            e.target.value = value;
        });

        // ID card formatting
        document.querySelector('input[name="id_card"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 12) {
                value = value.slice(0, 12);
            }
            e.target.value = value;
        });

        // Auto-resize textareas
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    </script>
    @endpush
@endsection

