{{-- resources/views/about.blade.php --}}
@extends('client.layouts.app')

@section('title', 'Giới thiệu')

@section('content')
    <div class="min-h-screen bg-gray-50 py-16">
        <div class="container mx-auto px-4 space-y-16">

            {{-- Header --}}
            <div class="text-center space-y-4">
                <h1 class="text-5xl font-extrabold text-blue-700 gradient-text">Về Chúng Tôi</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    SmartCare - Đơn vị tiên phong trong ứng dụng công nghệ chăm sóc sức khỏe, mang đến dịch vụ y tế chất
                    lượng cao và tiện lợi cho cộng đồng.
                </p>
            </div>

            {{-- Hero + Image --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-12">
                <div class="space-y-6">
                    <h2 class="text-3xl font-bold text-blue-800">Sứ Mệnh Của Chúng Tôi</h2>
                    <p class="text-lg text-blue-700">Chúng tôi cam kết mang đến dịch vụ chăm sóc sức khỏe toàn diện, chuyên
                        nghiệp và hiện đại nhất. Với phương châm "Sức khỏe là vàng", chúng tôi nỗ lực để trở thành địa chỉ
                        tin cậy của mọi gia đình Việt Nam.</p>
                    <a href="/dich-vu"
                        class="inline-block bg-blue-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-blue-700 transition">
                        Đặt Lịch Khám Ngay
                    </a>
                </div>
                <img src="https://truecarevietnam.com/wp-content/uploads/2023/11/su-menh.jpg" alt="Healthcare illustration"
                    class="rounded-xl shadow-lg w-full object-cover">
            </div>

            {{-- Statistics --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                @php
                    $stats = [
                        ['number' => '15+', 'label' => 'Năm Kinh Nghiệm'],
                        ['number' => '50+', 'label' => 'Bác Sĩ Chuyên Khoa'],
                        ['number' => '10K+', 'label' => 'Bệnh Nhân Tin Tưởng'],
                        ['number' => '99%', 'label' => 'Hài Lòng'],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-4xl font-bold text-blue-600">{{ $stat['number'] }}</h3>
                        <p class="mt-2 text-gray-700">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Core Values --}}
            <div class="space-y-8">
                <h2 class="text-3xl font-bold text-center text-blue-800">Giá Trị Cốt Lõi</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                        $values = [
                            [
                                'icon' => 'fas fa-heart',
                                'title' => 'Tận Tâm',
                                'description' => 'Luôn đặt bệnh nhân làm trung tâm, tận tâm mọi chi tiết.',
                            ],
                            [
                                'icon' => 'fas fa-shield-alt',
                                'title' => 'Chuyên Nghiệp',
                                'description' => 'Đội ngũ y tế chuyên môn cao, chứng chỉ đầy đủ.',
                            ],
                            [
                                'icon' => 'fas fa-bullseye',
                                'title' => 'Chính Xác',
                                'description' => 'Công nghệ hiện đại giúp chẩn đoán và điều trị hiệu quả.',
                            ],
                        ];
                    @endphp
                    @foreach ($values as $value)
                        <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-lg transition text-center">
                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4">
                                <i class="{{ $value['icon'] }} text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-semibold text-blue-800 mb-2">{{ $value['title'] }}</h3>
                            <p class="text-gray-600">{{ $value['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Milestones --}}
            <section class="py-20 bg-blue-50">
                <div class="max-w-5xl mx-auto px-4">
                    <h2 class="text-3xl font-bold text-center text-blue-800 mb-16">Hành Trình Phát Triển</h2>

                    @php
                        $milestones = [
                            [
                                'year' => '2010',
                                'title' => 'Thành lập phòng khám',
                                'desc' => 'Bắt đầu với đội ngũ 5 bác sĩ và 1 cơ sở tại Hà Nội.',
                            ],
                            [
                                'year' => '2015',
                                'title' => 'Mở rộng quy mô',
                                'desc' => 'Tăng số chuyên khoa, mở rộng phòng khám.',
                            ],
                            [
                                'year' => '2017',
                                'title' => 'Số hoá hồ sơ',
                                'desc' => 'Chuyển toàn bộ hồ sơ sang hệ thống điện tử.',
                            ],
                            [
                                'year' => '2018',
                                'title' => 'Đặt lịch trực tuyến',
                                'desc' => 'Cho phép đặt lịch khám qua website.',
                            ],
                            [
                                'year' => '2020',
                                'title' => 'Đạt chuẩn ISO 9001',
                                'desc' => 'Áp dụng tiêu chuẩn quốc tế.',
                            ],
                            ['year' => '2022', 'title' => 'Khám từ xa', 'desc' => 'Triển khai hệ thống Telehealth.'],
                            [
                                'year' => '2023',
                                'title' => '10.000+ bệnh nhân/năm',
                                'desc' => 'Cột mốc phục vụ vượt 10.000 lượt khám.',
                            ],
                            [
                                'year' => '2024',
                                'title' => 'Mở thêm chi nhánh',
                                'desc' => 'Phát triển hệ thống tại TP.HCM và Đà Nẵng.',
                            ],
                            [
                                'year' => '2025',
                                'title' => 'Ứng dụng AI y tế',
                                'desc' => 'Hỗ trợ chẩn đoán bằng trí tuệ nhân tạo.',
                            ],
                        ];
                    @endphp

                    <div class="relative border-l-2 border-blue-400 pl-6 space-y-12">
                        @foreach ($milestones as $index => $m)
                            <div
                                class="relative w-full md:w-1/2 {{ $index % 2 == 0 ? 'md:ml-0' : 'md:ml-auto' }} flex items-start">
                                <div
                                    class="absolute -left-8 top-1 w-6 h-6 rounded-full bg-blue-600 border-4 border-white shadow-md">
                                </div>
                                <div
                                    class="bg-white rounded-xl shadow-md p-6 w-full {{ $index % 2 == 1 ? 'bg-blue-50' : '' }}">
                                    <div class="text-sm text-blue-500 font-semibold mb-1">{{ $m['year'] }}</div>
                                    <h4 class="text-lg font-bold text-blue-800">{{ $m['title'] }}</h4>
                                    <p class="text-gray-600 mt-1">{{ $m['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>



            {{-- Team --}}
            <div class="space-y-8">
                <h2 class="text-3xl font-bold text-center text-blue-800">Đội Ngũ Lãnh Đạo</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @php
                        $team = [
                            [
                                'name' => 'BS. Nguyễn Văn Lâm',
                                'position' => 'Giám đốc Y khoa',
                                'specialty' => 'Nội khoa',
                                'exp' => '20 năm',
                                'image_url' => 'https://cdn.youmed.vn/photos/67c3e343-bbab-46f2-950e-f11b86ecde6e.jpg',
                            ],
                            [
                                'name' => 'BS. Trần Thị Bình',
                                'position' => 'Phó Giám đốc',
                                'specialty' => 'Sản Phụ khoa',
                                'exp' => '18 năm',
                                'image_url' =>
                                    'https://umcclinic.com.vn/Data/Sites/1/News/873/h%C3%ACnh-b%C3%A1c-thu-v%C3%A2n---spk.jpg',
                            ],
                            [
                                'name' => 'BS. Lê Văn Cường',
                                'position' => 'Trưởng khoa Ngoại',
                                'specialty' => 'Ngoại khoa',
                                'exp' => '15 năm',
                                'image_url' => 'https://cdn.youmed.vn/photos/79ed99a9-35ac-4478-8220-94e24748af18.png',
                            ],
                            [
                                'name' => 'BS. Phạm Thị Dung',
                                'position' => 'Trưởng khoa Nhi',
                                'specialty' => 'Nhi khoa',
                                'exp' => '12 năm',
                                'image_url' =>
                                    'https://benhvien22-12.com/wp-content/uploads/2025/06/bs-ck2-hien-768x768.png',
                            ],
                        ];
                    @endphp
                    @foreach ($team as $m)
                        <div
                            class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition p-6 text-center">
                            <img src="{{ $m['image_url'] ?? '/images/team/' . $m['image'] }}" alt="{{ $m['name'] }}"
                                class="w-32 h-32 object-cover rounded-full mx-auto mb-4 border-4 border-blue-200">
                            <h3 class="text-xl font-semibold text-blue-800">{{ $m['name'] }}</h3>
                            <p class="text-blue-600">{{ $m['position'] }}</p>
                            <p class="text-gray-600">{{ $m['specialty'] }}</p>
                            <p class="text-sm text-gray-500">{{ $m['exp'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>


            {{-- Facilities --}}
            <div class="space-y-8">
                <h2 class="text-3xl font-bold text-center text-blue-800">Cơ Sở Vật Chất</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php
                        $facilities = [
                            [
                                'icon' => '🏥',
                                'title' => 'Phòng Khám Hiện Đại',
                                'desc' => 'Trang bị thiết bị y tế tiên tiến',
                            ],
                            [
                                'icon' => '⚕️',
                                'title' => 'Phòng Mổ Vô Trùng',
                                'desc' => 'Tiêu chuẩn quốc tế về an toàn & vệ sinh',
                            ],
                            ['icon' => '🔬', 'title' => 'Máy Chẩn Đoán', 'desc' => 'CT, MRI, X-quang kỹ thuật số'],
                            ['icon' => '🚑', 'title' => 'Phòng Hồi Sức', 'desc' => 'Trang bị cho ca cấp cứu'],
                            ['icon' => '🛋️', 'title' => 'Khu Vực Chờ', 'desc' => 'Không gian thoải mái cho người bệnh'],
                            [
                                'icon' => '🧪',
                                'title' => 'Xét Nghiệm Tại Chỗ',
                                'desc' => 'Triển khai dịch vụ xét nghiệm nhanh và chính xác ngay tại phòng khám',
                            ],
                        ];
                    @endphp
                    @foreach ($facilities as $f)
                        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition text-center">
                            <div class="text-4xl mb-4">{{ $f['icon'] }}</div>
                            <h3 class="text-xl font-semibold text-blue-800 mb-2">{{ $f['title'] }}</h3>
                            <p class="text-gray-600">{{ $f['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Certificates --}}
            {{-- <div class="space-y-8">
                <h2 class="text-3xl font-bold text-center text-blue-800">Chứng Nhận & Giải Thưởng</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @php
                        $certs = [
                            ['title' => 'ISO 9001:2015', 'desc' => 'Chứng nhận chất lượng quốc tế'],
                            ['title' => 'Bộ Y Tế', 'desc' => 'Giấy phép hoạt động hợp pháp'],
                            ['title' => 'Top 10', 'desc' => 'Phòng khám uy tín 2023'],
                            ['title' => '5 Sao', 'desc' => 'Đánh giá từ bệnh nhân'],
                        ];
                    @endphp
                    @foreach ($certs as $c)
                        <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                            <i class="fas fa-award text-blue-600 text-3xl mb-4"></i>
                            <h4 class="font-semibold text-lg mb-2 text-blue-800">{{ $c['title'] }}</h4>
                            <p class="text-gray-600">{{ $c['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div> --}}

            {{-- Contact CTA --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-2xl p-12 text-center space-y-6">
                <h2 class="text-3xl font-bold">Liên Hệ Với Chúng Tôi</h2>
                <p class="text-xl text-blue-100">Sẵn sàng phục vụ 24/7. Liên hệ để được tư vấn và đặt lịch khám!</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-100">
                    <div class="flex items-center justify-center gap-3"><i class="fas fa-map-marker-alt"></i><span>13 Trịnh
                            Văn Bô, Xuân Phương, Hà Nội</span></div>
                    <div class="flex items-center justify-center gap-3"><i class="fas fa-clock"></i><span>T2–T7: 8:00 –
                            17:00</span></div>
                    <div class="flex items-center justify-center gap-3"><i
                            class="fas fa-phone-alt"></i><span>0123.456.789</span></div>
                </div>
                {{-- <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ url('/dat-lich') }}"
                        class="bg-white text-blue-700 px-8 py-4 rounded-full font-semibold hover:bg-blue-100 transition">
                        Đặt Lịch Ngay
                    </a>
                    <a href="{{ url('/lien-he') }}"
                        class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-blue-700 transition">
                        Liên Hệ
                    </a>
                </div> --}}
            </div>

        </div>
    </div>
@endsection
