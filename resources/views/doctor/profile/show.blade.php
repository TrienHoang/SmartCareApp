@extends('doctor.dashboard')
@section('title', 'Thông tin cá nhân')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8">
        {{-- Header chính --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.005]">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                <div class="relative flex-shrink-0">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                         alt="Ảnh đại diện" class="w-32 h-32 rounded-full object-cover border-4 border-blue-200 shadow-md">
                    <div class="absolute bottom-2 right-2 bg-green-500 w-5 h-5 rounded-full border-3 border-white shadow-sm"></div>
                </div>
                <div class="text-center md:text-left flex-1">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $user->full_name }}</h2>
                    <p class="text-lg text-gray-600 font-medium">{{ optional($doctor->department)->name ?? 'Khoa chưa cập nhật' }}</p>
                </div>
            </div>
        </div>

        {{-- Grid chính --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Thông tin cá nhân & liên hệ --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.005]">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-3 rounded-xl mr-4 shadow-sm">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Thông tin cá nhân & liên hệ</h3>
                </div>
                <div class="space-y-5">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-700 font-medium">Họ và tên:</span>
                        <span class="text-gray-900 font-semibold">{{ $user->full_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-700 font-medium">Tên đăng nhập:</span>
                        <span class="text-gray-900 font-semibold">{{ $user->username }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-700 font-medium">Email:</span>
                        <span class="text-blue-600 break-all font-semibold">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-700 font-medium">Giới tính:</span>
                        <span class="text-gray-900 font-semibold">
                            @php
                                $genderText = ['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'][$user->gender] ?? 'Chưa cập nhật';
                            @endphp
                            {{ $genderText }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-700 font-medium">Ngày sinh:</span>
                        <span class="text-gray-900 font-semibold">{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 last:border-b-0">
                        <span class="text-gray-700 font-medium">Số điện thoại:</span>
                        <span class="text-gray-900 font-semibold">{{ $user->phone ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="py-3 last:border-b-0 flex items-start gap-2">
                        <span class="text-gray-700 font-medium w-24">Địa chỉ:</span>
                        @if ($fullAddress)
                            <p class="text-gray-900 font-semibold flex items-center gap-1 leading-relaxed">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                {{ $fullAddress }}
                            </p>
                        @else
                            <p class="text-gray-500 italic">Chưa cập nhật</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Thông tin chuyên môn --}}
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
                    <div class="py-3 border-b border-gray-100">
                        <span class="text-gray-700 font-medium block mb-2">Tiểu sử:</span>
                        <p class="text-gray-900 leading-relaxed font-semibold">{{ $doctor->biography ?? 'Chưa cập nhật' }}</p>
                    </div>
                    {{-- <div class="py-3 border-b border-gray-100">
                        <span class="text-gray-700 font-medium block mb-2">Các chuyên khoa:</span>
                        @forelse ($doctor->specialties as $specialty)
                            <span class="inline-block bg-purple-100 text-purple-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded-full">{{ $specialty->name }}</span>
                        @empty
                            <p class="text-gray-500 italic">Chưa cập nhật</p>
                        @endforelse
                    </div> --}}
                    <div class="py-3 border-b border-gray-100">
                        <span class="text-gray-700 font-medium block mb-2">Học vấn:</span>
                        @forelse ($educations as $education)
                            <div class="mb-4">
                                <h4 class="text-gray-900 font-bold">{{ $education->degree }}</h4>
                                <p class="text-gray-600 text-sm italic">{{ $education->school }} ({{ $education->start_year }} - {{ $education->end_year ?? 'Hiện tại' }})</p>
                                @if ($education->description)
                                    <p class="text-gray-700 text-sm mt-1">{{ $education->description }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Chưa cập nhật</p>
                        @endforelse
                    </div>
                    <div class="py-3 border-b border-gray-100">
                        <span class="text-gray-700 font-medium block mb-2">Kinh nghiệm:</span>
                        @forelse ($experiences as $experience)
                            <div class="mb-4">
                                <h4 class="text-gray-900 font-bold">{{ $experience->position }}</h4>
                                <p class="text-gray-600 text-sm italic">{{ $experience->institution }} ({{ $experience->start_year }} - {{ $experience->end_year ?? 'Hiện tại' }})</p>
                                @if ($experience->description)
                                    <p class="text-gray-700 text-sm mt-1">{{ $experience->description }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Chưa cập nhật</p>
                        @endforelse
                    </div>
                    <div class="py-3 last:border-b-0">
                        <span class="text-gray-700 font-medium block mb-2">Thành tựu:</span>
                        @forelse ($achievements as $achievement)
                            <div class="mb-4">
                                <h4 class="text-gray-900 font-bold">{{ $achievement->title }}</h4>
                                <p class="text-gray-600 text-sm italic">{{ $achievement->organization }} ({{ $achievement->year }})</p>
                                @if ($achievement->description)
                                    <p class="text-gray-700 text-sm mt-1">{{ $achievement->description }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Chưa cập nhật</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Nút hành động --}}
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
                   class="inline-flex items-center px-8 py-3 bg-amber-500 hover:bg-amber-600 text-gray-900 font-semibold rounded-lg transition duration-300 ease-in-out shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Đổi mật khẩu
                </a>
            </div>
        </div>
    </div>
@endsection