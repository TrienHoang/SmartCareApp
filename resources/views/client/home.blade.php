{{-- resources/views/home.blade.php --}}
@extends('client.layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <div class="min-h-screen">
        {{-- Hero Section --}}
        <section class="relative text-white py-24"
            style="background: linear-gradient(120deg, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.25) 100%), url('{{ asset('admin/assets/img/banner1.jpg') }}') center/cover no-repeat;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/30 via-blue-200/10 to-blue-900/30"></div>

            <div class="container relative z-10 mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="fade-in">
                        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight drop-shadow-lg">
                            Đặt Lịch Khám
                            <span class="block text-white/80">Dễ Dàng &amp; Nhanh Chóng</span>
                        </h1>
                        <p class="text-xl mb-8 text-white/80 max-w-lg">
                            Hệ thống đặt lịch khám bệnh trực tuyến hiện đại. Chăm sóc sức khỏe của bạn một cách tiện lợi và
                            chuyên nghiệp.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ url('/dat-lich') }}"
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
                        <div class="bg-white/40 backdrop-blur-xl rounded-2xl p-8 shadow-xl border border-white/20">
                            <h3 class="text-2xl font-bold mb-6 text-blue-700">Đặt Lịch Nhanh</h3>
                            <form method="GET" action="{{ url('/dat-lich') }}" class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-blue-900">Chọn Chuyên Khoa</label>
                                    <select name="department_id"
                                        class="w-full p-3 rounded-lg bg-white/80 border border-blue-200 text-blue-900 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                        required>
                                        <option value="" disabled selected>-- Chọn chuyên khoa --</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}">
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-blue-900">Chọn Ngày</label>
                                    <input type="date" name="appointment_date"
                                        class="w-full p-3 rounded-lg bg-white/80 border border-blue-200 text-blue-900 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                        required />
                                </div>
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 rounded-lg font-semibold shadow-lg hover:from-blue-600 hover:to-blue-800 transition-all scale-100 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Tìm Lịch Trống
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section class="py-20 bg-gray-50">
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
                                        class="bg-gray-50 p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow hover-scale text-center">
                                        <img src="{{ $doctor->user->avatar ?? asset('images/default-doctor.png') }}"
                                            alt="{{ $doctor->user->full_name }}"
                                            class="w-24 h-24 mx-auto rounded-full mb-4 object-cover">
                                        <h3 class="text-xl font-semibold mb-2">{{ $doctor->user->full_name }}</h3>
                                        <div class="text-blue-600 mb-2">{{ $doctor->department->name ?? 'Chuyên khoa' }}
                                        </div>
                                        <p class="text-gray-600 mb-4">
                                            {{ $doctor->biography ?? 'Bác sĩ tận tâm, giàu kinh nghiệm.' }}</p>
                                        <a href="{{ url('/thong-tin-bac-si/') }}"
                                            class="inline-block bg-blue-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-700 transition-colors">
                                            Xem Hồ Sơ
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
        <section class="py-20 gradient-bg text-white">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                    @php
                        $stats = [
                            ['number' => '10,000+', 'label' => 'Bệnh Nhân Tin Tưởng'],
                            ['number' => '50+', 'label' => 'Bác Sĩ Chuyên Khoa'],
                            ['number' => '15+', 'label' => 'Năm Kinh Nghiệm'],
                            ['number' => '24/7', 'label' => 'Hỗ Trợ Khẩn Cấp'],
                        ];
                    @endphp
                    @foreach ($stats as $stat)
                        <div class="text-center">
                            <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stat['number'] }}</div>
                            <div class="text-blue-200">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Testimonials Section --}}
        <section class="py-20 bg-gray-50">
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
                                    <div class="bg-white p-8 rounded-xl shadow-lg hover:scale-105 transition">
                                        {{-- Stars --}}
                                        <div class="flex items-center mb-4">
                                            @for ($i = 0; $i < $testimonial->rating; $i++)
                                                <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current"></i>
                                            @endfor
                                        </div>

                                        {{-- Nội dung đánh giá --}}
                                        <p class="text-gray-600 mb-6 italic">
                                            "{{ $testimonial->comment }}"
                                        </p>

                                        <div>
                                            {{-- Bệnh nhân (ẩn danh) --}}
                                            <div class="font-semibold">
                                                @if ($testimonial->patient && $testimonial->patient->full_name)
                                                    {{ Str::substr($testimonial->patient->full_name, 0, 1) . '.***' }}
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


        {{-- CTA Section --}}
        <section class="py-20 gradient-bg text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-4xl font-bold mb-6">Sẵn Sàng Đặt Lịch Khám?</h2>
                <p class="text-xl mb-8 text-blue-100">
                    Đừng để sức khỏe chờ đợi. Đặt lịch ngay hôm nay để được chăm sóc tốt nhất!
                </p>
                <a href="{{ url('/dat-lich') }}"
                    class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-colors hover-scale text-lg">
                    Đặt Lịch Khám Ngay
                </a>
            </div>
        </section>
    </div>
@endsection
