@extends('client.layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        {{-- Hero Section --}}
        <div class="bg-gradient-to-br from-sky-400 via-sky-500 to-blue-600 text-white py-20">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    {{-- Breadcrumb --}}
                    <nav class="flex justify-center mb-8">
                        <ol class="flex items-center space-x-2 text-sky-100">
                            <li class="flex items-center">
                                <i data-lucide="chevron-right" class="w-4 h-4 mx-2"></i>
                                <a href="{{ route('client.services') }}"
                                    class="hover:text-white transition-colors duration-200">
                                    Dịch vụ
                                </a>
                            </li>
                            <li class="flex items-center">
                                <i data-lucide="chevron-right" class="w-4 h-4 mx-2"></i>
                                <span class="text-white font-medium">{{ $category->name }}</span>
                            </li>
                        </ol>
                    </nav>

                    {{-- Category Icon --}}
                    <div
                        class="w-24 h-24 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-8 backdrop-blur-sm border border-white/10">
                        @php
                            $iconMap = [
                                'Nội khoa' => 'stethoscope',
                                'Ngoại khoa' => 'scissors',
                                'Sản phụ khoa' => 'baby',
                                'Nhi khoa' => 'heart',
                                'Mắt' => 'eye',
                                'Tai Mũi Họng' => 'ear',
                                'Răng Hàm Mặt' => 'smile',
                                'Da liễu' => 'user',
                                'Thần kinh' => 'brain',
                                'Tim mạch' => 'heart-pulse',
                                'Xương khớp' => 'bone',
                                'Tiêu hóa' => 'pill',
                                'Hô hấp' => 'lungs',
                                'Thận - Tiết niệu' => 'droplets',
                                'Nội tiết' => 'activity',
                                'Tâm thần' => 'brain-circuit',
                                'Ung bướu' => 'shield',
                                'Chẩn đoán hình ảnh' => 'scan',
                                'Xét nghiệm' => 'test-tube',
                                'Cấp cứu' => 'ambulance',
                                'Phục hồi chức năng' => 'refresh-ccw',
                                'Dinh dưỡng' => 'apple',
                            ];
                            $icon = $iconMap[$category->name] ?? 'stethoscope';
                        @endphp
                        <i data-lucide="{{ $icon }}" class="w-12 h-12 text-white"></i>
                    </div>

                    {{-- Category Title --}}
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">{{ $category->name }}</h1>

                    {{-- Category Description --}}
                    @if ($category->description)
                        <p class="text-xl text-sky-50 max-w-3xl mx-auto mb-12 leading-relaxed">
                            {{ $category->description }}
                        </p>
                    @endif

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 max-w-4xl mx-auto">
                        <div
                            class="bg-white/15 rounded-2xl p-6 backdrop-blur-sm border border-white/10 hover:bg-white/20 transition-all duration-300">
                            <div class="text-3xl font-bold mb-2">{{ $category->services->count() }}</div>
                            <div class="text-sm text-sky-100">Dịch vụ</div>
                        </div>
                        <div
                            class="bg-white/15 rounded-2xl p-6 backdrop-blur-sm border border-white/10 hover:bg-white/20 transition-all duration-300">
                            <div class="text-3xl font-bold mb-2">24/7</div>
                            <div class="text-sm text-sky-100">Hỗ trợ</div>
                        </div>
                        <div
                            class="bg-white/15 rounded-2xl p-6 backdrop-blur-sm border border-white/10 hover:bg-white/20 transition-all duration-300">
                            <div class="text-3xl font-bold mb-2">15+</div>
                            <div class="text-sm text-sky-100">Năm KN</div>
                        </div>
                        <div
                            class="bg-white/15 rounded-2xl p-6 backdrop-blur-sm border border-white/10 hover:bg-white/20 transition-all duration-300">
                            <div class="text-3xl font-bold mb-2">100%</div>
                            <div class="text-sm text-sky-100">Tận tâm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Services Section --}}
        <div class="container mx-auto px-4 py-20">
            @if ($category->services->isEmpty())
                {{-- Empty State --}}
                <div class="text-center py-20">
                    <div
                        class="w-32 h-32 bg-sky-50 rounded-full flex items-center justify-center mx-auto mb-8 border-4 border-sky-100">
                        <i data-lucide="calendar-x" class="w-16 h-16 text-sky-400"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">Chưa có dịch vụ</h3>
                    <p class="text-gray-600 mb-10 max-w-lg mx-auto text-lg leading-relaxed">
                        Danh mục này hiện chưa có dịch vụ nào. Vui lòng quay lại sau hoặc liên hệ với chúng tôi để biết thêm
                        thông tin.
                    </p>
                    <a href="{{ route('client.services') }}"
                        class="inline-flex items-center px-8 py-4 bg-sky-500 text-white rounded-2xl hover:bg-sky-600 transition-all duration-300 text-lg font-semibold shadow-lg hover:shadow-xl">
                        <i data-lucide="arrow-left" class="w-6 h-6 mr-3"></i>
                        Quay lại danh mục
                    </a>
                </div>
            @else
                {{-- Services Grid --}}
                <div class="mb-16">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Các Dịch Vụ Có Sẵn</h2>
                        <p class="text-gray-600 text-lg max-w-2xl mx-auto">Khám phá danh sách đầy đủ các dịch vụ chất lượng
                            cao trong chuyên khoa {{ $category->name }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                        @foreach ($category->services as $service)
                            <div
                                class="group bg-white rounded-3xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                                <a href="{{ route('booking.showService', $service->id) }}" class="block">
                                    {{-- Service Header --}}
                                    <div class="p-8 pb-4">
                                        <div class="flex items-start justify-between mb-6">
                                            <div
                                                class="w-16 h-16 bg-gradient-to-br from-sky-400 to-sky-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                                <i data-lucide="check-circle" class="w-8 h-8 text-white"></i>
                                            </div>
                                            <div class="flex items-center space-x-1 bg-yellow-50 px-3 py-1 rounded-full">
                                                <i data-lucide="star" class="w-4 h-4 text-yellow-500 fill-current"></i>
                                                <span class="text-sm text-yellow-700 font-medium">
                                                    {{ $service->reviews_avg_rating ? number_format($service->reviews_avg_rating, 1) : 'Chưa có' }}
                                                </span>
                                            </div>
                                        </div>

                                        <h3
                                            class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-sky-600 transition-colors duration-300 leading-tight">
                                            {{ $service->name }}
                                        </h3>

                                        <div class="flex items-center justify-between mb-4">
                                            <span class="text-2xl font-bold text-sky-600">
                                                {{ number_format($service->price, 0, ',', '.') }}₫
                                            </span>
                                            @if ($service->duration)
                                                <span
                                                    class="text-sm text-gray-500 flex items-center bg-gray-50 px-3 py-1 rounded-full">
                                                    <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                                    {{ $service->duration }}
                                                </span>
                                            @endif
                                        </div>

                                        @if ($service->description)
                                            <p class="text-gray-600 line-clamp-3 mb-6 leading-relaxed">
                                                {{ $service->description }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Service Features --}}
                                    <div class="px-8 pb-6">
                                        <div class="flex flex-wrap gap-2 mb-6">
                                            <span
                                                class="px-4 py-2 bg-green-50 text-green-700 text-sm rounded-full font-medium border border-green-100">
                                                Chuyên nghiệp
                                            </span>
                                            <span
                                                class="px-4 py-2 bg-sky-50 text-sky-700 text-sm rounded-full font-medium border border-sky-100">
                                                Hiện đại
                                            </span>
                                        </div>
                                    </div>
                                </a>

                                {{-- Service Footer --}}
                                <div class="px-8 pb-8">
                                    <a href="{{ route('booking.showService', $service->id) }}"
                                        class="w-full bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white py-4 px-6 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center group/btn shadow-lg hover:shadow-xl text-lg">
                                        <span class="mr-3">Xem chi tiết</span>
                                        <i data-lucide="calendar-plus"
                                            class="w-6 h-6 group-hover/btn:translate-x-1 transition-transform duration-300"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Contact Section --}}
            <div class="bg-gradient-to-r from-sky-500 to-sky-600 rounded-3xl p-12 text-white text-center shadow-2xl">
                <h3 class="text-3xl font-bold mb-6">Cần tư vấn thêm?</h3>
                <p class="text-sky-50 mb-10 max-w-3xl mx-auto text-lg leading-relaxed">
                    Đội ngũ chuyên viên giàu kinh nghiệm của chúng tôi sẵn sàng hỗ trợ bạn lựa chọn dịch vụ phù hợp nhất với
                    nhu cầu của bạn
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="tel:+84123456789"
                        class="bg-white text-sky-600 px-8 py-4 rounded-2xl font-bold hover:bg-gray-50 transition-all duration-300 flex items-center justify-center text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i data-lucide="phone" class="w-6 h-6 mr-3"></i>
                        Hotline: 0123 456 789
                    </a>
                    <a href="#"
                        class="border-2 border-white text-white px-8 py-4 rounded-2xl font-bold hover:bg-white hover:text-sky-600 transition-all duration-300 flex items-center justify-center text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i data-lucide="message-circle" class="w-6 h-6 mr-3"></i>
                        Chat tư vấn
                    </a>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="text-center mt-16">
                <a href="{{ route('client.services') }}"
                    class="inline-flex items-center px-8 py-4 bg-gray-100 text-gray-700 rounded-2xl hover:bg-gray-200 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i data-lucide="arrow-left" class="w-6 h-6 mr-3"></i>
                    Quay lại danh mục dịch vụ
                </a>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        .group:hover .group-hover\:text-sky-600 {
            color: #4eb5e8;
        }

        .group/btn:hover .group-hover\/btn\:translate-x-1 {
            transform: translateX(0.25rem);
        }

        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
        }

        /* Smooth animations */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #2657aa;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #065ab9;
        }
    </style>
@endsection
