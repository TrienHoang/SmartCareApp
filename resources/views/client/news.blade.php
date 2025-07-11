{{-- resources/views/tin-tuc.blade.php --}}
@extends('client.layouts.app')

@section('title', 'Tin Tức Y Tế')

@section('content')
    <div class="min-h-screen">
        {{-- Header Section --}}
        <section class="gradient-bg text-white py-16">
            <div class="container mx-auto px-4">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Tin Tức Y Tế</h1>
                    <p class="text-xl text-blue-100">
                        Cập nhật những thông tin mới nhất về sức khỏe và y tế từ các chuyên gia hàng đầu
                    </p>
                </div>
            </div>
        </section>

        {{-- Main Content --}}
        <section class="py-8 md:py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8">
                    {{-- Main News Content --}}
                    <div class="lg:col-span-2 order-2 lg:order-1">
                        {{-- Featured News --}}
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-4 md:mb-8">
                            <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="Tin tức nổi bật" class="w-full h-48 md:h-64 object-cover">
                            <div class="p-4 md:p-6">
                                <div class="flex items-center mb-3">
                                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">Tin
                                        Nổi Bật</span>
                                    <span class="text-gray-500 text-sm ml-3">15/07/2025</span>
                                </div>
                                <h2 class="text-xl md:text-2xl font-bold mb-3 text-gray-800">
                                    Những Tiến Bộ Mới Nhất Trong Điều Trị Bệnh Tim Mạch
                                </h2>
                                <p class="text-gray-600 mb-4 text-sm md:text-base">
                                    Các nghiên cứu mới nhất cho thấy những phương pháp điều trị hiện đại đang mang lại hiệu
                                    quả cao trong việc chữa trị các bệnh lý tim mạch. Đây là tin vui cho hàng triệu bệnh
                                    nhân...
                                </p>
                                <a href="#"
                                    class="text-blue-600 font-semibold hover:text-blue-800 transition-colors flex items-center">
                                    Đọc Thêm
                                    <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                                </a>
                            </div>
                        </div>

                        {{-- News Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            @php
                                $news = [
                                    [
                                        'id' => 1,
                                        'title' => 'Cách Phòng Ngừa Bệnh Tiểu Đường Hiệu Quả',
                                        'excerpt' =>
                                            'Những biện pháp đơn giản nhưng hiệu quả để phòng ngừa bệnh tiểu đường type 2...',
                                        'category' => 'Nội Khoa',
                                        'date' => '12/07/2025',
                                        'image' =>
                                            'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                                    ],
                                    [
                                        'id' => 2,
                                        'title' => 'Tầm Soát Ung Thư Vú - Tại Sao Quan Trọng?',
                                        'excerpt' =>
                                            'Tầm soát ung thư vú sớm giúp phát hiện bệnh ở giai đoạn đầu, tăng cơ hội chữa khỏi...',
                                        'category' => 'Sản Phụ Khoa',
                                        'date' => '10/07/2025',
                                        'image' =>
                                            'https://images.unsplash.com/photo-1559757194-9f40fcb0cb85?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                                    ],
                                    [
                                        'id' => 3,
                                        'title' => 'Chế Độ Dinh Dưỡng Cho Trẻ Em Trong Mùa Hè',
                                        'excerpt' =>
                                            'Những thực phẩm giàu dinh dưỡng giúp trẻ khỏe mạnh trong mùa hè...',
                                        'category' => 'Nhi Khoa',
                                        'date' => '08/07/2025',
                                        'image' =>
                                            'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                                    ],
                                    [
                                        'id' => 4,
                                        'title' => 'Bí Quyết Chăm Sóc Da Trong Mùa Nắng',
                                        'excerpt' => 'Cách bảo vệ và chăm sóc da hiệu quả khi thời tiết nắng nóng...',
                                        'category' => 'Da Liễu',
                                        'date' => '05/07/2025',
                                        'image' =>
                                            'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                                    ],
                                ];
                            @endphp

                            @foreach ($news as $article)
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow hover-scale">
                                    <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}"
                                        class="w-full h-40 md:h-48 object-cover">
                                    <div class="p-4 md:p-6">
                                        <div class="flex items-center mb-3">
                                            <span
                                                class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">{{ $article['category'] }}</span>
                                            <span class="text-gray-500 text-sm ml-3">{{ $article['date'] }}</span>
                                        </div>
                                        <h3 class="text-lg md:text-xl font-bold mb-3 text-gray-800">{{ $article['title'] }}
                                        </h3>
                                        <p class="text-gray-600 mb-4 text-sm md:text-base">{{ $article['excerpt'] }}</p>
                                        <a href="{{ route('news_detail', $article['id']) }}"
                                            class="text-blue-600 font-semibold hover:text-blue-800 transition-colors flex items-center">
                                            Đọc Thêm
                                            <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        {{-- Pagination --}}
                        <div class="mt-8 md:mt-12 flex justify-center">
                            <nav class="flex items-center space-x-2">
                                <a href="#"
                                    class="px-3 py-2 rounded-lg bg-white shadow hover:bg-gray-50 transition-colors">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                                <a href="#" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold">1</a>
                                <a href="#"
                                    class="px-4 py-2 rounded-lg bg-white shadow hover:bg-gray-50 transition-colors">2</a>
                                <a href="#"
                                    class="px-4 py-2 rounded-lg bg-white shadow hover:bg-gray-50 transition-colors">3</a>
                                <a href="#"
                                    class="px-3 py-2 rounded-lg bg-white shadow hover:bg-gray-50 transition-colors">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            </nav>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="lg:col-span-1 order-1 lg:order-2">
                        {{-- Search Box --}}
                        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 mb-4 md:mb-6">
                            <h3 class="text-lg font-bold mb-4">Tìm Kiếm Tin Tức</h3>
                            <div class="relative">
                                <input type="text" placeholder="Nhập từ khóa..."
                                    class="w-full p-3 border border-gray-300 rounded-lg pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600">
                                    <i data-lucide="search" class="w-5 h-5"></i>
                                </button>
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
                                    <a href="#"
                                        class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <span class="text-gray-700">{{ $category['name'] }}</span>
                                        <span
                                            class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-sm">{{ $category['count'] }}</span>
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
                                            <a href="#"
                                                class="text-gray-800 hover:text-blue-600 transition-colors block font-medium">
                                                {{ $article['title'] }}
                                            </a>
                                            <span class="text-gray-500 text-sm">{{ $article['date'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Quick Booking --}}
                        <div
                            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl shadow-lg p-4 md:p-6">
                            <h3 class="text-lg font-bold mb-4">Đặt Lịch Khám</h3>
                            <p class="text-blue-100 mb-4">
                                Cần tư vấn sức khỏe? Đặt lịch khám ngay với các chuyên gia của chúng tôi.
                            </p>
                            <a href="{{ url('/dat-lich') }}"
                                class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors block text-center">
                                Đặt Lịch Ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Newsletter Section --}}
        <section class="py-8 md:py-16 gradient-bg text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Đăng Ký Nhận Tin Tức</h2>
                <p class="text-lg md:text-xl text-blue-100 mb-6 md:mb-8">
                    Nhận những thông tin y tế mới nhất và hữu ích qua email
                </p>
                <div class="max-w-md mx-auto">
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-0">
                        <input type="email" placeholder="Nhập email của bạn"
                            class="flex-1 p-3 rounded-lg sm:rounded-l-lg sm:rounded-r-none border border-gray-300 focus:border-blue-500 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                        <button
                            class="bg-white text-blue-600 px-6 py-3 rounded-lg sm:rounded-l-none sm:rounded-r-lg font-semibold hover:bg-blue-50 transition-colors whitespace-nowrap">
                            Đăng Ký
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('styles')
    <style>
        /* Fix cho zoom level thấp */
        /* Fix lỗi khoảng trắng thừa khi zoom xuống 25% */

        /* Breakpoint cho màn hình rất nhỏ hoặc zoom thấp */
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
        }

        /* Breakpoint cho zoom rất thấp (25%) */
        @media (max-width: 300px) {
            .gradient-bg {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .gradient-bg h2 {
                font-size: 1.25rem !important;
                margin-bottom: 0.75rem !important;
            }

            .gradient-bg p {
                font-size: 0.875rem !important;
                margin-bottom: 1rem !important;
            }

            .max-w-md {
                max-width: 100% !important;
                padding: 0 0.5rem;
            }

            /* Fix form layout */
            .gradient-bg .flex {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }

            .gradient-bg input,
            .gradient-bg button {
                width: 100% !important;
                border-radius: 0.5rem !important;
            }
        }

        /* Ngăn chặn horizontal scroll */
        .gradient-bg {
            overflow-x: hidden;
        }

        /* Đảm bảo button không bị wrap text */
        .gradient-bg button {
            white-space: nowrap;
            min-width: fit-content;
        }

        /* Fix cho container chính */
        .min-h-screen {
            margin-bottom: 0 !important;
        }

        /* Đảm bảo footer không có margin-top thừa */
        footer {
            margin-top: 0 !important;
        }
    </style>
@endpush
