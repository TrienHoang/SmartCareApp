@extends('doctor.dashboard')
@section('title', 'Thông tin cá nhân')

@section('content')
    {{-- <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-10 px-4 sm:px-6 lg:px-8"> --}}
        <div class="max-w-6xl mx-auto space-y-8">

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.005]">
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
            <div class="relative flex-shrink-0">
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}"
                    alt="Ảnh đại diện"
                    class="w-32 h-32 rounded-full object-cover border-4 border-blue-200 shadow-md">
                <div class="absolute bottom-2 right-2 bg-green-500 w-5 h-5 rounded-full border-3 border-white shadow-sm"></div>
            </div>
            <div class="text-center md:text-left flex-1">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ Auth::user()->full_name }}</h2>
               
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.005]">
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 p-3 rounded-xl mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Thông tin cá nhân</h3>
            </div>
            <div class="space-y-5">
                <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
                    <span class="text-gray-700 font-medium">Tên đăng nhập:</span>
                    <span class="text-gray-900 font-semibold">{{ Auth::user()->username }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
                    <span class="text-gray-700 font-medium">Giới tính:</span>
                    <span class="text-gray-900 font-semibold">
                        @php
                            $gender = Auth::user()->gender;
                            $genderText = match ($gender) {
                                'male' => 'Nam',
                                'female' => 'Nữ',
                                default => 'Chưa cập nhật',
                            };
                        @endphp
                        {{ $genderText }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3 last:border-b-0">
                    <span class="text-gray-700 font-medium">Ngày sinh:</span>
                    <span class="text-gray-900 font-semibold">{{ Auth::user()->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.005]">
            <div class="flex items-center mb-6">
                <div class="bg-green-100 p-3 rounded-xl mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Thông tin liên hệ</h3>
            </div>
            <div class="space-y-5">
                <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
                    <span class="text-gray-700 font-medium">Email:</span>
                    <span class="text-blue-600 break-all font-semibold">{{ Auth::user()->email }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
                    <span class="text-gray-700 font-medium">Số điện thoại:</span>
                    <span class="text-gray-900 font-semibold">{{ Auth::user()->phone ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="py-3 last:border-b-0">
                    <span class="text-gray-700 font-medium block mb-2">Địa chỉ:</span>
                    <p class="text-gray-900 font-semibold leading-relaxed">{{ Auth::user()->address ?? 'Chưa cập nhật' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.005]">
            <div class="flex items-center mb-6">
                <div class="bg-purple-100 p-3 rounded-xl mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Thông tin chuyên môn</h3>
            </div>
            <div class="space-y-5">
                <div class="py-3 border-b border-gray-100 last:border-b-0">
                    <span class="text-gray-700 font-medium block mb-2">Học vấn:</span>
                    <p class="text-gray-900 font-semibold leading-relaxed">{{ Auth::user()->education ?? 'Chưa cập nhật' }}</p>
                </div>
                <div class="py-3 last:border-b-0">
                    <span class="text-gray-700 font-medium block mb-2">Mô tả chuyên môn:</span>
                    <p class="text-gray-900 font-semibold leading-relaxed">{{ Auth::user()->description ?? 'Chưa cập nhật' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.005]">
            <div class="flex items-center mb-6">
                <div class="bg-red-100 p-3 rounded-xl mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Bảo mật</h3>
            </div>
            <div class="space-y-5">
                <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
                    <span class="text-gray-700 font-medium">Mật khẩu:</span>
                    <span class="text-gray-900 font-semibold">••••••••••</span>
                </div>
               
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="flex flex-wrap gap-4 justify-center md:justify-start">
            <a href="{{ route('doctor.profile.edit') }}"
                class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300 ease-in-out shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Chỉnh sửa thông tin
            </a>
            <a href="{{ route('doctor.profile.change-password') }}"
                    class="inline-flex items-center px-8 py-3 bg-amber-500 hover:bg-amber-600 text-gray-900 font-semibold rounded-lg transition duration-300 ease-in-out shadow-md hover:shadow-lg transform hover:-translate-y-0.5"> {{-- Changed text-white to text-gray-900 --}}
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Đổi mật khẩu
                </a>
        </div>
    </div>
</div>
    </div>
@endsection