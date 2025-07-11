{{-- resources/views/about.blade.php --}}
@extends('client.layouts.app')

@section('title', 'Giới thiệu')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 gradient-text">Về Chúng Tôi</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    SmartCare - Đơn vị tiên phong trong việc ứng dụng công nghệ vào chăm sóc sức khỏe,
                    mang đến dịch vụ y tế chất lượng cao và tiện lợi cho cộng đồng.
                </p>
            </div>

            {{-- Hero Section --}}
            <div class="gradient-bg text-white rounded-xl p-12 mb-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold mb-6">Sứ Mệnh Của Chúng Tôi</h2>
                        <p class="text-lg mb-6 text-blue-100">
                            Chúng tôi cam kết mang đến dịch vụ chăm sóc sức khỏe toàn diện, chuyên nghiệp
                            và hiện đại nhất. Với phương châm "Sức khỏe là vàng", chúng tôi không ngừng
                            nỗ lực để trở thành địa chỉ tin cậy của mọi gia đình Việt Nam.
                        </p>
                        <a href="{{ url('/dat-lich') }}"
                            class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-colors hover-scale">
                            Đặt Lịch Khám Ngay
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        @php
                            $stats = [
                                ['number' => '15+', 'label' => 'Năm Kinh Nghiệm'],
                                ['number' => '50+', 'label' => 'Bác Sĩ Chuyên Khoa'],
                                ['number' => '10K+', 'label' => 'Bệnh Nhân Tin Tưởng'],
                                ['number' => '99%', 'label' => 'Hài Lòng'],
                            ];
                        @endphp
                        @foreach ($stats as $stat)
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-2">{{ $stat['number'] }}</div>
                                <div class="text-blue-200">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Core Values --}}
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 gradient-text">Giá Trị Cốt Lõi</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                        $values = [
                            [
                                'icon' => 'heart',
                                'title' => 'Tận Tâm',
                                'description' =>
                                    'Chúng tôi luôn đặt bệnh nhân làm trung tâm, tận tâm chăm sóc từng chi tiết nhỏ nhất.',
                            ],
                            [
                                'icon' => 'shield',
                                'title' => 'Chuyên Nghiệp',
                                'description' =>
                                    'Đội ngũ y tế được đào tạo bài bản, có chứng chỉ hành nghề và kinh nghiệm thực tế.',
                            ],
                            [
                                'icon' => 'target',
                                'title' => 'Chính Xác',
                                'description' =>
                                    'Sử dụng công nghệ hiện đại để đảm bảo chẩn đoán chính xác và điều trị hiệu quả.',
                            ],
                        ];
                    @endphp
                    @foreach ($values as $value)
                        <div
                            class="bg-white p-8 rounded-xl shadow-lg text-center hover:shadow-xl transition-shadow hover-scale">
                            <div class="mb-6 flex justify-center">
                                <i data-lucide="{{ $value['icon'] }}" class="w-12 h-12 text-blue-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">{{ $value['title'] }}</h3>
                            <p class="text-gray-600">{{ $value['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Milestones --}}
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 gradient-text">Hành Trình Phát Triển</h2>
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="space-y-8">
                        @php
                            $milestones = [
                                ['year' => '2010', 'event' => 'Thành lập phòng khám với 5 bác sĩ'],
                                ['year' => '2015', 'event' => 'Mở rộng quy mô, bổ sung các chuyên khoa'],
                                ['year' => '2018', 'event' => 'Ra mắt hệ thống đặt lịch trực tuyến'],
                                ['year' => '2020', 'event' => 'Đạt chứng nhận chất lượng ISO 9001'],
                                ['year' => '2023', 'event' => 'Phục vụ hơn 10,000 bệnh nhân mỗi năm'],
                            ];
                        @endphp
                        @foreach ($milestones as $m)
                            <div class="flex items-center space-x-6">
                                <div
                                    class="w-24 h-24 gradient-bg text-white rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                                    {{ $m['year'] }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold mb-2">Năm {{ $m['year'] }}</h3>
                                    <p class="text-gray-600">{{ $m['event'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Leadership Team --}}
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 gradient-text">Đội Ngũ Lãnh Đạo</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @php
                        $team = [
                            [
                                'name' => 'BS. Nguyễn Văn An',
                                'position' => 'Giám đốc Y khoa',
                                'specialty' => 'Nội khoa',
                                'experience' => '20 năm kinh nghiệm',
                                'image' => '👨‍⚕️',
                            ],
                            [
                                'name' => 'BS. Trần Thị Bình',
                                'position' => 'Phó Giám đốc',
                                'specialty' => 'Sản Phụ khoa',
                                'experience' => '18 năm kinh nghiệm',
                                'image' => '👩‍⚕️',
                            ],
                            [
                                'name' => 'BS. Lê Văn Cường',
                                'position' => 'Trưởng khoa Ngoại',
                                'specialty' => 'Ngoại khoa',
                                'experience' => '15 năm kinh nghiệm',
                                'image' => '👨‍⚕️',
                            ],
                            [
                                'name' => 'BS. Phạm Thị Dung',
                                'position' => 'Trưởng khoa Nhi',
                                'specialty' => 'Nhi khoa',
                                'experience' => '12 năm kinh nghiệm',
                                'image' => '👩‍⚕️',
                            ],
                        ];
                    @endphp
                    @foreach ($team as $member)
                        <div
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow hover-scale">
                            <div class="p-8 text-center">
                                <div class="text-6xl mb-4">{{ $member['image'] }}</div>
                                <h3 class="text-xl font-bold mb-2">{{ $member['name'] }}</h3>
                                <p class="text-blue-600 font-semibold mb-1">{{ $member['position'] }}</p>
                                <p class="text-gray-600 mb-2">{{ $member['specialty'] }}</p>
                                <p class="text-sm text-gray-500">{{ $member['experience'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Facilities --}}
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 gradient-text">Cơ Sở Vật Chất</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php
                        $facilities = [
                            [
                                'title' => 'Phòng Khám Hiện Đại',
                                'description' => 'Được trang bị đầy đủ thiết bị y tế tiên tiến',
                                'icon' => '🏥',
                            ],
                            [
                                'title' => 'Phòng Mổ Vô Trùng',
                                'description' => 'Đạt tiêu chuẩn quốc tế về an toàn và vệ sinh',
                                'icon' => '⚕️',
                            ],
                            [
                                'title' => 'Máy Chẩn Đoán',
                                'description' => 'CT, MRI, X-quang kỹ thuật số cao cấp',
                                'icon' => '🔬',
                            ],
                            [
                                'title' => 'Phòng Hồi Sức',
                                'description' => 'Trang bị đầy đủ cho các ca cấp cứu',
                                'icon' => '🚑',
                            ],
                            [
                                'title' => 'Khu Vực Chờ',
                                'description' => 'Không gian thoải mái cho bệnh nhân và gia đình',
                                'icon' => '🛋️',
                            ],
                            [
                                'title' => 'Nhà Thuốc',
                                'description' => 'Cung cấp thuốc chính hãng, đầy đủ các loại',
                                'icon' => '💊',
                            ],
                        ];
                    @endphp
                    @foreach ($facilities as $f)
                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow hover-scale">
                            <div class="text-4xl mb-4">{{ $f['icon'] }}</div>
                            <h3 class="text-xl font-bold mb-3">{{ $f['title'] }}</h3>
                            <p class="text-gray-600">{{ $f['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Certificates --}}
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 gradient-text">Chứng Nhận & Giải Thưởng</h2>
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @php
                            $certs = [
                                ['title' => 'ISO 9001:2015', 'desc' => 'Chứng nhận chất lượng quốc tế'],
                                ['title' => 'Bộ Y Tế', 'desc' => 'Giấy phép hoạt động hợp pháp'],
                                ['title' => 'Top 10', 'desc' => 'Phòng khám uy tín năm 2023'],
                                ['title' => '5 Sao', 'desc' => 'Đánh giá từ bệnh nhân'],
                            ];
                        @endphp
                        @foreach ($certs as $cert)
                            <div class="text-center">
                                <i data-lucide="award" class="w-12 h-12 text-blue-600 mx-auto mb-4"></i>
                                <h3 class="font-bold text-lg mb-2">{{ $cert['title'] }}</h3>
                                <p class="text-gray-600 text-sm">{{ $cert['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Contact CTA --}}
            <div class="gradient-bg text-white rounded-xl p-12 text-center">
                <h2 class="text-3xl font-bold mb-6">Liên Hệ Với Chúng Tôi</h2>
                <p class="text-xl mb-8 text-blue-100">
                    Sẵn sàng phục vụ bạn 24/7. Hãy liên hệ để được tư vấn và đặt lịch khám!
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div class="flex items-center justify-center space-x-3">
                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                        <span>13 P.Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội</span>
                    </div>
                    <div class="flex items-center justify-center space-x-3">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                        <span>T2-T7: 8:00 - 17:00</span>
                    </div>
                    <div class="flex items-center justify-center space-x-3">
                        <i data-lucide="users" class="w-6 h-6"></i>
                        <span>0123.456.789</span>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ url('/dat-lich') }}"
                        class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-colors hover-scale">
                        Đặt Lịch Ngay
                    </a>
                    <a href="{{ url('/lien-he') }}"
                        class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition-colors hover-scale">
                        Liên Hệ
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
