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
                                <img id="profile-avatar"
                                    src="{{ optional($user)->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                    alt="Avatar"
                                    class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-blue-100 object-cover">
                                <button onclick="openAvatarModal()"
                                    class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 transition-colors shadow-lg">
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ $user->name ?? 'Người dùng' }}</h3>
                            <p class="text-gray-600">{{ $user->email ?? 'email@example.com' }}</p>
                        </div>

                        {{-- Menu --}}
                        <nav class="space-y-2">
                            <a href="#thong-tin-ca-nhan"
                                class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600 border-l-4 border-blue-600">
                                <i data-lucide="user" class="w-5 h-5"></i>
                                <span class="font-semibold">Thông Tin Cá Nhân</span>
                            </a>
                            <a href="{{ route('client.appointments.history') }}"
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
                            <a href="{{ route('client.payment_history.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="credit-card" class="w-5 h-5"></i>
                                <span>Lịch Sử Thanh Toán</span>
                            </a>
                            <a href="#"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="message-square" class="w-5 h-5"></i>
                                <span>Bình luận của tôi</span>
                            </a>
                        </nav>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="lg:w-3/4">
                    {{-- Personal Information --}}
                    <div id="thong-tin-ca-nhan" class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h1 class="text-3xl font-bold text-gray-800">Thông Tin Cá Nhân</h1>
                            <button onclick="openEditModal()"
                                class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors flex items-center space-x-2 shadow-lg hover:shadow-xl">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                                <span>Chỉnh Sửa</span>
                            </button>
                        </div>

                        {{-- Personal Information Form --}}
                        <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Full Name --}}
                                <div>
                                    <label class="block font-medium text-gray-700">Họ tên</label>
                                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                                        class="form-input mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('full_name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-input mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('email')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label class="block font-medium text-gray-700">Số điện thoại</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="form-input mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('phone')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Gender --}}
                                <div>
                                    <label class="block font-medium text-gray-700">Giới tính</label>
                                    <select name="gender"
                                        class="form-select mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="male"
                                            {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam
                                        </option>
                                        <option value="female"
                                            {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ
                                        </option>
                                        <option value="other"
                                            {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>
                                            Khác</option>
                                    </select>
                                    @error('gender')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Date of Birth --}}
                                <div>
                                    <label class="block font-medium text-gray-700">Ngày sinh</label>
                                    <input type="date" name="date_of_birth"
                                        value="{{ old('date_of_birth', $user->date_of_birth) }}"
                                        class="form-input mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('date_of_birth')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div>
                                    <label class="block font-medium text-gray-700">Địa chỉ</label>
                                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                        class="form-input mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('address')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl">
                                    Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Grid Layout for Medical Info and Recent Appointments --}}
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
                        {{-- Medical Information --}}
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-bold gradient-text">Thông Tin Y Tế</h2>
                                <button onclick="openMedicalModal()"
                                    class="bg-green-600 text-white px-4 py-2 rounded-full hover:bg-green-700 transition-colors flex items-center space-x-2 text-sm">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                    <span>Chỉnh Sửa</span>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nhóm Máu</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900">{{ $user->blood_type ?? 'A+' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Chiều Cao (cm)</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900">{{ $user->height ?? '170' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cân Nặng (kg)</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900">{{ $user->weight ?? '65' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Liên Hệ Khẩn Cấp</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 text-sm">
                                            {{ $user->emergency_contact ?? 'Nguyễn Thị B - 0987654321' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tiền Sử Bệnh Án</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 text-sm">
                                            {{ $user->medical_history ?? 'Không có tiền sử bệnh lý đặc biệt' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dị Ứng</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 text-sm">
                                            {{ $user->allergies ?? 'Không có dị ứng đã biết' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Recent Appointments --}}
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-xl font-bold mb-6 gradient-text">Lịch Hẹn Gần Đây</h2>
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
                                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i data-lucide="calendar" class="w-4 h-4 text-blue-600"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 text-sm">
                                                    {{ $appointment['doctor'] }}</h3>
                                                <p class="text-xs text-gray-600">{{ $appointment['specialty'] }}</p>
                                                <p class="text-xs text-gray-500">{{ $appointment['date'] }} -
                                                    {{ $appointment['time'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-semibold {{ $appointment['status_color'] }}">
                                                {{ $appointment['status'] }}
                                            </span>
                                            <button class="text-blue-600 hover:text-blue-800">
                                                <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ url('/lich-hen') }}"
                                    class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    Xem Tất Cả Lịch Hẹn →
                                </a>
                            </div>
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
                        <input type="text" name="name" value="{{ $user->name ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ $user->email ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Số Điện Thoại *</label>
                        <input type="tel" name="phone" value="{{ $user->phone ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày Sinh</label>
                        <input type="date" name="birth_date" value="{{ $user->birth_date ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Giới Tính</label>
                        <select name="gender"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="Nam" {{ ($user->gender ?? '') == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ ($user->gender ?? '') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            <option value="Khác" {{ ($user->gender ?? '') == 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">CCCD/CMND</label>
                        <input type="text" name="id_card" value="{{ $user->id_card ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Địa Chỉ</label>
                        <textarea name="address" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">{{ $user->address ?? '' }}</textarea>
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
                            <option value="A+" {{ ($user->blood_type ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ ($user->blood_type ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ ($user->blood_type ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ ($user->blood_type ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="O+" {{ ($user->blood_type ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ ($user->blood_type ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                            <option value="AB+" {{ ($user->blood_type ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ ($user->blood_type ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Chiều Cao (cm)</label>
                        <input type="number" name="height" value="{{ $user->height ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cân Nặng (kg)</label>
                        <input type="number" name="weight" value="{{ $user->weight ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Liên Hệ Khẩn Cấp</label>
                        <input type="text" name="emergency_contact" value="{{ $user->emergency_contact ?? '' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tiền Sử Bệnh Án</label>
                        <textarea name="medical_history" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">{{ $user->medical_history ?? '' }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dị Ứng</label>
                        <textarea name="allergies" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">{{ $user->allergies ?? '' }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeMedicalModal()"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2">
                        <i data-luc="save" class="w-4 h-4"></i>
                        <span>Lưu</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openEditModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openMedicalModal() {
            document.getElementById('medicalModal').classList.remove('hidden');
        }

        function closeMedicalModal() {
            document.getElementById('medicalModal').classList.add('hidden');
        }
        document.getElementById('editProfileForm').addEventListener('submit', function(event) {
            event.preventDefault();
            // Add your AJAX form submission logic here
            closeEditModal();
        });
        document.getElementById('editMedicalForm').addEventListener('submit', function(event) {
            event.preventDefault();
            // Add your AJAX form submission logic here
            closeMedicalModal();
        });
    </script>
@endsection
