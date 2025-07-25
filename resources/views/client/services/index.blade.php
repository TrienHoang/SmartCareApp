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
            
            {{-- Main Service Categories --}}
            <div class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 gradient-text">Chuyên Khoa Chính</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 mx-auto rounded-full mb-6"></div>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Khám phá các chuyên khoa y tế hàng đầu với đội ngũ bác sĩ chuyên môn cao
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($categories as $category)
                        <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                            {{-- Card Header với gradient overlay --}}
                            <div class="relative bg-gradient-to-br from-blue-50 to-indigo-50 p-8 pb-6">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full opacity-20 -mr-16 -mt-16"></div>
                                <div class="relative z-10">
                                    {{-- Icon container với animation --}}
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                        @php
                                            // Mapping icon theo tên danh mục
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
                                                'Dinh dưỡng' => 'apple'
                                            ];
                                            
                                            // Lấy icon phù hợp hoặc dùng icon mặc định
                                            $icon = $iconMap[$category->name] ?? 'stethoscope';
                                        @endphp
                                        <i data-lucide="{{ $icon }}" class="w-8 h-8 text-white"></i>
                                    </div>
                                    
                                    {{-- Category name --}}
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                        {{ $category->name }}
                                    </h3>
                                </div>
                            </div>
                            
                            {{-- Card Body --}}
                            <div class="p-8 pt-6">
                                {{-- Description --}}
                                <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                    {{ $category->description }}
                                </p>
                                
                                {{-- Features list (nếu có) --}}
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 text-sm rounded-full font-medium">
                                        Tư vấn miễn phí
                                    </span>
                                    <span class="px-3 py-1 bg-green-50 text-green-600 text-sm rounded-full font-medium">
                                        Thiết bị hiện đại
                                    </span>
                                </div>
                                
                                {{-- CTA Button --}}
                                <a href="{{ route('client.services.show', ['id' => $category->id]) }}"
                                    class="group/btn w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white py-4 px-6 rounded-xl font-semibold flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-lg relative overflow-hidden">
                                    
                                    {{-- Button background animation --}}
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></div>
                                    
                                    {{-- Button content --}}
                                    <div class="relative flex items-center">
                                        <span class="mr-2">Xem Dịch Vụ</span>
                                        <i data-lucide="arrow-right" class="w-5 h-5 transition-transform group-hover/btn:translate-x-1"></i>
                                    </div>
                                </a>
                            </div>
                            
                            {{-- Hover effect overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-indigo-600/0 group-hover:from-blue-500/5 group-hover:to-indigo-600/5 transition-all duration-300 pointer-events-none"></div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Call to Action Section --}}
                <div class="text-center mt-16">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl p-8 text-white">
                        <h3 class="text-2xl font-bold mb-4">Cần hỗ trợ tư vấn?</h3>
                        <p class="text-blue-100 mb-6 max-w-2xl mx-auto">
                            Đội ngũ chuyên viên y tế của chúng tôi sẵn sàng hỗ trợ bạn 24/7 để giải đáp mọi thắc mắc
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="tel:+84123456789" class="bg-white text-blue-600 px-8 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-colors flex items-center justify-center">
                                <i data-lucide="phone" class="w-5 h-5 mr-2"></i>
                                Gọi ngay: 0123 456 789
                            </a>
                            <a href="#" class="border-2 border-white text-white px-8 py-3 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-colors flex items-center justify-center">
                                <i data-lucide="calendar" class="w-5 h-5 mr-2"></i>
                                Đặt lịch hẹn
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: translateY(-4px);
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
        
        .group:hover .group-hover\:text-blue-600 {
            color: #2563eb;
        }
        
        .group/btn:hover .group-hover\/btn\:translate-x-1 {
            transform: translateX(0.25rem);
        }
        
        .group/btn:hover .group-hover\/btn\:translate-x-full {
            transform: translateX(100%);
        }
    </style>
@endsection