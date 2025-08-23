@extends('client.layouts.app')

@section('title', 'Thông Tin Bác Sĩ')

@push('styles')
    <style>
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Smooth scroll cho button */
        html {
            scroll-behavior: smooth;
        }

        /* Service card hover effect - subtle */
        .hover\:shadow-md:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .md\:flex-row {
                flex-direction: column;
            }

            .md\:w-48 {
                width: 100%;
            }
        }

        /* Line clamp utility if not available */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Button focus states */
        button:focus-visible,
        a:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* Smooth transitions */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bg-doctor {
            background-image: url({{ asset('admin/assets/img/dr-profile-banner-dt-4.png') }});
            background-size: cover;
            background-position: center;

        }

        .bg-doctor-top {
            background-image: url({{ asset('admin/assets/img/doctor-item-top-bg.png') }});
            background-size: cover;
            background-position: top center;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #2563eb 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .rating-star {
            color: #fbbf24;
        }

        .rating-star {
            transition: color 0.2s ease, fill 0.2s ease;
        }

        .rating-star.text-yellow-400 {
            color: #fbbf24 !important;
            fill: #fbbf24 !important;
        }

        .rating-star.text-gray-300 {
            color: #d1d5db !important;
            fill: none !important;
        }

        .star-transition {
            transition: all 0.2s ease-in-out !important;
        }

        .star-transition:hover {
            filter: brightness(1.2);
            drop-shadow: 0 0 8px rgba(255, 193, 7, 0.6));
        }

        .simple-stars {
            display: flex;
            gap: 2px;
        }

        .simple-stars input[type="radio"] {
            display: none;
        }

        .simple-stars label {
            cursor: pointer;
            font-size: 0;
            /* Ẩn text */
            display: inline-block;
            transition: transform 0.2s ease;
        }

        .simple-stars label:hover {
            transform: scale(1.1);
        }

        .simple-stars svg {
            width: 20px;
            height: 20px;
            fill: #d1d5db;
            stroke: #d1d5db;
            transition: all 0.2s ease;
        }

        /* Khi hover - tất cả sao từ đầu đến sao được hover sẽ vàng */
        .simple-stars label:hover svg,
        .simple-stars label:hover~label svg {
            fill: #fbbf24;
            stroke: #fbbf24;
        }

        /* Khi được chọn - sao và tất cả sao trước nó sẽ vàng */
        .simple-stars input:checked~label svg {
            fill: #fbbf24;
            stroke: #fbbf24;
        }

        /* Reverse order để CSS selector hoạt động đúng */
        .simple-stars {
            flex-direction: row-reverse;
            justify-content: flex-end;
        }
    </style>
@endpush

@section('content')

    <body class="bg-gray-50 scroll-smooth">
        <section class="relative bg-doctor overflow-hidden ps-10 pe-10 py-12 ">
            <div class="absolute inset-0">
                <div class="absolute top-10 right-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                <div class="absolute bottom-20 left-10 w-24 h-24 bg-white/10 rounded-full blur-lg"></div>
                <div
                    class="absolute top-1/2 right-1/4 w-40 h-40 bg-gradient-to-r from-purple-400/20 to-blue-400/20 rounded-full blur-2xl">
                </div>
            </div>

            <div class="container mx-auto px-1 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    {{-- Thông tin chính bác sĩ --}}
                    <div class="lg:col-span-6 fade-in">
                        {{-- Tên và chức danh bác sĩ --}}
                        <div class="mb-6">
                            <h1 class="text-5xl lg:text-4xl font-bold text-blue-600 mb-2">
                                {{ $doctor->user->full_name }}
                            </h1>

                            {{-- Badges và Rating --}}
                            <div class="flex flex-wrap items-center gap-3 mt-4">
                                <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-full px-3 py-1">
                                    @php
                                        $rating = $doctor->average_rating;
                                    @endphp

                                    @if (is_null($rating) || $rating == 0)
                                        <span class="text-blue-600 text-base italic">Chưa có đánh giá</span>
                                    @else
                                        @php
                                            $fullStars = floor($rating);
                                            $hasHalfStar = $rating - $fullStars >= 0.5;
                                        @endphp

                                        @for ($i = 1; $i <= $fullStars; $i++)
                                            <i data-lucide="star" class="w-5 h-5 text-yellow-300 fill-yellow-300 mr-1"></i>
                                        @endfor

                                        @if ($hasHalfStar)
                                            <i data-lucide="star-half"
                                                class="w-5 h-5 text-yellow-300 fill-yellow-300 mr-1"></i>
                                        @endif

                                        @for ($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++)
                                            <i data-lucide="star" class="w-5 h-5 text-gray-400 mr-1"></i>
                                        @endfor

                                        <span class="text-blue-600 text-xl ml-1">({{ number_format($rating, 1) }}/5)</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center mt-4">
                                <div class="flex items-center  rounded-full  py-1 gap-3">

                                    <div class="flex items-center bg-green-500/20 backdrop-blur-sm rounded-full px-4 py-1">
                                        @php
                                            $genderText =
                                                ['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'][
                                                    $doctor->user->gender
                                                ] ?? 'Chưa cập nhật';
                                        @endphp
                                        <span class="text-blue-500 text-xl">{{ $genderText }}</span>
                                    </div>

                                    <div class="flex items-center bg-purple-500/20 backdrop-blur-sm rounded-full px-4 py-1">
                                        <span class="text-blue-500 text-xl">{{ $doctor->department->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Experience Badge --}}
                        <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                            <i data-lucide="award" class="w-5 h-5 text-blue-600 mr-2"></i>
                            <span class="text-blue-500 text-xl">{{ $doctor->experience_years ?? '6' }} Năm kinh
                                nghiệm</span>
                        </div>

                        {{-- Mô tả chuyên môn --}}
                        <p class="text-blue-500/90 text-lg leading-relaxed mb-8 max-w-2xl">
                            {{ $doctor->biography ?? 'ThS. BS. CKI. Trần Anh Ngọc đã có hơn 6 năm kinh nghiệm trong lĩnh vực Tâm thần - Tâm lý, có chuyên môn sâu về rối loạn giấc ngủ, rối loạn trầm cảm, rối loạn lưỡng cực, rối loạn lo âu, rối loạn loạn thần và các rối loạn liên quan tới sử dụng chất,...' }}
                        </p>
                    </div>

                    {{-- Hình ảnh bác sĩ --}}
                    <div class="lg:col-span-6 fade-in">
                        <div class="relative rounded-3xl p-8 overflow-hidden">
                            <img src="{{ $doctor->user && $doctor->user->avatar ? asset('storage/' . $doctor->user->avatar) : asset('images/default-doctor.png') }}"
                                alt="{{ $doctor->user->full_name ?? 'Bác sĩ' }}" class="w-full max-w-sm mx-auto rounded-2xl"
                                style="mask-image: linear-gradient(to bottom, black 90%, transparent 100%);
                                   -webkit-mask-image: linear-gradient(to bottom, black 90%, transparent 100%);">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Navigation Tabs -->
        <section class="bg-white border-b border-gray-200 sticky top-[70px] z-40">
            <div class="container mx-auto px-4 py-2">
                <nav class="flex space-x-8 overflow-x-auto">
                    <button onclick="showTab('info')" id="tab-info"
                        class="tab-btn py-4 px-2 border-b-2 border-blue-600 text-blue-600 font-semibold whitespace-nowrap">
                        Thông tin
                    </button>
                    <button onclick="showTab('schedule')" id="tab-schedule"
                        class="tab-btn py-4 px-2 border-b-2 border-transparent text-gray-600 hover:text-blue-600 whitespace-nowrap">
                        Dịch vụ
                    </button>
                    <button onclick="showTab('reviews')" id="tab-reviews"
                        class="tab-btn py-4 px-2 border-b-2 border-transparent text-gray-600 hover:text-blue-600 whitespace-nowrap">
                        Đánh giá
                    </button>
                </nav>
            </div>
        </section>

        <!-- Content -->
        <main class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <!-- Main Content -->
                <!-- Doctor Info Tab -->
                <div id="content-info" class="tab-content">
                    <!-- Tổng quan nhanh -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-2xl font-bold mb-6 gradient-text flex items-center">
                            <i data-lucide="user" class="w-6 h-6 mr-3 text-blue-600"></i>
                            Tổng quan
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                <div
                                    class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                                </div>
                                <div class="text-2xl font-bold text-blue-600">{{ $doctor->experience_years ?? '6' }}</div>
                                <div class="text-sm text-gray-600 font-medium">Năm kinh nghiệm</div>
                            </div>

                            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                                <div
                                    class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="graduation-cap" class="w-6 h-6 text-white"></i>
                                </div>
                                <div class="text-2xl font-bold text-green-600">{{ $doctor->educations->count() }}</div>
                                <div class="text-sm text-gray-600 font-medium">Bằng cấp</div>
                            </div>

                            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                                <div
                                    class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="briefcase" class="w-6 h-6 text-white"></i>
                                </div>
                                <div class="text-2xl font-bold text-purple-600">{{ $doctor->experiences->count() }}</div>
                                <div class="text-sm text-gray-600 font-medium">Kinh nghiệm</div>
                            </div>

                            <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl">
                                <div
                                    class="w-12 h-12 bg-yellow-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="award" class="w-6 h-6 text-white"></i>
                                </div>
                                <div class="text-2xl font-bold text-yellow-600">{{ $doctor->achievements->count() }}</div>
                                <div class="text-sm text-gray-600 font-medium">Thành tích</div>
                            </div>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-8">
                        <!-- Học vấn -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 card-hover h-fit">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                                    <i data-lucide="graduation-cap" class="w-6 h-6 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Học vấn</h3>
                                    <p class="text-sm text-gray-500">Quá trình đào tạo</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                @forelse ($doctor->educations as $education)
                                    <div class="relative pl-8 pb-6 border-l-2 border-green-200 last:border-l-0 last:pb-0">
                                        <!-- Timeline dot -->
                                        <div
                                            class="absolute -left-2 top-2 w-4 h-4 bg-green-500 rounded-full border-2 border-white shadow-lg">
                                        </div>

                                        <!-- Content -->
                                        <div class="bg-green-50 rounded-lg p-4">
                                            <div class="flex flex-wrap items-center justify-between mb-2">
                                                <h4 class="font-bold text-green-800 text-lg">{{ $education->degree }}</h4>
                                                <span
                                                    class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    {{ $education->start_year }} - {{ $education->end_year }}
                                                </span>
                                            </div>
                                            <p class="text-green-700 font-medium mb-1">{{ $education->school }}</p>
                                            @if ($education->description)
                                                <p class="text-gray-600 text-sm">{{ $education->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <i data-lucide="book-open" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                                        <p class="text-gray-500">Chưa cập nhật thông tin học vấn</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Kinh nghiệm làm việc -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 card-hover h-fit">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                                    <i data-lucide="briefcase" class="w-6 h-6 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Kinh nghiệm làm việc</h3>
                                    <p class="text-sm text-gray-500">Quá trình công tác</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                @forelse ($doctor->experiences as $experience)
                                    <div class="relative pl-8 pb-6 border-l-2 border-blue-200 last:border-l-0 last:pb-0">
                                        <!-- Timeline dot -->
                                        <div
                                            class="absolute -left-2 top-2 w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow-lg">
                                        </div>

                                        <!-- Content -->
                                        <div class="bg-blue-50 rounded-lg p-4">
                                            <div class="flex flex-wrap items-center justify-between mb-2">
                                                <h4 class="font-bold text-blue-800 text-lg">{{ $experience->position }}
                                                </h4>
                                                <span
                                                    class="bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    {{ $experience->start_year }} -
                                                    {{ $experience->end_year ?? 'Hiện tại' }}
                                                </span>
                                            </div>
                                            <p class="text-blue-700 font-medium mb-1">{{ $experience->institution }}</p>
                                            @if ($experience->description)
                                                <p class="text-gray-600 text-sm">{{ $experience->description }}</p>
                                            @endif

                                            <!-- Tính số năm -->
                                            @php
                                                $years = ($experience->end_year ?? date('Y')) - $experience->start_year;
                                            @endphp
                                            @if ($years > 0)
                                                <div class="mt-2">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                                        {{ $years }} năm
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <i data-lucide="briefcase" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                                        <p class="text-gray-500">Chưa cập nhật thông tin kinh nghiệm</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Chuyên khoa -->
                    @if ($doctor->specialties && $doctor->specialties->count())
                        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8 card-hover">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                                    <i data-lucide="heart" class="w-6 h-6 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Chuyên khoa</h3>
                                    <p class="text-sm text-gray-500">Lĩnh vực chuyên môn</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($doctor->specialties as $specialty)
                                    <div
                                        class="group p-6 rounded-xl bg-gradient-to-br from-red-50 to-pink-50 border border-red-100 hover:border-red-300 transition-all duration-300 hover:shadow-lg">
                                        <div class="flex items-start space-x-4">
                                            <div
                                                class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                                <i data-lucide="stethoscope" class="w-5 h-5 text-red-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-bold text-red-800 mb-2">{{ $specialty->name }}</h4>
                                                <p class="text-gray-600 text-sm leading-relaxed">
                                                    {{ $specialty->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Thành tích & Chứng chỉ -->
                    @if ($doctor->achievements && $doctor->achievements->count())
                        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8 card-hover">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mr-4">
                                    <i data-lucide="trophy" class="w-6 h-6 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Thành tích & Chứng chỉ</h3>
                                    <p class="text-sm text-gray-500">Giải thưởng và chứng nhận</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($doctor->achievements as $achievement)
                                    <div
                                        class="group bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200 hover:border-yellow-400 hover:shadow-lg transition-all duration-300">
                                        <div class="flex items-start space-x-4">
                                            <!-- Icon -->
                                            <div
                                                class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors flex-shrink-0">
                                                <i data-lucide="award" class="w-6 h-6 text-yellow-600"></i>
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-yellow-800 mb-2 break-words">
                                                    {{ $achievement->title }}</h4>

                                                <!-- Year & Organization -->
                                                <div class="space-y-2 mb-3">
                                                    <div class="flex items-center text-sm text-yellow-700">
                                                        <i data-lucide="calendar" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                                                        <span>{{ $achievement->year }}</span>
                                                    </div>

                                                    @if ($achievement->organization)
                                                        <div class="flex items-center text-sm text-yellow-700">
                                                            <i data-lucide="building"
                                                                class="w-4 h-4 mr-2 flex-shrink-0"></i>
                                                            <span
                                                                class="break-words">{{ $achievement->organization }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Description -->
                                                @if ($achievement->description)
                                                    <p class="text-gray-600 text-sm leading-relaxed">
                                                        {{ $achievement->description }}</p>
                                                @else
                                                    <p class="text-gray-500 text-sm italic">Thông tin chi tiết chưa được
                                                        cập nhật</p>
                                                @endif

                                                <!-- Badge -->
                                                <div class="mt-3">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800">
                                                        <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                                                        Thành tích
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">
                            <div class="text-center py-12">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="award" class="w-8 h-8 text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có thành tích</h3>
                                <p class="text-gray-500">Thông tin thành tích và chứng chỉ sẽ được cập nhật sau</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Services Tab - Optimized Version -->
                <div id="content-schedule" class="tab-content hidden">
                    <div class="space-y-6">
                        <!-- Header -->
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                        <i data-lucide="stethoscope" class="w-6 h-6 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900">Dịch vụ khám chữa bệnh</h2>
                                        <p class="text-gray-500 text-sm mt-1">Các dịch vụ y tế được cung cấp</p>
                                    </div>
                                </div>

                                @if ($doctor->services && $doctor->services->count())
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Tổng cộng</p>
                                        <p class="text-2xl font-bold text-blue-600">{{ $doctor->services->count() }}</p>
                                        <p class="text-sm text-gray-500">dịch vụ</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Services Grid -->
                        @forelse ($doctor->services as $service)
                            <div
                                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Service Image -->
                                    <div class="md:w-48 h-48 md:h-auto flex-shrink-0">
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                            class="w-full h-full object-cover">
                                    </div>

                                    <!-- Service Content -->
                                    <div class="flex-1 p-6">
                                        <div class="flex flex-col h-full">
                                            <!-- Title and Description -->
                                            <div class="flex-1">
                                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $service->name }}
                                                </h3>

                                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                                    {{ Str::limit(strip_tags($service->description), 120) }}
                                                </p>
                                            </div>

                                            <!-- Service Details -->
                                            <div class="space-y-3">
                                                <!-- Price and Duration -->
                                                <div class="flex flex-wrap gap-3">
                                                    <div class="flex items-center bg-blue-50 px-3 py-2 rounded-lg">
                                                        <i data-lucide="credit-card"
                                                            class="w-4 h-4 text-blue-600 mr-2"></i>
                                                        <span
                                                            class="text-blue-800 font-semibold">{{ number_format($service->price, 0, ',', '.') }}đ</span>
                                                    </div>

                                                    <div class="flex items-center bg-green-50 px-3 py-2 rounded-lg">
                                                        <i data-lucide="clock" class="w-4 h-4 text-green-600 mr-2"></i>
                                                        <span class="text-green-800 font-medium">{{ $service->duration }}
                                                            phút</span>
                                                    </div>
                                                </div>

                                                <!-- Action Button -->
                                                <div class="flex justify-between items-center pt-2">
                                                    <div class="text-xs text-gray-400">
                                                        Nhấn để đặt lịch khám
                                                    </div>
                                                    <a href="{{ route('booking.showService', $service->id) }}"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                                        <i data-lucide="calendar-plus" class="w-4 h-4 mr-2"></i>
                                                        Đặt lịch khám
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- Empty State -->
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12">
                                <div class="text-center">
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i data-lucide="calendar-x" class="w-10 h-10 text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có dịch vụ</h3>
                                    <p class="text-gray-500 max-w-md mx-auto">
                                        Bác sĩ hiện chưa có dịch vụ nào được liên kết. Vui lòng liên hệ trực tiếp để biết
                                        thêm thông tin.
                                    </p>
                                    <div class="mt-6">
                                        <button
                                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                                            <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                                            Liên hệ tư vấn
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforelse

                        <!-- Additional Info -->
                        @if ($doctor->services && $doctor->services->count())
                            <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-blue-900 mb-2">Lưu ý quan trọng</h4>
                                        <ul class="text-blue-700 text-sm space-y-1 list-disc list-inside">
                                            <li>Vui lòng đến trước giờ hẹn 15 phút để làm thủ tục</li>
                                            <li>Mang theo giấy tờ tùy thân và sổ khám bệnh (nếu có)</li>
                                            <li>Có thể hủy lịch trước khi được admin xác nhận </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Reviews Tab -->
                <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

                <!-- Reviews Section -->
                <div id="content-reviews" class="tab-content hidden">
                    <div class="mt-10 space-y-8">
                        <!-- Rating Overview -->
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h2 class="text-2xl font-bold mb-6 gradient-text flex items-center">
                                <i data-lucide="star" class="w-6 h-6 mr-3 text-yellow-400"></i>
                                Đánh giá bác sĩ
                            </h2>
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="text-center">
                                    <p class="text-4xl font-bold text-blue-600">
                                        {{ $doctor->average_rating ? number_format($doctor->average_rating, 1) : 'Chưa có' }}
                                    </p>
                                    <p class="text-sm text-gray-500">Điểm trung bình</p>
                                </div>
                                <div class="flex-1">
                                    @foreach ([5, 4, 3, 2, 1] as $star)
                                        <div class="flex items-center mb-2">
                                            <span class="w-10 text-sm font-medium">{{ $star }} sao</span>
                                            <div class="flex-1 mx-2 bg-gray-200 rounded-full h-2">
                                                <div class="bg-yellow-400 h-2 rounded-full"
                                                    style="width: {{ $doctor->review_count ? ($ratingBreakdown[$star] / $doctor->review_count) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-500">({{ $ratingBreakdown[$star] }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Danh sách đánh giá -->
                        <div class="space-y-6">
                            @forelse ($doctor->reviews->sortByDesc('created_at') as $review)
                                @php
                                    $editable =
                                        Auth::check() &&
                                        $review->patient_id == Auth::id() &&
                                        \Carbon\Carbon::parse($review->created_at)->diffInMinutes(now()) <= 60;
                                @endphp
                                <div class="border-b pb-6">
                                    <div class="flex items-start space-x-4">
                                        <!-- Avatar + Tên -->
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600">
                                                {{ strtoupper(Str::substr($review->patient->full_name ?? 'A', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-blue-700">
                                                    {{ $review->patient->full_name ?? 'Ẩn danh' }}
                                                </p>
                                                <!-- Dịch vụ khám -->
                                                @if (
                                                    $review->appointment &&
                                                        $review->appointment->service &&
                                                        !in_array(Str::lower($review->appointment->service->name), ['tâm lý', 'sản phụ khoa', 'bệnh xã hội']))
                                                    <p class="text-xs text-gray-500">Dịch vụ:
                                                        {{ $review->appointment->service->name }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <!-- Stars & Date -->
                                            <div class="flex items-center justify-between mb-1">
                                                <div class="flex space-x-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i data-lucide="star"
                                                            class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 fill-none' }}"></i>
                                                    @endfor
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $review->created_at->diffForHumans() }}
                                                </div>
                                            </div>

                                            <!-- Nội dung + form chỉnh sửa -->
                                            @if ($editable && request('edit_review_id') == $review->id)
                                                <form method="POST"
                                                    action="{{ route('reviews.update', ['doctor' => $doctor->id, 'review' => $review->id]) }}"
                                                    class="mt-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="appointment_id"
                                                        value="{{ $review->appointment_id }}">
                                                    <input type="hidden" name="service_id"
                                                        value="{{ $review->service_id }}">
                                                    <input type="hidden" name="tab" value="reviews">
                                                    <div class="mb-2 flex items-center space-x-3">
                                                        <label class="text-sm font-medium">Đánh giá:</label>
                                                        <div class="simple-stars">
                                                            @for ($i = 5; $i >= 1; $i--)
                                                                <input type="radio" name="rating"
                                                                    value="{{ $i }}"
                                                                    id="edit-rating-{{ $i }}"
                                                                    {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                                                                <label for="edit-rating-{{ $i }}">
                                                                    <svg viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2">
                                                                        <polygon
                                                                            points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26">
                                                                        </polygon>
                                                                    </svg>
                                                                </label>
                                                            @endfor
                                                        </div>
                                                    </div>

                                                    <div class="mb-2">
                                                        <textarea name="comment" rows="2" class="w-full border rounded p-2 text-sm" placeholder="Nhận xét của bạn...">{{ old('comment', $review->comment) }}</textarea>
                                                        @error('comment')
                                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="flex space-x-2">
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-blue-600 text-white rounded text-sm font-medium hover:bg-blue-700">
                                                            Lưu
                                                        </button>
                                                        <a href="{{ url()->current() }}?tab=reviews"
                                                            class="px-3 py-1 bg-gray-300 text-gray-700 rounded text-sm font-medium hover:bg-gray-400">
                                                            Hủy
                                                        </a>
                                                    </div>
                                                </form>
                                            @else
                                                <p class="text-gray-700 mb-2">
                                                    @if ($review->comment)
                                                        {{ $review->comment }}
                                                    @else
                                                        {{ $review->created_at }}
                                                    @endif

                                                </p>
                                            @endif

                                            <!-- Hữu ích và chỉnh sửa -->
                                            <div class="flex space-x-4 text-sm text-gray-500 items-center">
                                                @auth
                                                    @if ($review->patient_id != Auth::id())
                                                        <button type="button"
                                                            class="flex items-center space-x-1 hover:text-yellow-500 font-medium bg-transparent border-none p-0 btn-useful"
                                                            data-id="{{ $review->id }}">
                                                            <i data-lucide="thumbs-up" class="w-4 h-4"></i>
                                                            <span>Hữu ích (<span
                                                                    class="useful-count">{{ $review->useful_count ?? 0 }}</span>)</span>
                                                        </button>
                                                    @endif
                                                @endauth
                                                @if ($editable && request('edit_review_id') != $review->id)
                                                    <form method="GET" action="{{ url()->current() }}">
                                                        <input type="hidden" name="edit_review_id"
                                                            value="{{ $review->id }}">
                                                        <input type="hidden" name="tab" value="reviews">
                                                        <button type="submit"
                                                            class="flex items-center space-x-1 hover:text-yellow-500 font-medium bg-transparent border-none p-0">
                                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                                            <span>Chỉnh sửa</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 text-center">
                                    <i data-lucide="star" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                                    <p class="text-gray-500">Chưa có đánh giá nào cho bác sĩ này.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Gửi đánh giá -->
                        @auth
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <h3 class="text-lg font-semibold mb-4">Gửi đánh giá của bạn</h3>

                                @if ($appointmentsToReview->isEmpty())
                                    <p class="text-sm text-gray-500">Bạn không có cuộc hẹn nào đủ điều kiện để đánh giá. Hãy
                                        hoàn tất một cuộc hẹn với bác sĩ để gửi đánh giá.</p>
                                @else
                                    <form action="{{ route('reviews.store', $doctor->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="appointment_id" class="block text-sm font-medium mb-1">Chọn cuộc
                                                hẹn:</label>
                                            <select name="appointment_id" id="appointment_id"
                                                class="w-full border rounded p-2 text-sm" onchange="updateServiceId(this)">
                                                @foreach ($appointmentsToReview as $appt)
                                                    <option value="{{ $appt->id }}"
                                                        data-service-id="{{ $appt->service_id ?? '' }}"
                                                        {{ old('appointment_id') == $appt->id ? 'selected' : '' }}>
                                                        {{ $appt->service->name ?? 'Cuộc hẹn' }} -
                                                        {{ $appt->appointment_time->format('d/m/Y H:i') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('appointment_id')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                            <input type="hidden" name="service_id" id="service_id"
                                                value="{{ old('service_id', $appointmentsToReview->first()->service_id ?? '') }}">
                                        </div>
                                        <div class="mb-4 flex items-center space-x-3">
                                            <label class="text-sm font-medium">Đánh giá:</label>
                                            <div class="simple-stars">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" value="{{ $i }}"
                                                        id="rating-{{ $i }}"
                                                        {{ old('rating') == $i ? 'checked' : '' }} required>
                                                    <label for="rating-{{ $i }}">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <polygon
                                                                points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26">
                                                            </polygon>
                                                        </svg>
                                                    </label>
                                                @endfor
                                            </div>
                                            @error('rating')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="comment" class="block text-sm font-medium mb-1">Nhận xét (không bắt
                                                buộc):</label>
                                            <textarea name="comment" id="comment" rows="3" class="w-full border rounded p-2 text-sm"
                                                placeholder="Nhận xét của bạn...">{{ old('comment') }}</textarea>
                                            @error('comment')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="text-right">
                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium">
                                                Gửi đánh giá
                                            </button>
                                        </div>
                                    </form>
                                @endif

                                <!-- Hiển thị tất cả đánh giá của người dùng -->
                                @if ($userReviews->isNotEmpty())
                                    <div class="mt-6">
                                        <h4 class="text-md font-semibold mb-3">Đánh giá của bạn</h4>
                                        @foreach ($userReviews as $userReview)
                                            @php
                                                $editable =
                                                    Auth::check() &&
                                                    $userReview->patient_id == Auth::id() &&
                                                    \Carbon\Carbon::parse($userReview->created_at)->diffInMinutes(
                                                        now(),
                                                    ) <= 60;
                                            @endphp
                                            <div class="mb-4 p-4 border rounded bg-gray-100">
                                                <p class="text-sm mb-1 text-gray-700">
                                                    Đánh giá: <strong>{{ $userReview->rating }} sao</strong>
                                                    @if ($userReview->appointment && $userReview->appointment->service)
                                                        - Dịch vụ: {{ $userReview->appointment->service->name }}
                                                    @endif
                                                    ({{ $userReview->created_at->format('d/m/Y H:i') }})
                                                </p>
                                                <p class="text-sm text-gray-800 italic">
                                                    "{{ $userReview->comment ?? 'Không có nhận xét' }}"
                                                </p>
                                                @if ($editable)
                                                    <div class="mt-2">
                                                        <a href="{{ url()->current() }}?edit_review_id={{ $userReview->id }}&tab=reviews"
                                                            class="inline-block px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition-colors text-sm font-medium">
                                                            Chỉnh sửa
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <p class="text-sm text-gray-500">Vui lòng đăng nhập để gửi đánh giá.</p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>


            <div class="bg-white rounded-2xl shadow-lg p-8 mt-10">
                <h2 class="text-2xl font-bold mb-6 gradient-text">Bác sĩ cùng chuyên khoa</h2>

                <div class="flex flex-wrap justify-center gap-6">
                    @forelse ($doctor->related_doctors as $related)
                        <a href="{{ route('doctors.show', $related->id) }}"
                            class="block min-w-[280px] max-w-sm flex-1 rounded-3xl overflow-hidden border border-gray-200 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 bg-white">
                            <!-- Ảnh đại diện bác sĩ -->
                            <div class="relative w-full rounded-3xl overflow-hidden">
                                <!-- Nền cong -->
                                <div class="bg-doctor-top relative">
                                    <img src="{{ $related->user->avatar ? asset('storage/' . $related->user->avatar) : asset('images/default-avatar.png') }}"
                                        alt="{{ $related->user->full_name }}"
                                        class="w-full h-80 object-cover object-top mx-auto">
                                    <div
                                        class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-[#f5f8fc] via-[#f5f8fcaa] to-transparent">
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin bác sĩ -->
                            <div class="p-6 text-center space-y-3">
                                <h3 class="text-xl font-semibold text-gray-900"> {{ $related->user->full_name }}</h3>
                                {{-- <p class="text-sm italic text-gray-500"></p> --}}

                                <div class="bg-green-50 text-sm text-gray-700 rounded-xl p-4 space-y-1">
                                    <p>{{ $related->department->name }}</p>
                                </div>

                                <?php
                                // Lấy năm bắt đầu sớm nhất
                                $startYear = $related->experiences->min('start_year');
                                
                                // Xử lý end_year: nếu có null => coi là năm hiện tại
                                $endYears = $related->experiences->map(function ($exp) {
                                    return $exp->end_year ?? now()->year;
                                });
                                
                                // Lấy năm kết thúc muộn nhất
                                $endYear = $endYears->max();
                                
                                // Tính số năm kinh nghiệm
                                $experienceYears = 0;
                                if ($startYear) {
                                    $experienceYears = $endYear - $startYear;
                                }
                                
                                $related->experience_years = $experienceYears > 0 ? $experienceYears : null; ?>

                                <div
                                    class="flex items-center justify-center bg-blue-50 rounded-xl p-3 text-blue-600 font-medium text-sm gap-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M12 8v4l3 3"></path>
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                    <span> {{ $related->experience_years }} năm kinh nghiệm</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500">Không tìm thấy bác sĩ nào cùng chuyên khoa.</p>
                    @endforelse
                </div>
            </div>
        </main>

    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.btn-useful').forEach(btn => {
                    btn.addEventListener('click', function() {
                        let reviewId = this.dataset.id;

                        fetch(`/reviews/${reviewId}/useful`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // update số count ngay tại chỗ
                                    this.querySelector('.useful-count').textContent = data
                                        .new_count;
                                } else {
                                    alert(data.message || 'Có lỗi xảy ra');
                                }
                            })
                            .catch(err => console.error(err));
                    });
                });

                // Add intersection observer for animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, observerOptions);

                // Observe all card elements
                document.querySelectorAll('.card-hover').forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.6s ease-out';
                    observer.observe(card);
                });

                // Add hover effects to achievement cards
                document.querySelectorAll('.group').forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-4px)';
                    });

                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });
            });

            // Initialize Lucide icons
            lucide.createIcons();

            // Tab functionality
            function showTab(tabName) {
                // Hide all content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active class from all tabs
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('border-blue-600', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-600');
                });

                // Show selected content
                document.getElementById(`content-${tabName}`).classList.remove('hidden');

                // Add active class to selected tab
                const activeTab = document.getElementById(`tab-${tabName}`);
                activeTab.classList.remove('border-transparent', 'text-gray-600');
                activeTab.classList.add('border-blue-600', 'text-blue-600');
            }


            // Add fade-in animation on scroll
            function animateOnScroll() {
                const elements = document.querySelectorAll('.fade-in');
                elements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementVisible = 150;

                    if (elementTop < window.innerHeight - elementVisible) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            }

            // Initialize animations
            window.addEventListener('scroll', animateOnScroll);
            window.addEventListener('load', animateOnScroll);

            document.addEventListener('DOMContentLoaded', function() {
                // Kiểm tra xem có hash trong URL không
                const hash = window.location.hash;
                if (hash) {
                    const tabName = hash.replace('#', '');
                    if (document.getElementById(`content-${tabName}`)) {
                        showTab(tabName);
                    }
                }

                // Kiểm tra URL parameter để biết tab nào cần hiển thị
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('edit_review_id') || urlParams.has('tab')) {
                    const tab = urlParams.get('tab') || 'reviews';
                    showTab(tab);
                }
            });

            function showTab(tabName) {
                // Hide all content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active class from all tabs
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('border-blue-600', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-600');
                });

                // Show selected content
                document.getElementById(`content-${tabName}`).classList.remove('hidden');

                // Add active class to selected tab
                const activeTab = document.getElementById(`tab-${tabName}`);
                activeTab.classList.remove('border-transparent', 'text-gray-600');
                activeTab.classList.add('border-blue-600', 'text-blue-600');

                // Cập nhật URL hash để giữ trạng thái tab
                window.history.replaceState(null, null, `#${tabName}`);
            }
        </script>
    @endpush
