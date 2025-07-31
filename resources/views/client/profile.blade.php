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
                            <a href="{{ route('client.profile.show') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600 border-l-4 border-blue-600">
                                <i data-lucide="user" class="w-5 h-5"></i>
                                <span class="font-semibold">Thông Tin Cá Nhân</span>
                            </a>
                            <a href="{{ route('client.appointments.history') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                                <span>Lịch Sử Khám</span>
                            </a>
                            <a href="{{ route('client.prescriptions.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                <span>Đơn Thuốc</span>
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
                            <a href="{{ route('client.notifications.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                <span>Thông Báo</span>
                                @php
                                    $currentUnreadCount = $notifications
                                        ->where('userStatuses.0.is_read', false)
                                        ->count();
                                @endphp
                                @if ($currentUnreadCount > 0)
                                    <span id="unreadCount"
                                        class="ml-2 px-2 py-0.5 bg-red-500 text-white rounded-full text-xs font-semibold">
                                        {{ $currentUnreadCount }}
                                    </span>
                                @endif
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
                <div class="lg:w-3/4 space-y-8">
                    {{-- Personal Information --}}
                    <div id="thong-tin-ca-nhan" class="bg-white rounded-xl shadow-lg p-8">
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                {{-- Full Name --}}
                                <div>
                                    <label class="block font-medium text-gray-700 mb-2">Họ tên</label>
                                    <div class="p-3 bg-gray-50 rounded-lg border">
                                        <p class="text-gray-900">{{ $user->full_name ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block font-medium text-gray-700 mb-2">Email</label>
                                    <div class="p-3 bg-gray-50 rounded-lg border">
                                        <p class="text-gray-900">{{ $user->email ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label class="block font-medium text-gray-700 mb-2">Số điện thoại</label>
                                    <div class="p-3 bg-gray-50 rounded-lg border">
                                        <p class="text-gray-900">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                </div>

                                {{-- Gender --}}
                                <div>
                                    <label class="block font-medium text-gray-700 mb-2">Giới tính</label>
                                    <div class="p-3 bg-gray-50 rounded-lg border">
                                        <p class="text-gray-900">{{ $user->gender ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                </div>

                                {{-- Date of Birth --}}
                                <div>
                                    <label class="block font-medium text-gray-700 mb-2">Ngày sinh</label>
                                    <div class="p-3 bg-gray-50 rounded-lg border">
                                        <p class="text-gray-900">
                                            {{ optional($user->date_of_birth)->format('d/m/Y') ?? 'Chưa cập nhật' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div>
                                    <label class="block font-medium text-gray-700 mb-2">Địa chỉ</label>
                                    <div class="p-3 bg-gray-50 rounded-lg border">
                                        <p class="text-gray-900">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Quick Actions - Moved to bottom with improved design --}}
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                                    <h2 class="text-2xl font-bold text-white flex items-center">
                                        <i data-lucide="zap" class="w-6 h-6 mr-2"></i>
                                        Thao Tác Nhanh
                                    </h2>
                                    <p class="text-blue-100 mt-1">Truy cập nhanh các chức năng chính</p>
                                </div>

                                <div class="p-8">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        {{-- Đặt Lịch Khám --}}
                                        <a href="{{ url('/dat-lich') }}"
                                            class="group relative bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200 hover:from-blue-100 hover:to-blue-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                            <div class="flex items-start justify-between mb-4">
                                                <div
                                                    class="p-3 bg-blue-600 rounded-lg group-hover:bg-blue-700 transition-colors">
                                                    <i data-lucide="calendar-plus" class="w-6 h-6 text-white"></i>
                                                </div>
                                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i data-lucide="arrow-right" class="w-5 h-5 text-blue-600"></i>
                                                </div>
                                            </div>
                                            <h3 class="text-lg font-bold text-blue-900 mb-2">Đặt Lịch Khám</h3>
                                            <p class="text-blue-700 text-sm">Đặt lịch hẹn khám bệnh với bác sĩ chuyên khoa
                                            </p>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 opacity-0 group-hover:opacity-5 rounded-xl transition-opacity">
                                            </div>
                                        </a>

                                        {{-- Xem Hồ Sơ --}}
                                        <a href="{{ route('client.appointments.history') }}"
                                            class="group relative bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200 hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                            <div class="flex items-start justify-between mb-4">
                                                <div
                                                    class="p-3 bg-green-600 rounded-lg group-hover:bg-green-700 transition-colors">
                                                    <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
                                                </div>
                                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i data-lucide="arrow-right" class="w-5 h-5 text-green-600"></i>
                                                </div>
                                            </div>
                                            <h3 class="text-lg font-bold text-green-900 mb-2">Xem Hồ Sơ</h3>
                                            <p class="text-green-700 text-sm">Xem lịch sử khám bệnh và các kết quả điều trị
                                            </p>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-green-600 to-green-700 opacity-0 group-hover:opacity-5 rounded-xl transition-opacity">
                                            </div>
                                        </a>

                                        {{-- Đơn Thuốc --}}
                                        <a href="{{ route('client.prescriptions.index') }}"
                                            class="group relative bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200 hover:from-purple-100 hover:to-purple-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                            <div class="flex items-start justify-between mb-4">
                                                <div
                                                    class="p-3 bg-purple-600 rounded-lg group-hover:bg-purple-700 transition-colors">
                                                    <i data-lucide="clipboard-list" class="w-6 h-6 text-white"></i>
                                                </div>
                                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i data-lucide="arrow-right" class="w-5 h-5 text-purple-600"></i>
                                                </div>
                                            </div>
                                            <h3 class="text-lg font-bold text-purple-900 mb-2">Đơn Thuốc</h3>
                                            <p class="text-purple-700 text-sm">Xem và quản lý các đơn thuốc được kê</p>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-purple-600 to-purple-700 opacity-0 group-hover:opacity-5 rounded-xl transition-opacity">
                                            </div>
                                        </a>

                                        {{-- Thanh Toán --}}
                                        <a href="{{ route('client.payment_history.index') }}"
                                            class="group relative bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl border border-orange-200 hover:from-orange-100 hover:to-orange-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                            <div class="flex items-start justify-between mb-4">
                                                <div
                                                    class="p-3 bg-orange-600 rounded-lg group-hover:bg-orange-700 transition-colors">
                                                    <i data-lucide="credit-card" class="w-6 h-6 text-white"></i>
                                                </div>
                                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i data-lucide="arrow-right" class="w-5 h-5 text-orange-600"></i>
                                                </div>
                                            </div>
                                            <h3 class="text-lg font-bold text-orange-900 mb-2">Thanh Toán</h3>
                                            <p class="text-orange-700 text-sm">Xem lịch sử thanh toán và hóa đơn</p>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-700 opacity-0 group-hover:opacity-5 rounded-xl transition-opacity">
                                            </div>
                                        </a>

                                        {{-- Thông Báo --}}
                                        <a href="{{ route('client.notifications.index') }}"
                                            class="group relative bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl border border-red-200 hover:from-red-100 hover:to-red-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                            <div class="flex items-start justify-between mb-4">
                                                <div
                                                    class="p-3 bg-red-600 rounded-lg group-hover:bg-red-700 transition-colors relative">
                                                    <i data-lucide="bell" class="w-6 h-6 text-white"></i>
                                                    @if ($currentUnreadCount > 0)
                                                        <span
                                                            class="absolute -top-1 -right-1 bg-yellow-400 text-red-800 text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                                            {{ $currentUnreadCount > 9 ? '9+' : $currentUnreadCount }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i data-lucide="arrow-right" class="w-5 h-5 text-red-600"></i>
                                                </div>
                                            </div>
                                            <h3 class="text-lg font-bold text-red-900 mb-2">Thông Báo</h3>
                                            <p class="text-red-700 text-sm">Xem các thông báo và cập nhật mới nhất</p>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-700 opacity-0 group-hover:opacity-5 rounded-xl transition-opacity">
                                            </div>
                                        </a>

                                        {{-- Liên Hệ --}}
                                        <a href="{{ url('/lien-he') }}"
                                            class="group relative bg-gradient-to-br from-teal-50 to-teal-100 p-6 rounded-xl border border-teal-200 hover:from-teal-100 hover:to-teal-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                            <div class="flex items-start justify-between mb-4">
                                                <div
                                                    class="p-3 bg-teal-600 rounded-lg group-hover:bg-teal-700 transition-colors">
                                                    <i data-lucide="phone" class="w-6 h-6 text-white"></i>
                                                </div>
                                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i data-lucide="arrow-right" class="w-5 h-5 text-teal-600"></i>
                                                </div>
                                            </div>
                                            <h3 class="text-lg font-bold text-teal-900 mb-2">Liên Hệ</h3>
                                            <p class="text-teal-700 text-sm">Hỗ trợ khách hàng 24/7</p>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-teal-600 to-teal-700 opacity-0 group-hover:opacity-5 rounded-xl transition-opacity">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
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

                {{-- Session messages (added consistent padding for these) --}}
                <div class="px-6 pt-4">
                    @if (session('success'))
                        <div class="text-green-600 bg-green-100 border border-green-200 rounded-lg p-3 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="text-red-600 bg-red-100 border border-red-200 rounded-lg p-3 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data"
                    class="p-6 pt-0">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Full Name --}}
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">Họ và Tên
                                *</label>
                            <input type="text" id="full_name" name="full_name"
                                value="{{ old('full_name', $user->full_name ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                required>
                            @error('full_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $user->email ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Số Điện
                                Thoại</label>
                            <input type="tel" id="phone" name="phone"
                                value="{{ old('phone', $user->phone ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Date of Birth --}}
                        <div> {{-- This div now pairs with "Phone" --}}
                            <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Ngày
                                Sinh</label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @error('date_of_birth')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Gender --}}
                        <div> {{-- This div is now alone, but it will be visually fine as we move address to its own md:col-span-2 --}}
                            <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Giới Tính</label>
                            <select id="gender" name="gender"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam" {{ ($user->gender ?? '') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ ($user->gender ?? '') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác" {{ ($user->gender ?? '') == 'Khác' ? 'selected' : '' }}>Khác
                                </option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address - Ensure it spans 2 columns --}}
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Địa Chỉ</label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">{{ old('address', $user->address ?? '') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Avatar - Ensure it spans 2 columns --}}
                        <div class="md:col-span-2">
                            <label for="avatar" class="block text-sm font-semibold text-gray-700 mb-2">Ảnh Đại
                                Diện</label>
                            <input type="file" id="avatar" name="avatar"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                    class="mt-4 w-24 h-24 rounded-full object-cover">
                            @endif
                            @error('avatar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 text-right"> {{-- Align button to the right --}}
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition-colors shadow-lg">
                            Cập Nhật Thông Tin
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Medical Record Modal (existing, assume it's styled correctly or will be) --}}
        <div id="medicalModal"
            class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            {{-- Content for medical modal goes here --}}
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold gradient-text">Hồ Sơ Y Tế</h2>
                    <button onclick="closeMedicalModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <p>Nội dung hồ sơ y tế sẽ hiển thị ở đây.</p>
                <div class="mt-4 text-right">
                    <button onclick="closeMedicalModal()"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded-full hover:bg-gray-400 transition-colors">Đóng</button>
                </div>
            </div>
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

        // Add this to ensure the modal opens if there are validation errors on submission
        @if ($errors->any() || session('success') || session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                openEditModal();
            });
        @endif
    </script>
@endsection
