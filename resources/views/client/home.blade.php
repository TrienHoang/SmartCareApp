{{-- resources/views/home.blade.php --}}
@extends('client.layouts.app')

@section('title', 'Trang chủ')

@push('styles')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: white;
            border: 1px solid #bfdbfe;
            border-radius: 0.5rem;
            /* rounded-lg */
            padding: 0.5rem 0.75rem;
            /* px-3 py-2 */
            color: #1e3a8a;
            /* text-blue-900 */
            width: 100%;
            min-height: 44px;
            box-shadow: none;
        }

        .bg-doctor-top {
            background-image: url({{ asset('admin/assets/img/doctor-item-top-bg.png') }});
            background-size: cover;
            background-position: top center;
        }

                .bg-vipro {
            background-image: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.5)), url({{ asset('LayoutClient/img/khambenh.png') }});
            background-size: cover;
            background-position: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50%;
            transform: translateY(-50%);
            right: 10px;
        }

        .select2-container {
            width: 100% !important;
        }

        .typewrite>.wrap {
            border-right: 0.08em solid #fff;
            animation: blink-caret 0.75s step-end infinite;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        /* Animation for cards appearing */
        .grid>div {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .grid>div:nth-child(2) {
            animation-delay: 0.1s;
        }

        .grid>div:nth-child(3) {
            animation-delay: 0.2s;
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

        @keyframes blink-caret {

            from,
            to {
                border-color: transparent
            }

            50% {
                border-color: white;
            }
        }

        .gradient-bg-social {
            background: linear-gradient(135deg, #356fe3 0%, #d8eef3 100%);
        }

        .media-logo {
            transition: all 0.3s ease;
            /* filter: grayscale(100%); */
            opacity: 1;
        }

        .media-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.05);
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            align-items: center;
        }

        @media (max-width: 768px) {
            .media-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        .logo-bg {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section-icon {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 1rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }


    </style>
@endpush

@section('content')
    <div class="min-h-screen">
        {{-- Hero Section --}}
        <section class="relative text-white py-24"
            style="background: linear-gradient(120deg, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.25) 100%), url('{{ asset('admin/assets/img/banner1.jpg') }}') center/cover no-repeat;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/30 via-blue-200/10 to-blue-900/30"></div>

            <div class="container relative z-10 mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="fade-in">
                        <h1 class="text-xl md:text-6xl font-extrabold mb-8 leading-tight drop-shadow-lg">
                            Đặt Lịch Khám
                            <span class="block text-white/80 text-5xl mt-4 typewrite" data-period="2000"
                                data-type='[&quot; Dễ dàng &quot;, &quot;Nhanh chóng &quot;]'>
                                <span class="wrap"></span>
                            </span>
                        </h1>
                        <p class="text-xl mb-8 text-white/80 max-w-lg">
                            Hệ thống đặt lịch khám bệnh trực tuyến hiện đại. Chăm sóc sức khỏe của bạn một cách tiện lợi và
                            chuyên nghiệp.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ url('/dich-vu') }}"
                                class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-8 py-4 rounded-full font-semibold shadow-lg hover:from-blue-600 hover:to-blue-800 transition-all flex items-center justify-center scale-100 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                Đặt Lịch Ngay
                                <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                            </a>
                            <a href="{{ url('/dich-vu') }}"
                                class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-blue-700 transition-all scale-100 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white">
                                Xem Dịch Vụ
                            </a>
                        </div>
                    </div>
                    <div class="fade-in">
                        <div
                            class="bg-white/40 backdrop-blur-xl rounded-2xl p-8 shadow-xl border border-white/20 max-w-xl mx-auto">
                            <h3 class="text-2xl font-bold mb-6 text-blue-700">Tìm Lịch Khám Nhanh</h3>
                            <form method="GET" action="{{ route('home.search') }}" class="space-y-6" id="booking-form">
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-blue-900">Tìm Dịch Vụ</label>
                                    <select name="service_id" id="service_id"
                                        class="w-full p-3 rounded-lg bg-white/80 border border-blue-200 text-blue-900 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                        required>
                                        <option value="" disabled selected>-- Nhập tên dịch vụ --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-blue-900">Chọn Ngày Khám</label>
                                    <input type="date" name="appointment_date" id="appointment_date"
                                        class="w-full p-3 rounded-lg bg-white/80 border border-blue-200 text-blue-900 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                        min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required />
                                </div>
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 rounded-lg font-semibold shadow-lg hover:from-blue-600 hover:to-blue-800 transition-all scale-100 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <span id="button-text">Tìm Lịch Trống</span>
                                    <span id="button-loading" class="hidden">Đang tìm...</span>
                                </button>
                            </form>
                            <!-- Hiển thị kết quả -->
                            <div id="search-results" class="mt-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section class="py-20
        bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4 text-blue-700">Tại Sao Chọn SmartCare?</h2>
                    <p class="text-xl text-gray-600">Chúng tôi cam kết mang đến dịch vụ chăm sóc sức khỏe tốt nhất</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @php
                        $features = [
                            [
                                'icon' => 'fas fa-calendar-check',
                                'title' => 'Đặt Lịch Dễ Dàng',
                                'desc' => 'Đặt lịch khám chỉ với vài thao tác đơn giản trên website',
                            ],
                            [
                                'icon' => 'fas fa-user-doctor',
                                'title' => 'Đội Ngũ Chuyên Nghiệp',
                                'desc' => 'Bác sĩ giàu kinh nghiệm, tận tâm với nghề',
                            ],
                            [
                                'icon' => 'fas fa-stethoscope',
                                'title' => 'Chất Lượng Cao',
                                'desc' => 'Trang thiết bị hiện đại, dịch vụ chất lượng quốc tế',
                            ],
                            [
                                'icon' => 'fas fa-clock',
                                'title' => 'Tiết Kiệm Thời Gian',
                                'desc' => 'Không cần xếp hàng, đúng giờ hẹn đã có',
                            ],
                        ];
                    @endphp
                    @foreach ($features as $feature)
                        <div
                            class="bg-white p-8 rounded-2xl shadow-md hover:shadow-lg hover:-translate-y-1 transition duration-300 text-center border border-blue-100">
                            <div
                                class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 shadow-sm">
                                <i class="{{ $feature['icon'] }} text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">{{ $feature['title'] }}</h3>
                            <p class="text-gray-600 text-sm">{{ $feature['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Doctor Section --}}
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4 gradient-text">Đội Ngũ Bác Sĩ</h2>
                    <p class="text-xl text-gray-600">Gặp gỡ các bác sĩ chuyên khoa giàu kinh nghiệm của chúng tôi</p>
                </div>

                <div id="doctor-slider" class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @forelse ($doctors as $doctor)
                                <li class="splide__slide">
                                    <div
                                        class="doctor-card bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm">
                                        <a href="{{ route('doctors.show', $doctor->id) }}" class="block">
                                            <!-- Doctor Image -->
                                            <div class="relative overflow-hidden bg-doctor-top">
                                                <img src="{{ $doctor->user->avatar ? asset('storage/' . $doctor->user->avatar) : asset('images/default-avatar.png') }}"
                                                    alt="{{ $doctor->user->full_name }}"
                                                    class="w-full h-65 object-cover object-top">
                                                <div
                                                    class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-white/90 to-transparent">
                                                </div>
                                            </div>

                                            <!-- Doctor Info -->
                                            <div class="p-5">
                                                <h3 class="text-xl font-semibold text-gray-900 mb-2 truncate">
                                                    {{ $doctor->user->full_name }}
                                                </h3>

                                                <!-- Department Badge -->
                                                <div class="bg-green-50 border border-green-200 rounded-lg p-2 mb-3">
                                                    <p class="text-green-800 text-sm font-medium text-center">
                                                        {{ $doctor->department->name }}
                                                    </p>
                                                </div>

                                                <!-- Experience & Rating -->
                                                <div class="space-y-2">
                                                    <!-- Experience -->
                                                    <div class="flex items-center bg-blue-50 rounded-lg p-2">
                                                        <i data-lucide="clock" class="w-4 h-4 text-blue-600 mr-2"></i>
                                                        <span class="text-blue-800 text-sm font-medium">
                                                            {{ $doctor->experience_years ?? 0 }} năm kinh nghiệm
                                                        </span>
                                                    </div>

                                                    <!-- Rating -->
                                                    {{-- @if ($doctor->average_rating) --}}
                                                    <div class="flex flex-wrap items-center gap-3 mt-4">
                                                        <div
                                                            class="flex items-center bg-white/10 backdrop-blur-sm rounded-full px-3 py-1">
                                                            @php
                                                                $rating = $doctor->average_rating;
                                                            @endphp

                                                            @if (is_null($rating) || $rating == 0)
                                                                <span class="text-blue-600 text-sm italic">Chưa có
                                                                    đánh giá</span>
                                                            @else
                                                                @php
                                                                    $fullStars = floor($rating);
                                                                    $hasHalfStar = $rating - $fullStars >= 0.5;
                                                                @endphp

                                                                @for ($i = 1; $i <= $fullStars; $i++)
                                                                    <i data-lucide="star"
                                                                        class="w-4 h-4 text-yellow-300 fill-yellow-300 mr-1"></i>
                                                                @endfor

                                                                @if ($hasHalfStar)
                                                                    <i data-lucide="star-half"
                                                                        class="w-4 h-4 text-yellow-300 fill-yellow-300 mr-1"></i>
                                                                @endif

                                                                @for ($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++)
                                                                    <i data-lucide="star"
                                                                        class="w-4 h-4 text-gray-400 mr-1"></i>
                                                                @endfor

                                                                <span
                                                                    class="text-blue-600 text-sm ml-1">({{ number_format($rating, 1) }}/5)</span>
                                                                {{-- @endif --}}
                                                        </div>
                                                    </div>
                            @endif
                    </div>
                </div>
                </a>
            </div>
            </li>
        @empty
            <li class="splide__slide">
                <div class="text-gray-500 text-center">Chưa có thông tin bác sĩ.</div>
            </li>
            @endforelse
            </ul>
    </div>
    </div>
    </div>
    </section>

    {{-- Stats Section --}}
    <section class="py-20 bg-vipro text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold mb-2">
                        <span class="counter" data-number="10000" data-step="300">0</span>+
                    </div>
                    <div class="text-white">Bệnh nhân tin tưởng</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold mb-2">
                        <span class="counter" data-number="50" data-step="1">0</span>+
                    </div>
                    <div class="text-white">Bác sĩ chuyên khoa</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold mb-2">
                        <span class="counter" data-number="15" data-step="1">0</span>+
                    </div>
                    <div class="text-white">Năm kinh nghiệm</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold mb-2">
                        <span>24/7</span>
                    </div>
                    <div class="text-white">Hỗ Trợ Khẩn Cấp</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section class=" bg-gray-50">
        <div class="container mx-auto px-4 pt-12">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <h2
                        class="text-5xl font-bold mb-6 bg-gradient-to-r from-blue-600 via-purple-600 to-cyan-600 bg-clip-text text-transparent">
                        Dịch Vụ Của Chúng Tôi
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                        Các dịch vụ chăm sóc sức khỏe đa dạng và chuyên nghiệp với đội ngũ bác sĩ giàu kinh nghiệm
                    </p>
                </div>

                <!-- Services Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach ($dich_vu as $item)
                        @if ($item->service)
                            <div
                                class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                                <!-- Image Container with Overlay -->
                                <div class="relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->service->image) }}"
                                        alt="{{ $item->service->name }}"
                                        class="w-full h-56 object-cover transition-transform duration-700 group-hover:scale-110">

                                    <!-- Gradient Overlay -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>

                                    <!-- Popular Badge -->
                                    @if ($item->total_bookings > 50)
                                        <div
                                            class="absolute top-4 left-4 bg-gradient-to-r from-orange-400 to-pink-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-fire mr-1"></i> Phổ biến
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <!-- Service Name -->
                                    <h3
                                        class="text-xl font-bold mb-3 text-gray-800 group-hover:text-blue-600 transition-colors duration-300 line-clamp-2">
                                        {{ $item->service->name }}
                                    </h3>

                                    <!-- Description -->
                                    <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                        {{ $item->service->description }}
                                    </p>

                                    <!-- Stats Row -->
                                    <div class="flex items-center justify-between mb-4 text-sm">
                                        <div class="flex items-center text-gray-500">
                                            <i class="fas fa-calendar-check mr-2 text-blue-500"></i>
                                            <span>{{ $item->total_bookings }} lượt đặt</span>
                                        </div>
                                        <div class="flex items-center text-yellow-500">
                                            <i class="fas fa-star mr-1"></i>
                                            <span class="text-gray-600">
                                                {{ $item->service->reviews_avg_rating ? number_format($item->service->reviews_avg_rating, 1) : 'Chưa có' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="mb-6">
                                        <div class="flex items-baseline">
                                            <span class="text-2xl font-bold text-blue-600">
                                                {{ number_format($item->service->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-gray-500 ml-2">VNĐ</span>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <a href="{{ route('booking.showService', ['service_id' => $item->service->id]) }}"
                                        class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-3 px-6 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300">
                                        <i class="fas fa-calendar-plus mr-2"></i>
                                        Đặt Lịch Ngay
                                    </a>
                                </div>

                                <!-- Decorative Elements -->
                                <div
                                    class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-100 to-transparent rounded-bl-full opacity-50">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <!-- Call to Action -->
                <div class="text-center">
                    <a href="{{ '/dich-vu' }}"
                        class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-th-large mr-3"></i>
                        Xem Tất Cả Dịch Vụ
                        <i class="fas fa-arrow-right ml-3"></i>
                    </a>
                </div>
    </section>


    {{-- Testimonials Section --}}
    <section class="pb-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 gradient-text">Ý Kiến Bệnh Nhân</h2>
                <p class="text-xl text-gray-600">Những chia sẻ chân thực từ bệnh nhân</p>
            </div>

            <div id="testimonial-slider" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        @forelse ($testimonials as $testimonial)
                            <li class="splide__slide">
                                <div class="bg-white p-5 rounded-xl shadow-lg hover:scale-105 transition">
                                    {{-- Stars --}}
                                    <div class="flex items-center mb-4">
                                        @for ($i = 0; $i < $testimonial->rating; $i++)
                                            <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current"></i>
                                        @endfor
                                    </div>

                                    {{-- Nội dung đánh giá --}}
                                    <p class="text-gray-600 mb-6 italic">
                                        {{ $testimonial->comment ?? 'Đánh giá này chưa có nội dung.' }}
                                    </p>

                                    <div>
                                        {{-- Bệnh nhân (ẩn danh) --}}
                                        <div class="font-semibold">
                                            @if ($testimonial->patient && $testimonial->patient->full_name)
                                                {{ Str::substr($testimonial->patient->full_name, 0, 5) . '.***' }}
                                            @else
                                                Bệnh nhân
                                            @endif
                                        </div>

                                        {{-- Bác sĩ --}}
                                        <div class="text-sm text-gray-500">
                                            @if ($testimonial->doctor && $testimonial->doctor->user->full_name)
                                                Bác sĩ: {{ $testimonial->doctor->user->full_name }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="splide__slide">
                                <div class="text-gray-500">Chưa có đánh giá nào.</div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </section>

    </div>
    <section class="py-20 gradient-bg-social">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-white mb-4">Truyền thông nói về SmartCare</h2>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Video Section -->
                <div class="order-2 lg:order-1">
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/FyDQljKtWnI" title="SmartCare trên VTV1"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <!-- Media Logos Section -->
                <div class="order-1 lg:order-2">

                    <div class="media-grid">
                        <!-- VnExpress -->
                        <div class="logo-bg media-logo">
                            <img src="{{ asset('LayoutClient/img/vnexpress.png') }}" alt="VnExpress"
                                class="h-8 object-contain">
                        </div>

                        <!-- Sức khỏe đời sống -->
                        <div class="logo-bg media-logo">
                            <img src="{{ asset('LayoutClient/img/suckhoedoisong.png') }}" alt="Sức khỏe đời sống"
                                class="h-8 object-contain">
                        </div>

                        <!-- VietnamNet -->
                        <div class="logo-bg media-logo">
                            <img src="{{ asset('LayoutClient/img/logo-vnnet.png') }}" alt="VietnamNet"
                                class="h-8 object-contain">
                        </div>

                        <!-- VTV1 -->
                        <div class="logo-bg media-logo">
                            <img src="{{ asset('LayoutClient/img/vtv1.png') }}" alt="VTV1"
                                class="h-8 object-contain">
                        </div>

                        <!-- VTC News -->
                        <div class="logo-bg media-logo">
                            <img src="{{ asset('LayoutClient/img/vtcnewslogosvg.png') }}" alt="VTC News"
                                class="h-8 object-contain">
                        </div>

                        <!-- VnExpress (second instance) -->
                        <div class="logo-bg media-logo">
                            <img src="{{ asset('LayoutClient/img/vnexpress.png') }}" alt="VnExpress"
                                class="h-8 object-contain">
                        </div>

                        <!-- VTV1 (second instance) -->
                        <div class="logo-bg media-logo">
                            <img src="{{ asset('LayoutClient/img/vtv1.png') }}" alt="VTV1"
                                class="h-8 object-contain">
                        </div>

                        <!-- Dân trí -->
                        <div class="logo-bg media-logo">
                            <img src="{{ asset('LayoutClient/img/dantrilogo.png') }}" alt="Dân trí"
                                class="h-8 object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            class TxtType {
                constructor(el, toRotate, period) {
                    this.toRotate = toRotate;
                    this.el = el;
                    this.loopNum = 0;
                    this.period = parseInt(period, 10) || 2000;
                    this.txt = '';
                    this.isDeleting = false;
                    this.tick();
                }

                tick() {
                    const i = this.loopNum % this.toRotate.length;
                    const fullTxt = this.toRotate[i];

                    this.txt = this.isDeleting ?
                        fullTxt.substring(0, this.txt.length - 1) :
                        fullTxt.substring(0, this.txt.length + 1);

                    this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';

                    let delta = 200 - Math.random() * 100;
                    if (this.isDeleting) delta /= 2;

                    if (!this.isDeleting && this.txt === fullTxt) {
                        delta = this.period;
                        this.isDeleting = true;
                    } else if (this.isDeleting && this.txt === '') {
                        this.isDeleting = false;
                        this.loopNum++;
                        delta = 500;
                    }

                    setTimeout(() => this.tick(), delta);
                }
            }

            window.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll('.typewrite').forEach(el => {
                    const toRotate = el.getAttribute('data-type');
                    const period = el.getAttribute('data-period');
                    if (toRotate) {
                        new TxtType(el, JSON.parse(toRotate), period);
                    }
                });
            });

            // Khởi tạo Select2 cho tìm kiếm dịch vụ
            $('#service_id').select2({
                placeholder: '-- Nhập tên dịch vụ --',
                allowClear: true,
                minimumInputLength: 1,
                width: '100%',
                ajax: {
                    url: '/home/search-services',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term || ''
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.services.map(service => ({
                                id: service.id,
                                text: `${service.name} (${service.duration} phút)`
                            }))
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                templateResult: function(service) {
                    if (!service.id) return service.text;
                    return $('<span>' + service.text + '</span>');
                },
                templateSelection: function(service) {
                    return service.text || '-- Chọn dịch vụ --';
                }
            });

            const form = document.getElementById('booking-form');
            const buttonText = document.getElementById('button-text');
            const buttonLoading = document.getElementById('button-loading');
            const searchResults = document.getElementById('search-results');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                buttonText.classList.add('hidden');
                buttonLoading.classList.remove('hidden');
                searchResults.innerHTML = '';

                const formData = new FormData(form);
                const queryString = new URLSearchParams(formData).toString();
                fetch(`${form.action}?${queryString}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');

                        if (data.success) {
                            if (data.has_slots) {
                                searchResults.innerHTML = `
                        <p class="text-green-600 font-semibold mb-3">Có khung giờ trống cho ngày ${data.date_formatted}!</p>
                        <a href="${data.booking_url}" class="inline-block bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold shadow hover:bg-blue-700 transition-all">
                            Đặt lịch ngay
                        </a>
                    `;
                            } else {
                                searchResults.innerHTML =
                                    '<p class="text-red-600">Không có khung giờ trống trong ngày này.</p>';
                            }
                        } else {
                            searchResults.innerHTML = `<p class="text-red-600">${data.message}</p>`;
                        }
                    })
                    .catch(error => {
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');
                        searchResults.innerHTML = '<p class="text-red-600">Lỗi khi tìm lịch trống.</p>';
                        console.error('Error:', error);
                    });
            });
        });
    </script>
@endpush
