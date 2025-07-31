@extends('doctor.dashboard')
@section('title', 'Chỉnh sửa hồ sơ')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Chỉnh sửa hồ sơ</h2>

    <!-- Form -->
    <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <!-- Thông tin cá nhân -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.002]">
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 p-3 rounded-xl mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Thông tin cá nhân</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">Họ tên</label>
                    <input type="text" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('full_name') border-red-500 ring-2 ring-red-200 @enderror" 
                           id="full_name" 
                           name="full_name" 
                           value="{{ old('full_name', $user->full_name) }}" 
                           placeholder="Nhập họ tên">
                    @error('full_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Tên đăng nhập</label>
                    <input type="text" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('username') border-red-500 ring-2 ring-red-200 @enderror" 
                           id="username" 
                           name="username" 
                           value="{{ old('username', $user->username) }}" 
                           placeholder="Nhập tên đăng nhập">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Giới tính</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('gender') border-red-500 ring-2 ring-red-200 @enderror" 
                            id="gender" 
                            name="gender">
                        <option value="">Chọn giới tính</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Ngày sinh</label>
                    <input type="date" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('date_of_birth') border-red-500 ring-2 ring-red-200 @enderror" 
                           id="date_of_birth" 
                           name="date_of_birth" 
                           value="{{ old('date_of_birth', $user->date_of_birth) }}">
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.002]">
            <div class="flex items-center mb-6">
                <div class="bg-green-100 p-3 rounded-xl mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Thông tin liên hệ</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 ring-2 ring-red-200 @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           placeholder="Nhập email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại</label>
                    <input type="text" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('phone') border-red-500 ring-2 ring-red-200 @enderror" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $user->phone) }}" 
                           placeholder="Nhập số điện thoại">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ</label>
                <input type="text" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('address') border-red-500 ring-2 ring-red-200 @enderror" 
                       id="address" 
                       name="address" 
                       value="{{ old('address', $user->address) }}" 
                       placeholder="Nhập địa chỉ">
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Thông tin chuyên môn -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.002]">
            <div class="flex items-center mb-6">
                <div class="bg-purple-100 p-3 rounded-xl mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Thông tin chuyên môn</h3>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="education" class="block text-sm font-semibold text-gray-700 mb-2">Học vấn</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('education') border-red-500 ring-2 ring-red-200 @enderror" 
                              id="education" 
                              name="education" 
                              rows="3" 
                              placeholder="Nhập thông tin học vấn">{{ old('education', $user->education) }}</textarea>
                    @error('education')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chuyên môn</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('description') border-red-500 ring-2 ring-red-200 @enderror" 
                              id="description" 
                              name="description" 
                              rows="3" 
                              placeholder="Mô tả về chuyên môn và kinh nghiệm">{{ old('description', $user->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Ảnh đại diện -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.002]">
            <div class="flex items-center mb-6">
                <div class="bg-indigo-100 p-3 rounded-xl mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Ảnh đại diện</h3>
            </div>

            <div>
                <label for="avatar" class="block text-sm font-semibold text-gray-700 mb-2">Chọn ảnh mới</label>
                <input type="file" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('avatar') border-red-500 ring-2 ring-red-200 @enderror" 
                       id="avatar" 
                       name="avatar"
                       accept="image/*">
                @error('avatar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300 ease-in-out shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Lưu thay đổi
                </button>
                
                <a href="{{ route('doctor.profile.change-password') }}" 
                   class="inline-flex items-center px-8 py-3 bg-amber-500 hover:bg-amber-600 text-gray-900 font-semibold rounded-lg transition duration-300 ease-in-out shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Đổi mật khẩu
                </a>

                <a href="{{ route('doctor.profile.show') }}" 
                   class="inline-flex items-center px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition duration-300 ease-in-out shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>
    </form>
</div>
@endsection