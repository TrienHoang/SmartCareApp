@extends('client.layouts.app')
@section('title', 'Dịch Vụ')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 gradient-text">Dịch Vụ Y Tế</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Chúng tôi cung cấp đầy đủ các dịch vụ y tế với đội ngũ bác sĩ chuyên khoa giàu kinh nghiệm
                    và trang thiết bị y tế hiện đại nhất.
                </p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
                @php
                    $stats = [
                        ['icon' => 'users', 'number' => '50+', 'label' => 'Bác Sĩ Chuyên Khoa'],
                        ['icon' => 'award', 'number' => '15+', 'label' => 'Năm Kinh Nghiệm'],
                        ['icon' => 'calendar', 'number' => '10K+', 'label' => 'Lượt Khám'],
                        ['icon' => 'clock', 'number' => '24/7', 'label' => 'Hỗ Trợ'],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="bg-white p-6 rounded-xl shadow-lg text-center hover-scale">
                        <div class="text-blue-600 mb-3 flex justify-center">
                            <i data-lucide="{{ $stat['icon'] }}" class="w-8 h-8"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">{{ $stat['number'] }}</div>
                        <div class="text-sm text-gray-600">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Main Services --}}
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 gradient-text">Chuyên Khoa Chính</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php
                        $services = [
                            [
                                'id' => 'noi-khoa',
                                'name' => 'Nội Khoa',
                                'icon' => '🫀',
                                'description' =>
                                    'Chẩn đoán và điều trị các bệnh lý nội khoa như tim mạch, tiêu hóa, hô hấp',
                                'features' => [
                                    'Khám sức khỏe tổng quát',
                                    'Điều trị bệnh tim mạch',
                                    'Điều trị tiểu đường',
                                    'Bệnh lý tiêu hóa',
                                    'Bệnh hô hấp',
                                ],
                                'doctors' => ['BS. Nguyễn Văn An', 'BS. Trần Thị Bình'],
                                'price' => '200.000 - 500.000 VNĐ',
                            ],
                            [
                                'id' => 'ngoai-khoa',
                                'name' => 'Ngoại Khoa',
                                'icon' => '🏥',
                                'description' => 'Phẫu thuật và điều trị các bệnh lý ngoại khoa',
                                'features' => [
                                    'Phẫu thuật tổng hợp',
                                    'Phẫu thuật nội soi',
                                    'Điều trị chấn thương',
                                    'Phẫu thuật thẩm mỹ',
                                    'Phẫu thuật cấp cứu',
                                ],
                                'doctors' => ['BS. Lê Văn Cường', 'BS. Phạm Thị Dung'],
                                'price' => '300.000 - 1.000.000 VNĐ',
                            ],
                            [
                                'id' => 'san-phu-khoa',
                                'name' => 'Sản Phụ Khoa',
                                'icon' => '👶',
                                'description' => 'Chăm sóc sức khỏe phụ nữ trong thai kỳ và các bệnh phụ khoa',
                                'features' => [
                                    'Khám thai định kỳ',
                                    'Siêu âm thai nhi',
                                    'Điều trị vô sinh',
                                    'Phẫu thuật sản khoa',
                                    'Tư vấn kế hoạch hóa gia đình',
                                ],
                                'doctors' => ['BS. Hoàng Thị Em', 'BS. Vũ Văn Phúc'],
                                'price' => '250.000 - 800.000 VNĐ',
                            ],
                            [
                                'id' => 'nhi-khoa',
                                'name' => 'Nhi Khoa',
                                'icon' => '🧸',
                                'description' => 'Chuyên khoa dành cho trẻ em từ sơ sinh đến 16 tuổi',
                                'features' => [
                                    'Khám sức khỏe trẻ em',
                                    'Tiêm chủng đầy đủ',
                                    'Điều trị bệnh nhiễm trùng',
                                    'Tư vấn dinh dưỡng',
                                    'Theo dõi phát triển',
                                ],
                                'doctors' => ['BS. Đỗ Thị Giang', 'BS. Bùi Văn Hải'],
                                'price' => '150.000 - 400.000 VNĐ',
                            ],
                            [
                                'id' => 'tim-mach',
                                'name' => 'Tim Mạch',
                                'icon' => '❤️',
                                'description' => 'Chuyên sâu về các bệnh lý tim mạch và huyết áp',
                                'features' => [
                                    'Siêu âm tim',
                                    'Điện tâm đồ',
                                    'Điều trị tăng huyết áp',
                                    'Bệnh mạch vành',
                                    'Rối loạn nhịp tim',
                                ],
                                'doctors' => ['BS. Ngô Văn Inh', 'BS. Tôn Thị Kim'],
                                'price' => '300.000 - 700.000 VNĐ',
                            ],
                            [
                                'id' => 'da-lieu',
                                'name' => 'Da Liễu',
                                'icon' => '✨',
                                'description' => 'Điều trị các bệnh về da và thẩm mỹ da',
                                'features' => [
                                    'Điều trị mụn trứng cá',
                                    'Điều trị bệnh da',
                                    'Laser điều trị',
                                    'Thẩm mỹ da',
                                    'Tư vấn chăm sóc da',
                                ],
                                'doctors' => ['BS. Cao Thị Lan', 'BS. Đinh Văn Minh'],
                                'price' => '200.000 - 600.000 VNĐ',
                            ],
                        ];
                    @endphp
                    @foreach ($services as $service)
                        <div
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow hover-scale">
                            <div class="p-8">
                                <div class="text-4xl mb-4">{{ $service['icon'] }}</div>
                                <h3 class="text-2xl font-bold mb-3">{{ $service['name'] }}</h3>
                                <p class="text-gray-600 mb-6">{{ $service['description'] }}</p>

                                <div class="mb-6">
                                    <h4 class="font-semibold mb-3">Dịch vụ bao gồm:</h4>
                                    <ul class="space-y-2">
                                        @foreach ($service['features'] as $feature)
                                            <li class="flex items-center text-sm text-gray-600">
                                                <i data-lucide="check-circle"
                                                    class="w-4 h-4 text-green-500 mr-2 flex-shrink-0"></i>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="mb-6">
                                    <h4 class="font-semibold mb-2">Đội ngũ bác sĩ:</h4>
                                    @foreach ($service['doctors'] as $doctor)
                                        <p class="text-sm text-gray-600">{{ $doctor }}</p>
                                    @endforeach
                                </div>

                                <div class="mb-6">
                                    <p class="text-blue-600 font-semibold">Chi phí: {{ $service['price'] }}</p>
                                </div>

                                <a href="#"
                                    class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity flex items-center justify-center">
                                    Đặt Lịch Khám
                                    <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Additional Services --}}
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 gradient-text">Dịch Vụ Bổ Sung</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        $extras = [
                            [
                                'name' => 'Khám Sức Khỏe Tổng Quát',
                                'description' => 'Gói khám sức khỏe định kỳ đầy đủ',
                                'price' => '800.000 - 2.000.000 VNĐ',
                            ],
                            [
                                'name' => 'Xét Nghiệm Y Khoa',
                                'description' => 'Đầy đủ các loại xét nghiệm cơ bản và chuyên sâu',
                                'price' => '100.000 - 500.000 VNĐ',
                            ],
                            [
                                'name' => 'Chẩn Đoán Hình Ảnh',
                                'description' => 'X-quang, CT, MRI, siêu âm',
                                'price' => '200.000 - 1.500.000 VNĐ',
                            ],
                            [
                                'name' => 'Vật Lý Trị Liệu',
                                'description' => 'Phục hồi chức năng và điều trị đau',
                                'price' => '150.000 - 300.000 VNĐ',
                            ],
                        ];
                    @endphp
                    @foreach ($extras as $extra)
                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow hover-scale">
                            <h3 class="text-xl font-bold mb-3">{{ $extra['name'] }}</h3>
                            <p class="text-gray-600 mb-4">{{ $extra['description'] }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-semibold">{{ $extra['price'] }}</span>
                                <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                                    Đặt lịch
                                    <i data-lucide="arrow-right" class="ml-1 w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Process --}}
            <div class="bg-white rounded-xl shadow-lg p-8 mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 gradient-text">Quy Trình Khám Bệnh</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    @php
                        $steps = [
                            ['step' => '1', 'title' => 'Đặt Lịch', 'desc' => 'Đặt lịch online hoặc gọi điện'],
                            ['step' => '2', 'title' => 'Xác Nhận', 'desc' => 'Nhận xác nhận qua điện thoại'],
                            ['step' => '3', 'title' => 'Khám Bệnh', 'desc' => 'Đến khám đúng giờ hẹn'],
                            ['step' => '4', 'title' => 'Theo Dõi', 'desc' => 'Nhận kết quả và tư vấn'],
                        ];
                    @endphp
                    @foreach ($steps as $step)
                        <div class="text-center">
                            <div
                                class="w-16 h-16 gradient-bg text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                                {{ $step['step'] }}
                            </div>
                            <h3 class="text-xl font-semibold mb-2">{{ $step['title'] }}</h3>
                            <p class="text-gray-600">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- CTA --}}
            <div class="gradient-bg text-white rounded-xl p-12 text-center">
                <h2 class="text-3xl font-bold mb-4">Sẵn Sàng Chăm Sóc Sức Khỏe?</h2>
                <p class="text-xl mb-8 text-blue-100">
                    Đặt lịch ngay hôm nay để được tư vấn và khám bệnh với đội ngũ y tế chuyên nghiệp
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#"
                        class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-colors hover-scale">
                        Đặt Lịch Ngay
                    </a>
                    <a href="#"
                        class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition-colors hover-scale">
                        Liên Hệ Tư Vấn
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
