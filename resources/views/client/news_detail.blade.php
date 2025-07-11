@extends('client.layouts.app')

@section('title', 'Những Tiến Bộ Mới Nhất Trong Điều Trị Bệnh Tim Mạch')

@section('content')
    <div class="min-h-screen">
        {{-- Header Section --}}
        <section class="gradient-bg text-white py-8 md:py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center space-x-2 mb-4">
                    <a href="{{ url('/') }}" class="text-blue-100 hover:text-white transition-colors">
                        <i data-lucide="home" class="w-4 h-4"></i>
                    </a>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-blue-200"></i>
                    <a href="{{ url('/tin-tuc') }}" class="text-blue-100 hover:text-white transition-colors">
                        Tin Tức
                    </a>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-blue-200"></i>
                    <span class="text-white">Chi Tiết Tin Tức</span>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center mb-4">
                        <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">Tin Nổi Bật</span>
                        <span class="text-blue-100 text-sm ml-3">15/07/2025</span>
                    </div>
                    <h1 class="text-2xl md:text-4xl font-bold mb-4">
                        Những Tiến Bộ Mới Nhất Trong Điều Trị Bệnh Tim Mạch
                    </h1>
                    <p class="text-lg md:text-xl text-blue-100 max-w-3xl mx-auto">
                        Khám phá những phương pháp điều trị tiên tiến đang mang lại hy vọng mới cho hàng triệu bệnh nhân tim mạch trên toàn thế giới
                    </p>
                </div>
            </div>
        </section>

        {{-- Main Content --}}
        <section class="py-8 md:py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8">
                    {{-- Article Content --}}
                    <div class="lg:col-span-2 order-2 lg:order-1">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            {{-- Featured Image --}}
                            <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="Những Tiến Bộ Mới Nhất Trong Điều Trị Bệnh Tim Mạch" 
                                class="w-full h-48 md:h-80 object-cover">
                            
                            {{-- Article Body --}}
                            <div class="p-6 md:p-8">
                                {{-- Article Meta --}}
                                <div class="flex items-center justify-between mb-6 pb-4 border-b">
                                    <div class="flex items-center space-x-4">
                                        <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=50&q=80" 
                                            alt="Tác giả" class="w-12 h-12 rounded-full">
                                        <div>
                                            <p class="font-semibold text-gray-800">BS. Nguyễn Thị Lan</p>
                                            <p class="text-sm text-gray-500">Chuyên khoa Tim Mạch</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Thời gian đọc: 8 phút</p>
                                        <p class="text-sm text-gray-500">Lượt xem: 1,234</p>
                                    </div>
                                </div>

                                {{-- Article Content --}}
                                <div class="prose max-w-none">
                                    <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                                        Trong những năm gần đây, y học tim mạch đã chứng kiến những bước tiến vượt bậc với sự ra đời của nhiều phương pháp điều trị mới. Những tiến bộ này không chỉ mang lại hy vọng cho hàng triệu bệnh nhân mà còn thay đổi cách tiếp cận trong điều trị các bệnh lý tim mạch.
                                    </p>

                                    <h2 class="text-2xl font-bold text-gray-800 mb-4 mt-8">1. Phẫu Thuật Tim Ít Xâm Lấn</h2>
                                    <p class="text-gray-700 mb-4 leading-relaxed">
                                        Phẫu thuật tim ít xâm lấn (Minimally Invasive Cardiac Surgery - MICS) đã trở thành xu hướng chính trong điều trị các bệnh lý tim mạch. Thay vì phải mở ngực lớn như phương pháp truyền thống, các bác sĩ giờ đây có thể thực hiện phẫu thuật qua những vết rẻ nhỏ.
                                    </p>
                                    <p class="text-gray-700 mb-6 leading-relaxed">
                                        Những lợi ích nổi bật của phương pháp này bao gồm:
                                    </p>
                                    <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                                        <li>Giảm đau sau phẫu thuật</li>
                                        <li>Thời gian hồi phục nhanh hơn</li>
                                        <li>Giảm nguy cơ nhiễm trùng</li>
                                        <li>Sẹo mổ nhỏ, thẩm mỹ cao</li>
                                    </ul>

                                    <h2 class="text-2xl font-bold text-gray-800 mb-4 mt-8">2. Liệu Pháp Tế Bào Gốc</h2>
                                    <p class="text-gray-700 mb-4 leading-relaxed">
                                        Liệu pháp tế bào gốc đang mở ra một chương mới trong điều trị bệnh tim mạch. Các nghiên cứu cho thấy tế bào gốc có thể giúp tái tạo mô tim bị tổn thương, đặc biệt hiệu quả trong điều trị suy tim và sau nhồi máu cơ tim.
                                    </p>

                                    <div class="bg-blue-50 p-6 rounded-lg mb-6">
                                        <h3 class="text-lg font-bold text-blue-800 mb-2">Lưu Ý Quan Trọng</h3>
                                        <p class="text-blue-700">
                                            Mặc dù liệu pháp tế bào gốc rất hứa hẹn, nhưng vẫn đang trong giai đoạn nghiên cứu và chưa được áp dụng rộng rãi. Bệnh nhân cần tham khảo ý kiến bác sĩ chuyên khoa để được tư vấn phù hợp.
                                        </p>
                                    </div>

                                    <h2 class="text-2xl font-bold text-gray-800 mb-4 mt-8">3. Công Nghệ AI Trong Chẩn Đoán</h2>
                                    <p class="text-gray-700 mb-4 leading-relaxed">
                                        Trí tuệ nhân tạo (AI) đang cách mạng hóa việc chẩn đoán bệnh tim mạch. Các thuật toán AI có thể phân tích hình ảnh siêu âm tim, điện tâm đồ và CT/MRI với độ chính xác cao, giúp phát hiện sớm các bệnh lý tim mạch.
                                    </p>

                                    <h2 class="text-2xl font-bold text-gray-800 mb-4 mt-8">4. Thiết Bị Y Tế Thông Minh</h2>
                                    <p class="text-gray-700 mb-4 leading-relaxed">
                                        Các thiết bị y tế thông minh như máy tạo nhịp tim không dây, van tim nhân tạo thế hệ mới đang giúp cải thiện chất lượng cuộc sống của bệnh nhân một cách đáng kể.
                                    </p>

                                    <h2 class="text-2xl font-bold text-gray-800 mb-4 mt-8">Kết Luận</h2>
                                    <p class="text-gray-700 mb-4 leading-relaxed">
                                        Những tiến bộ trong điều trị bệnh tim mạch đang mang lại hy vọng mới cho hàng triệu bệnh nhân. Tuy nhiên, việc áp dụng các phương pháp mới cần được thực hiện dưới sự giám sát của các chuyên gia y tế có kinh nghiệm.
                                    </p>
                                    <p class="text-gray-700 mb-6 leading-relaxed">
                                        Nếu bạn hoặc người thân có các vấn đề về tim mạch, hãy tham khảo ý kiến bác sĩ chuyên khoa để được tư vấn phù hợp và có thể tiếp cận với các phương pháp điều trị tiên tiến nhất.
                                    </p>
                                </div>

                                {{-- Article Footer --}}
                                <div class="mt-8 pt-6 border-t">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <span class="text-gray-600">Chia sẻ:</span>
                                            <a href="#" class="text-blue-600 hover:text-blue-800 transition-colors">
                                                <i data-lucide="facebook" class="w-5 h-5"></i>
                                            </a>
                                            <a href="#" class="text-blue-400 hover:text-blue-600 transition-colors">
                                                <i data-lucide="twitter" class="w-5 h-5"></i>
                                            </a>
                                            <a href="#" class="text-blue-700 hover:text-blue-900 transition-colors">
                                                <i data-lucide="linkedin" class="w-5 h-5"></i>
                                            </a>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors">
                                                <i data-lucide="heart" class="w-5 h-5"></i>
                                                <span>245</span>
                                            </button>
                                            <button class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors">
                                                <i data-lucide="bookmark" class="w-5 h-5"></i>
                                                <span>Lưu</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Related Articles --}}
                        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold mb-6 text-gray-800">Bài Viết Liên Quan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @php
                                    $relatedArticles = [
                                        [
                                            'title' => 'Cách Phòng Ngừa Bệnh Tiểu Đường Hiệu Quả',
                                            'excerpt' => 'Những biện pháp đơn giản nhưng hiệu quả để phòng ngừa bệnh tiểu đường type 2...',
                                            'category' => 'Nội Khoa',
                                            'date' => '12/07/2025',
                                            'image' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
                                        ],
                                        [
                                            'title' => 'Tầm Soát Ung Thư Vú - Tại Sao Quan Trọng?',
                                            'excerpt' => 'Tầm soát ung thư vú sớm giúp phát hiện bệnh ở giai đoạn đầu, tăng cơ hội chữa khỏi...',
                                            'category' => 'Sản Phụ Khoa',
                                            'date' => '10/07/2025',
                                            'image' => 'https://images.unsplash.com/photo-1559757194-9f40fcb0cb85?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
                                        ]
                                    ];
                                @endphp
                                
                                @foreach ($relatedArticles as $article)
                                    <div class="flex space-x-4">
                                        <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" 
                                            class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                                        <div>
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs font-semibold">{{ $article['category'] }}</span>
                                                <span class="text-gray-500 text-xs">{{ $article['date'] }}</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 mb-1 line-clamp-2">
                                                <a href="#" class="hover:text-blue-600 transition-colors">{{ $article['title'] }}</a>
                                            </h4>
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ $article['excerpt'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="lg:col-span-1 order-1 lg:order-2">
                        {{-- Quick Navigation --}}
                        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 mb-4 md:mb-6">
                            <h3 class="text-lg font-bold mb-4">Mục Lục</h3>
                            <div class="space-y-2">
                                <a href="#" class="block text-gray-700 hover:text-blue-600 transition-colors py-2 border-b border-gray-100">
                                    1. Phẫu Thuật Tim Ít Xâm Lấn
                                </a>
                                <a href="#" class="block text-gray-700 hover:text-blue-600 transition-colors py-2 border-b border-gray-100">
                                    2. Liệu Pháp Tế Bào Gốc
                                </a>
                                <a href="#" class="block text-gray-700 hover:text-blue-600 transition-colors py-2 border-b border-gray-100">
                                    3. Công Nghệ AI Trong Chẩn Đoán
                                </a>
                                <a href="#" class="block text-gray-700 hover:text-blue-600 transition-colors py-2">
                                    4. Thiết Bị Y Tế Thông Minh
                                </a>
                            </div>
                        </div>

                        {{-- Categories --}}
                        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 mb-4 md:mb-6">
                            <h3 class="text-lg font-bold mb-4">Chuyên Mục</h3>
                            <div class="space-y-2">
                                @php
                                    $categories = [
                                        ['name' => 'Nội Khoa', 'count' => 15],
                                        ['name' => 'Ngoại Khoa', 'count' => 12],
                                        ['name' => 'Sản Phụ Khoa', 'count' => 8],
                                        ['name' => 'Nhi Khoa', 'count' => 10],
                                        ['name' => 'Tim Mạch', 'count' => 6],
                                        ['name' => 'Da Liễu', 'count' => 5],
                                    ];
                                @endphp
                                @foreach ($categories as $category)
                                    <a href="#" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <span class="text-gray-700">{{ $category['name'] }}</span>
                                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-sm">{{ $category['count'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Popular Articles --}}
                        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 mb-4 md:mb-6">
                            <h3 class="text-lg font-bold mb-4">Bài Viết Phổ Biến</h3>
                            <div class="space-y-4">
                                @php
                                    $popularArticles = [
                                        ['title' => '10 Thói Quen Tốt Cho Sức Khỏe', 'date' => '01/07/2025'],
                                        ['title' => 'Cách Tăng Cường Hệ Miễn Dịch', 'date' => '28/06/2025'],
                                        ['title' => 'Chế Độ Ăn Uống Lành Mạnh', 'date' => '25/06/2025'],
                                        ['title' => 'Tập Thể Dục Đúng Cách', 'date' => '22/06/2025'],
                                    ];
                                @endphp
                                @foreach ($popularArticles as $article)
                                    <div class="flex items-start space-x-3">
                                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 flex-shrink-0"></div>
                                        <div>
                                            <a href="#" class="text-gray-800 hover:text-blue-600 transition-colors block font-medium">
                                                {{ $article['title'] }}
                                            </a>
                                            <span class="text-gray-500 text-sm">{{ $article['date'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Quick Booking --}}
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl shadow-lg p-4 md:p-6">
                            <h3 class="text-lg font-bold mb-4">Cần Tư Vấn?</h3>
                            <p class="text-blue-100 mb-4">
                                Bạn có thắc mắc về bài viết? Đặt lịch tư vấn với chuyên gia tim mạch ngay.
                            </p>
                            <a href="{{ url('/dat-lich') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors block text-center">
                                Đặt Lịch Tư Vấn
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        .prose {
            line-height: 1.8;
        }
        
        .prose h2 {
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0.5rem;
        }
        
        .prose ul {
            padding-left: 1.5rem;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Fix cho zoom level thấp */
        @media (max-width: 400px) {
            .gradient-bg {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }

            .container {
                max-width: 100% !important;
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .prose {
                font-size: 0.875rem;
            }

            .prose h2 {
                font-size: 1.25rem;
            }
        }

        /* Breakpoint cho zoom rất thấp (25%) */
        @media (max-width: 300px) {
            .gradient-bg {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .gradient-bg h1 {
                font-size: 1.5rem !important;
                margin-bottom: 0.75rem !important;
            }

            .gradient-bg p {
                font-size: 0.875rem !important;
            }

            .prose {
                font-size: 0.75rem;
            }

            .prose h2 {
                font-size: 1.125rem;
            }
        }
    </style>
@endpush