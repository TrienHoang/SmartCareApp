@extends('doctor.dashboard')
@section('title', 'Chỉnh sửa hồ sơ')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Chỉnh sửa hồ sơ</h2>

        <!-- Form -->
        <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            {{-- @method('POST') --}}

            <!-- Thông tin cá nhân -->
            <div
                class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.002]">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-3 rounded-xl mr-4 shadow-sm">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Thông tin cá nhân</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">Họ tên</label>
                        <input type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('full_name') border-red-500 ring-2 ring-red-200 @enderror"
                            id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                            placeholder="Nhập họ tên">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Tên đăng nhập</label>
                        <input type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('username') border-red-500 ring-2 ring-red-200 @enderror"
                            id="username" name="username" value="{{ old('username', $user->username) }}"
                            placeholder="Nhập tên đăng nhập">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Giới tính</label>
                        <select
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('gender') border-red-500 ring-2 ring-red-200 @enderror"
                            id="gender" name="gender">
                            <option value="">Chọn giới tính</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam
                            </option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ
                            </option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác
                            </option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Ngày sinh</label>
                        <input type="date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('date_of_birth') border-red-500 ring-2 ring-red-200 @enderror"
                            id="date_of_birth" name="date_of_birth"
                            value="{{ old('date_of_birth', $user->date_of_birth) }}">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Thông tin liên hệ -->
            <div
                class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.002]">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-3 rounded-xl mr-4 shadow-sm">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Thông tin liên hệ</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 ring-2 ring-red-200 @enderror"
                            id="email" name="email" value="{{ old('email', $user->email) }}"
                            placeholder="Nhập email">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại</label>
                        <input type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('phone') border-red-500 ring-2 ring-red-200 @enderror"
                            id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                            placeholder="Nhập số điện thoại">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">Tỉnh / Thành phố</label>
                    <select id="province" name="province_code"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        <option value="">-- Chọn tỉnh --</option>
                    </select>
                    @error('province_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="district" class="block text-sm font-semibold text-gray-700 mb-2">Quận / Huyện</label>
                    <select id="district" name="district_code"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        disabled>
                        <option value="">-- Chọn quận --</option>
                    </select>
                    @error('district_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="ward" class="block text-sm font-semibold text-gray-700 mb-2">Phường / Xã</label>
                    <select id="ward" name="ward_code"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        disabled>
                        <option value="">-- Chọn phường --</option>
                    </select>
                    @error('ward_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name="address" id="address" value="{{ old('address', $user->address) }}">
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Thông tin chuyên môn -->
            <div
                class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.002]">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 p-3 rounded-xl mr-4 shadow-sm">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Thông tin chuyên môn</h3>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="specialization" class="block text-sm font-semibold text-gray-700 mb-2">Chuyên môn</label>
                        <input type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('specialization') border-red-500 ring-2 ring-red-200 @enderror"
                            id="specialization" name="specialization" value="{{ old('specialization', $user->specialization) }}"
                            placeholder="Nhập chuyên môn (ví dụ: Nội khoa, Ngoại khoa)">
                        @error('specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Mô tả chuyên môn</label>
                        <textarea
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('description') border-red-500 ring-2 ring-red-200 @enderror"
                            id="description" name="description" rows="3" placeholder="Mô tả về chuyên môn và kinh nghiệm">{{ old('description', $user->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Thành tựu -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thành tựu</label>
                        <div id="achievements-container" class="space-y-4">
                            @foreach ($achievements as $index => $achievement)
                                <div class="achievement-entry grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tên thành tựu</label>
                                        <input type="text"
                                            name="achievements[{{ $index }}][title]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('achievements.' . $index . '.title', $achievement->title) }}"
                                            placeholder="Nhập tên thành tựu">
                                        @error('achievements.' . $index . '.title')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tổ chức cấp</label>
                                        <input type="text"
                                            name="achievements[{{ $index }}][organization]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('achievements.' . $index . '.organization', $achievement->organization) }}"
                                            placeholder="Nhập tổ chức cấp">
                                        @error('achievements.' . $index . '.organization')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Năm</label>
                                        <input type="number"
                                            name="achievements[{{ $index }}][year]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('achievements.' . $index . '.year', $achievement->year) }}"
                                            placeholder="Nhập năm">
                                        @error('achievements.' . $index . '.year')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
                                        <textarea
                                            name="achievements[{{ $index }}][description]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            rows="3" placeholder="Nhập mô tả">{{ old('achievements.' . $index . '.description', $achievement->description) }}</textarea>
                                        @error('achievements.' . $index . '.description')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="button" class="remove-achievement text-red-600 hover:text-red-800">Xóa</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-achievement"
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Thêm thành tựu</button>
                    </div>

                    <!-- Học vấn -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Học vấn chi tiết</label>
                        <div id="educations-container" class="space-y-4">
                            @foreach ($educations as $index => $education)
                                <div class="education-entry grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bằng cấp</label>
                                        <input type="text"
                                            name="educations[{{ $index }}][degree]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('educations.' . $index . '.degree', $education->degree) }}"
                                            placeholder="Nhập bằng cấp">
                                        @error('educations.' . $index . '.degree')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Trường học</label>
                                        <input type="text"
                                            name="educations[{{ $index }}][school]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('educations.' . $index . '.school', $education->school) }}"
                                            placeholder="Nhập trường học">
                                        @error('educations.' . $index . '.school')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Năm bắt đầu</label>
                                        <input type="number"
                                            name="educations[{{ $index }}][start_year]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('educations.' . $index . '.start_year', $education->start_year) }}"
                                            placeholder="Nhập năm bắt đầu">
                                        @error('educations.' . $index . '.start_year')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Năm kết thúc</label>
                                        <input type="number"
                                            name="educations[{{ $index }}][end_year]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('educations.' . $index . '.end_year', $education->end_year) }}"
                                            placeholder="Nhập năm kết thúc">
                                        @error('educations.' . $index . '.end_year')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
                                        <textarea
                                            name="educations[{{ $index }}][description]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            rows="3" placeholder="Nhập mô tả">{{ old('educations.' . $index . '.description', $education->description) }}</textarea>
                                        @error('educations.' . $index . '.description')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="button" class="remove-education text-red-600 hover:text-red-800">Xóa</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-education"
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Thêm học vấn</button>
                    </div>

                    <!-- Kinh nghiệm -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kinh nghiệm</label>
                        <div id="experiences-container" class="space-y-4">
                            @foreach ($experiences as $index => $experience)
                                <div class="experience-entry grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Chức vụ</label>
                                        <input type="text"
                                            name="experiences[{{ $index }}][degree]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('experiences.' . $index . '.degree', $experience->degree) }}"
                                            placeholder="Nhập chức vụ">
                                        @error('experiences.' . $index . '.degree')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nơi làm việc</label>
                                        <input type="text"
                                            name="experiences[{{ $index }}][school]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('experiences.' . $index . '.school', $experience->school) }}"
                                            placeholder="Nhập nơi làm việc">
                                        @error('experiences.' . $index . '.school')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Năm bắt đầu</label>
                                        <input type="number"
                                            name="experiences[{{ $index }}][start_year]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('experiences.' . $index . '.start_year', $experience->start_year) }}"
                                            placeholder="Nhập năm bắt đầu">
                                        @error('experiences.' . $index . '.start_year')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Năm kết thúc</label>
                                        <input type="number"
                                            name="experiences[{{ $index }}][end_year]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            value="{{ old('experiences.' . $index . '.end_year', $experience->end_year) }}"
                                            placeholder="Nhập năm kết thúc">
                                        @error('experiences.' . $index . '.end_year')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
                                        <textarea
                                            name="experiences[{{ $index }}][description]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                            rows="3" placeholder="Nhập mô tả">{{ old('experiences.' . $index . '.description', $experience->description) }}</textarea>
                                        @error('experiences.' . $index . '.description')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="button" class="remove-experience text-red-600 hover:text-red-800">Xóa</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-experience"
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Thêm kinh nghiệm</button>
                    </div>
                </div>
            </div>

            <!-- Ảnh đại diện -->
            <div
                class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 transform transition-all duration-300 hover:scale-[1.002]">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-100 p-3 rounded-xl mr-4 shadow-sm">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Ảnh đại diện</h3>
                </div>

                <div>
                    <label for="avatar" class="block text-sm font-semibold text-gray-700 mb-2">Chọn ảnh mới</label>
                    <input type="file"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('avatar') border-red-500 ring-2 ring-red-200 @enderror"
                        id="avatar" name="avatar" accept="image/*">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Lưu thay đổi
                    </button>

                    <a href="{{ route('doctor.profile.change-password') }}"
                        class="inline-flex items-center px-8 py-3 bg-amber-500 hover:bg-amber-600 text-gray-900 font-semibold rounded-lg transition duration-300 ease-in-out shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                        Đổi mật khẩu
                    </a>

                    <a href="{{ route('doctor.profile.show') }}"
                        class="inline-flex items-center px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition duration-300 ease-in-out shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Quay lại
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        const addressInput = document.getElementById('address');

        let selectedProvince = '{{ $user->province_code ? Http::get("https://provinces.open-api.vn/api/p/{$user->province_code}")->json("name") : "" }}';
        let selectedDistrict = '{{ $user->district_code ? Http::get("https://provinces.open-api.vn/api/d/{$user->district_code}")->json("name") : "" }}';
        let selectedWard = '{{ $user->ward_code ? Http::get("https://provinces.open-api.vn/api/w/{$user->ward_code}")->json("name") : "" }}';

        // Load provinces
        fetch('https://provinces.open-api.vn/api/?depth=1')
            .then(res => res.json())
            .then(data => {
                data.forEach(province => {
                    let opt = document.createElement('option');
                    opt.value = province.code;
                    opt.textContent = province.name;
                    if (province.code === '{{ $user->province_code }}') opt.selected = true;
                    provinceSelect.appendChild(opt);
                });
                if ('{{ $user->province_code }}') provinceSelect.dispatchEvent(new Event('change'));
            });

        // When province changes
        provinceSelect.addEventListener('change', function() {
            const provinceCode = this.value;
            selectedProvince = this.options[this.selectedIndex].text;
            districtSelect.innerHTML = '<option value="">-- Chọn quận --</option>';
            wardSelect.innerHTML = '<option value="">-- Chọn phường --</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;

            if (!provinceCode) {
                addressInput.value = '';
                return;
            }

            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                .then(res => res.json())
                .then(data => {
                    data.districts.forEach(district => {
                        let opt = document.createElement('option');
                        opt.value = district.code;
                        opt.textContent = district.name;
                        if (district.code === '{{ $user->district_code }}') opt.selected = true;
                        districtSelect.appendChild(opt);
                    });
                    districtSelect.disabled = false;
                    if ('{{ $user->district_code }}') districtSelect.dispatchEvent(new Event('change'));
                });
        });

        // When district changes
        districtSelect.addEventListener('change', function() {
            const districtCode = this.value;
            selectedDistrict = this.options[this.selectedIndex].text;
            wardSelect.innerHTML = '<option value="">-- Chọn phường --</option>';
            wardSelect.disabled = true;

            if (!districtCode) {
                addressInput.value = selectedProvince ? `${selectedProvince}` : '';
                return;
            }

            fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                .then(res => res.json())
                .then(data => {
                    data.wards.forEach(ward => {
                        let opt = document.createElement('option');
                        opt.value = ward.code;
                        opt.textContent = ward.name;
                        if (ward.code === '{{ $user->ward_code }}') opt.selected = true;
                        wardSelect.appendChild(opt);
                    });
                    wardSelect.disabled = false;
                    if ('{{ $user->ward_code }}') wardSelect.dispatchEvent(new Event('change'));
                });
        });

        // When ward changes
        wardSelect.addEventListener('change', function() {
            selectedWard = this.options[this.selectedIndex].text;
            addressInput.value = selectedWard ? `${selectedWard}, ${selectedDistrict}, ${selectedProvince}` : `${selectedDistrict}, ${selectedProvince}`;
        });

        // JavaScript để thêm/xóa động các mục
        let achievementIndex = {{ $achievements->count() }};
        document.getElementById('add-achievement').addEventListener('click', function() {
            const container = document.getElementById('achievements-container');
            const entry = document.createElement('div');
            entry.className = 'achievement-entry grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded-lg';
            entry.innerHTML = `
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên thành tựu</label>
                    <input type="text" name="achievements[${achievementIndex}][title]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập tên thành tựu">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tổ chức cấp</label>
                    <input type="text" name="achievements[${achievementIndex}][organization]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập tổ chức cấp">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Năm</label>
                    <input type="number" name="achievements[${achievementIndex}][year]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập năm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
                    <textarea name="achievements[${achievementIndex}][description]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" rows="3" placeholder="Nhập mô tả"></textarea>
                </div>
                <button type="button" class="remove-achievement text-red-600 hover:text-red-800">Xóa</button>
            `;
            container.appendChild(entry);
            achievementIndex++;
        });

        let educationIndex = {{ $educations->count() }};
        document.getElementById('add-education').addEventListener('click', function() {
            const container = document.getElementById('educations-container');
            const entry = document.createElement('div');
            entry.className = 'education-entry grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded-lg';
            entry.innerHTML = `
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bằng cấp</label>
                    <input type="text" name="educations[${educationIndex}][degree]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập bằng cấp">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Trường học</label>
                    <input type="text" name="educations[${educationIndex}][school]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập trường học">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Năm bắt đầu</label>
                    <input type="number" name="educations[${educationIndex}][start_year]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập năm bắt đầu">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Năm kết thúc</label>
                    <input type="number" name="educations[${educationIndex}][end_year]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập năm kết thúc">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
                    <textarea name="educations[${educationIndex}][description]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" rows="3" placeholder="Nhập mô tả"></textarea>
                </div>
                <button type="button" class="remove-education text-red-600 hover:text-red-800">Xóa</button>
            `;
            container.appendChild(entry);
            educationIndex++;
        });

        let experienceIndex = {{ $experiences->count() }};
        document.getElementById('add-experience').addEventListener('click', function() {
            const container = document.getElementById('experiences-container');
            const entry = document.createElement('div');
            entry.className = 'experience-entry grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded-lg';
            entry.innerHTML = `
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Chức vụ</label>
                    <input type="text" name="experiences[${experienceIndex}][degree]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập chức vụ">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nơi làm việc</label>
                    <input type="text" name="experiences[${experienceIndex}][school]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập nơi làm việc">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Năm bắt đầu</label>
                    <input type="number" name="experiences[${experienceIndex}][start_year]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập năm bắt đầu">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Năm kết thúc</label>
                    <input type="number" name="experiences[${experienceIndex}][end_year]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Nhập năm kết thúc">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
                    <textarea name="experiences[${experienceIndex}][description]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" rows="3" placeholder="Nhập mô tả"></textarea>
                </div>
                <button type="button" class="remove-experience text-red-600 hover:text-red-800">Xóa</button>
            `;
            container.appendChild(entry);
            experienceIndex++;
        });

        // Xóa mục
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-achievement')) {
                e.target.closest('.achievement-entry').remove();
            } else if (e.target.classList.contains('remove-education')) {
                e.target.closest('.education-entry').remove();
            } else if (e.target.classList.contains('remove-experience')) {
                e.target.closest('.experience-entry').remove();
            }
        });
    </script>
@endpush