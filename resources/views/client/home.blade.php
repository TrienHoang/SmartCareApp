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
                        Hệ thống đặt lịch khám bệnh trực tuyến hiện đại. Chăm sóc sức khỏe của bạn một cách tiện lợi và chuyên nghiệp.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ url('/dat-lich') }}" class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-colors flex items-center justify-center hover-scale">
                            Đặt Lịch Ngay
                            <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                        </a>
                        <a href="{{ url('/dich-vu') }}" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition-colors hover-scale">
                            Xem Dịch Vụ
                        </a>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                        <h3 class="text-2xl font-bold mb-6">Đặt Lịch Nhanh</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm mb-2">Chọn Chuyên Khoa</label>
                                <select class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white">
                                    <option>Nội Khoa</option>
                                    <option>Ngoại Khoa</option>
                                    <option>Sản Phụ Khoa</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm mb-2">Chọn Ngày</label>
                                <input type="date" class="w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white" />
                            </div>
                            <a href="{{ url('/dat-lich') }}" class="w-full bg-white text-blue-600 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors text-center block">
                                Tìm Lịch Trống
                            </a>
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
                        ['icon' => 'calendar', 'title' => 'Đặt Lịch Dễ Dàng', 'desc' => 'Đặt lịch khám chỉ với vài thao tác đơn giản trên website'],
                        ['icon' => 'users', 'title' => 'Đội Ngũ Chuyên Nghiệp', 'desc' => 'Bác sĩ giàu kinh nghiệm, tận tâm với nghề'],
                        ['icon' => 'award', 'title' => 'Chất Lượng Cao', 'desc' => 'Trang thiết bị hiện đại, dịch vụ chất lượng quốc tế'],
                        ['icon' => 'clock', 'title' => 'Tiết Kiệm Thời Gian', 'desc' => 'Không cần xếp hàng, đúng giờ hẹn đã có'],
                    ];
                @endphp
                @foreach($features as $feature)
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
                @foreach($stats as $stat)
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stat['number'] }}</div>
                        <div class="text-blue-200">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 gradient-text">Chuyên Khoa Nổi Bật</h2>
                <p class="text-xl text-gray-600">Đa dạng các chuyên khoa với đội ngũ bác sĩ giàu kinh nghiệm</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $services = [
                        ['name' => 'Nội Khoa', 'icon' => '🫀', 'desc' => 'Chẩn đoán và điều trị các bệnh nội khoa'],
                        ['name' => 'Ngoại Khoa', 'icon' => '🏥', 'desc' => 'Phẫu thuật và điều trị ngoại khoa'],
                        ['name' => 'Sản Phụ Khoa', 'icon' => '👶', 'desc' => 'Chăm sóc sức khỏe phụ nữ và trẻ em'],
                        ['name' => 'Nhi Khoa', 'icon' => '🧸', 'desc' => 'Chuyên khoa dành cho trẻ em'],
                        ['name' => 'Tim Mạch', 'icon' => '❤️', 'desc' => 'Chẩn đoán và điều trị bệnh tim mạch'],
                        ['name' => 'Da Liễu', 'icon' => '✨', 'desc' => 'Điều trị các bệnh về da'],
                    ];
                @endphp
                @foreach($services as $service)
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow hover-scale">
                        <div class="text-4xl mb-4">{{ $service['icon'] }}</div>
                        <h3 class="text-xl font-semibold mb-3">{{ $service['name'] }}</h3>
                        <p class="text-gray-600 mb-4">{{ $service['desc'] }}</p>
                        <a href="{{ url('/dat-lich') }}" class="text-blue-600 font-semibold hover:text-blue-800 transition-colors flex items-center">
                            Đặt Lịch
                            <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                        </a>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $testimonials = [
                        ['name' => 'Nguyễn Thị Lan', 'role' => 'Bệnh nhân', 'content' => 'Dịch vụ rất tốt, bác sĩ tận tâm. Đặt lịch online rất tiện lợi!', 'rating' => 5],
                        ['name' => 'Trần Văn Nam', 'role' => 'Bệnh nhân', 'content' => 'Cơ sở vật chất hiện đại, nhân viên nhiệt tình. Rất hài lòng!', 'rating' => 5],
                        ['name' => 'Lê Thị Hoa', 'role' => 'Bệnh nhân', 'content' => 'Đặt lịch dễ dàng, không phải chờ đợi lâu. Sẽ quay lại!', 'rating' => 5],
                    ];
                @endphp
                @foreach($testimonials as $testimonial)
                    <div class="bg-white p-8 rounded-xl shadow-lg hover-scale">
                        <div class="flex items-center mb-4">
                            @for($i = 0; $i < $testimonial['rating']; $i++)
                                <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current"></i>
                            @endfor
                        </div>
                        <p class="text-gray-600 mb-6 italic">"{{ $testimonial['content'] }}"</p>
                        <div>
                            <div class="font-semibold">{{ $testimonial['name'] }}</div>
                            <div class="text-sm text-gray-500">{{ $testimonial['role'] }}</div>
                        </div>
                    </div>
                @endforeach
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
            <a href="{{ url('/dat-lich') }}" class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-colors hover-scale text-lg">
                Đặt Lịch Khám Ngay
            </a>
        </div>
    </section>
</div>
@endsection