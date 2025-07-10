{{-- resources/views/home.blade.php --}}
@extends('client.layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <div class="min-h-screen">
        {{-- Hero Section --}}
        <section class="gradient-bg text-white py-20">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="fade-in">
                        <h1 class="text-4xl md:text-6xl font-bold mb-6">
                            Đặt Lịch Khám
                            <span class="block text-blue-200">Dễ Dàng & Nhanh Chóng</span>
                        </h1>
                        <p class="text-xl mb-8 text-blue-100">
                            Hệ thống đặt lịch khám bệnh trực tuyến hiện đại. Chăm sóc sức khỏe của bạn một cách tiện lợi và
                            chuyên nghiệp.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ url('/dat-lich') }}"
                                class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-colors flex items-center justify-center hover-scale">
                                Đặt Lịch Ngay
                                <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                            </a>
                            <a href="{{ url('/dich-vu') }}"
                                class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition-colors hover-scale">
                                Xem Dịch Vụ
                            </a>
                        </div>
                    </div>
                    <div class="fade-in">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                            <h3 class="text-2xl font-bold mb-6">Đặt Lịch Nhanh</h3>
                            <div class="space-y-4">
                                <form method="GET" action="{{ url('/dat-lich') }}" class="space-y-4">
                                    <div>
                                        <label class="block text-sm mb-2">Chọn Chuyên Khoa</label>
                                        <select name="department_id"
                                            class="w-full p-3 rounded-lg bg-white/10 border border-white/30 text-white focus:bg-white/20 focus:text-white"
                                            style="background-color:rgba(255,255,255,0.1); color:#fff;" required>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->id }}" class="text-black bg-white">
                                                    {{ $dept->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-2">Chọn Ngày</label>
                                        <input type="date" name="appointment_date"
                                            class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white"
                                            required />
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-white text-blue-600 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors text-center block">
                                        Tìm Lịch Trống
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4 gradient-text">Tại Sao Chọn SmartCare?</h2>
                    <p class="text-xl text-gray-600">Chúng tôi cam kết mang đến dịch vụ chăm sóc sức khỏe tốt nhất</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @php
                        $features = [
                            [
                                'icon' => 'calendar',
                                'title' => 'Đặt Lịch Dễ Dàng',
                                'desc' => 'Đặt lịch khám chỉ với vài thao tác đơn giản trên website',
                            ],
                            [
                                'icon' => 'users',
                                'title' => 'Đội Ngũ Chuyên Nghiệp',
                                'desc' => 'Bác sĩ giàu kinh nghiệm, tận tâm với nghề',
                            ],
                            [
                                'icon' => 'award',
                                'title' => 'Chất Lượng Cao',
                                'desc' => 'Trang thiết bị hiện đại, dịch vụ chất lượng quốc tế',
                            ],
                            [
                                'icon' => 'clock',
                                'title' => 'Tiết Kiệm Thời Gian',
                                'desc' => 'Không cần xếp hàng, đúng giờ hẹn đã có',
                            ],
                        ];
                    @endphp
                    @foreach ($features as $feature)
                        <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow hover-scale">
                            <i data-lucide="{{ $feature['icon'] }}" class="w-8 h-8 text-blue-600 mb-4"></i>
                            <h3 class="text-xl font-semibold mb-3">{{ $feature['title'] }}</h3>
                            <p class="text-gray-600">{{ $feature['desc'] }}</p>
                        </div>
                    @endforeach
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
