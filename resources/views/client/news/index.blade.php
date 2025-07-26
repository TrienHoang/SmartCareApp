{{-- resources/views/tin-tuc.blade.php --}}
@extends('client.layouts.app')

@section('title', 'Tin Tức Y Tế')

@section('content')
    <div class="min-h-screen bg-gray-50">
        {{-- Header Section with improved gradient and animation --}}
        <section
            class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white py-20 lg:py-24">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-blue-900/20"></div>

            {{-- Animated background shapes --}}
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl transform translate-x-32 -translate-y-16">
            </div>
            <div
                class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-300/10 rounded-full blur-3xl transform -translate-x-20 translate-y-20">
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="text-center max-w-4xl mx-auto">
                    <h1
                        class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent leading-tight">
                        Tin Tức Y Tế
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-50 leading-relaxed font-light">
                        Cập nhật những thông tin mới nhất về sức khỏe và y tế từ các chuyên gia hàng đầu
                    </p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <div class="flex items-center text-blue-100">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                            <span>Thông tin chính xác</span>
                        </div>
                        <div class="flex items-center text-blue-100">
                            <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                            <span>Cập nhật liên tục</span>
                        </div>
                        <div class="flex items-center text-blue-100">
                            <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                            <span>Chuyên gia uy tín</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Main Content with improved layout --}}
        <section class="py-12 lg:py-20">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                    {{-- Main News Content --}}
                    <div class="xl:col-span-3 order-2 xl:order-1">
                        {{-- Filter and Sort Bar --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        class="px-4 py-2 bg-blue-600 text-white rounded-full text-sm font-medium hover:bg-blue-700 transition-colors">
                                        Tất cả
                                    </button>

                                    @foreach ($serviceCategories as $category)
                                        <button
                                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">
                                            {{ $category->name }}
                                        </button>
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-600">Sắp xếp:</span>
                                    <select
                                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option>Mới nhất</option>
                                        <option>Phổ biến nhất</option>
                                        <option>Cũ nhất</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        {{-- News Grid with enhanced cards --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            @foreach ($posts as $post)
                                <article
                                    class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300 hover:-translate-y-1">
                                    <div class="relative overflow-hidden">
                                        <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}"
                                            class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                        <div class="absolute top-4 left-4">
                                            <span
                                                class="bg-white/90 backdrop-blur-sm text-blue-700 px-3 py-1.5 rounded-full text-sm font-semibold shadow-sm">
                                                {{ $post->category->name ?? 'Chuyên mục' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="flex items-center gap-4 mb-4 text-sm text-gray-500">
                                            <div class="flex items-center gap-1">
                                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                                <span>{{ $post->created_at->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                                <span>1.2k lượt xem</span>
                                            </div>
                                        </div>
                                        <h3
                                            class="text-xl font-bold mb-3 text-gray-900 group-hover:text-blue-700 transition-colors line-clamp-2">
                                            {{ $post->title }}
                                        </h3>
                                        <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                            {{ Str::limit(strip_tags($post->content), 120) }}
                                        </p>
                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('client.news.show', $post->slug) }}"
                                                class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors group/link">
                                                Đọc Thêm
                                                <i data-lucide="arrow-right"
                                                    class="ml-2 w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                                            </a>
                                            <button class="text-gray-400 hover:text-red-500 transition-colors">
                                                <i data-lucide="heart" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{-- Enhanced Pagination --}}
                        <div class="mt-12 flex justify-center">
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2">
                                {{ $posts->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- Enhanced Sidebar --}}
                    <div class="xl:col-span-1 order-1 xl:order-2 space-y-8">
                        {{-- Search Box with better styling --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-xl font-bold mb-6 flex items-center">
                                <i data-lucide="search" class="w-5 h-5 mr-2 text-blue-600"></i>
                                Tìm Kiếm Tin Tức
                            </h3>
                            <div class="relative">
                                <input type="text" placeholder="Nhập từ khóa tìm kiếm..."
                                    class="w-full p-4 border border-gray-200 rounded-xl pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <button
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors">
                                    <i data-lucide="search" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Categories with modern design --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-xl font-bold mb-6 flex items-center">
                                <i data-lucide="grid-3x3" class="w-5 h-5 mr-2 text-blue-600"></i>
                                Chuyên Mục
                            </h3>
                            <div class="space-y-2">
                                @php
                                    $categories = [
                                        ['name' => 'Nội Khoa', 'count' => 15, 'icon' => 'stethoscope'],
                                        ['name' => 'Ngoại Khoa', 'count' => 12, 'icon' => 'scissors'],
                                        ['name' => 'Sản Phụ Khoa', 'count' => 8, 'icon' => 'baby'],
                                        ['name' => 'Nhi Khoa', 'count' => 10, 'icon' => 'smile'],
                                        ['name' => 'Tim Mạch', 'count' => 6, 'icon' => 'heart'],
                                        ['name' => 'Da Liễu', 'count' => 5, 'icon' => 'shield'],
                                    ];
                                @endphp
                                @foreach ($categories as $category)
                                    <a href="#"
                                        class="group flex items-center justify-between p-4 rounded-xl hover:bg-blue-50 transition-all duration-200 hover:shadow-sm">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                                                <i data-lucide="{{ $category['icon'] }}" class="w-5 h-5 text-blue-600"></i>
                                            </div>
                                            <span class="text-gray-700 font-medium">{{ $category['name'] }}</span>
                                        </div>
                                        <span
                                            class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm font-semibold group-hover:bg-blue-100 group-hover:text-blue-700 transition-colors">
                                            {{ $category['count'] }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Popular Articles with improved design --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-xl font-bold mb-6 flex items-center">
                                <i data-lucide="trending-up" class="w-5 h-5 mr-2 text-blue-600"></i>
                                Bài Viết Phổ Biến
                            </h3>
                            <div class="space-y-4">
                                @php
                                    $popularArticles = [
                                        [
                                            'title' => '10 Thói Quen Tốt Cho Sức Khỏe',
                                            'date' => '01/07/2025',
                                            'views' => '2.1k',
                                        ],
                                        [
                                            'title' => 'Cách Tăng Cường Hệ Miễn Dịch',
                                            'date' => '28/06/2025',
                                            'views' => '1.8k',
                                        ],
                                        [
                                            'title' => 'Chế Độ Ăn Uống Lành Mạnh',
                                            'date' => '25/06/2025',
                                            'views' => '1.5k',
                                        ],
                                        ['title' => 'Tập Thể Dục Đúng Cách', 'date' => '22/06/2025', 'views' => '1.2k'],
                                    ];
                                @endphp
                                @foreach ($popularArticles as $index => $article)
                                    <div
                                        class="group flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="#"
                                                class="text-gray-800 hover:text-blue-600 transition-colors block font-medium leading-tight mb-2 line-clamp-2">
                                                {{ $article['title'] }}
                                            </a>
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                                    {{ $article['date'] }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                                    {{ $article['views'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Enhanced Quick Booking --}}
                        <div
                            class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-purple-700 text-white rounded-2xl shadow-lg">
                            <div class="absolute inset-0 bg-black/10"></div>
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-8 -translate-y-8">
                            </div>
                            <div class="relative z-10 p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                                        <i data-lucide="calendar-plus" class="w-6 h-6"></i>
                                    </div>
                                    <h3 class="text-xl font-bold">Đặt Lịch Khám</h3>
                                </div>
                                <p class="text-blue-50 mb-6 leading-relaxed">
                                    Cần tư vấn sức khỏe? Đặt lịch khám ngay với các chuyên gia của chúng tôi.
                                </p>
                                <a href="{{ url('/dat-lich') }}"
                                    class="group inline-flex items-center justify-center w-full bg-white text-blue-700 px-6 py-4 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-200 hover:shadow-lg">
                                    <span>Đặt Lịch Ngay</span>
                                    <i data-lucide="arrow-right"
                                        class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Newsletter Signup Widget --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-xl font-bold mb-4 flex items-center">
                                <i data-lucide="mail" class="w-5 h-5 mr-2 text-blue-600"></i>
                                Nhận Tin Mới
                            </h3>
                            <p class="text-gray-600 mb-4 text-sm">
                                Đăng ký để nhận thông tin y tế mới nhất qua email
                            </p>
                            <div class="space-y-3">
                                <input type="email" placeholder="Email của bạn"
                                    class="w-full p-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <button
                                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-sm">
                                    Đăng Ký
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Enhanced Newsletter Section --}}
        <section class="relative py-16 lg:py-20 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800"></div>
            <div class="absolute inset-0 bg-black/10"></div>
            <div
                class="absolute top-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl transform -translate-x-32 -translate-y-16">
            </div>
            <div
                class="absolute bottom-0 right-0 w-80 h-80 bg-indigo-300/10 rounded-full blur-3xl transform translate-x-20 translate-y-20">
            </div>

            <div class="container mx-auto px-4 text-center relative z-10">
                <div class="max-w-3xl mx-auto">
                    <h2 class="text-4xl md:text-5xl font-bold mb-6 text-white">
                        Đăng Ký Nhận Tin Tức
                    </h2>
                    <p class="text-xl text-blue-50 mb-8 leading-relaxed">
                        Nhận những thông tin y tế mới nhất và hữu ích qua email từ đội ngũ chuyên gia của chúng tôi
                    </p>
                    <div class="max-w-lg mx-auto">
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-0 bg-white/10 backdrop-blur-sm p-2 rounded-2xl">
                            <input type="email" placeholder="Nhập email của bạn"
                                class="flex-1 p-4 rounded-xl sm:rounded-l-xl sm:rounded-r-none border-0 focus:outline-none focus:ring-2 focus:ring-white/50 text-gray-800 bg-white">
                            <button
                                class="bg-white text-blue-700 px-8 py-4 rounded-xl sm:rounded-l-none sm:rounded-r-xl font-bold hover:bg-blue-50 transition-colors whitespace-nowrap shadow-lg">
                                Đăng Ký Ngay
                            </button>
                        </div>
                        <p class="text-blue-100 text-sm mt-4">
                            Chúng tôi tôn trọng quyền riêng tư của bạn. Hủy đăng ký bất cứ lúc nào.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        /* Line clamp utilities */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Enhanced hover animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Backdrop blur fallback */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .grid.grid-cols-1.lg\\:grid-cols-2 {
                gap: 1.5rem;
            }
        }

        /* Fix for very small screens */
        @media (max-width: 400px) {
            .text-5xl {
                font-size: 2.5rem !important;
            }

            .text-6xl {
                font-size: 3rem !important;
            }

            .py-20 {
                padding-top: 3rem !important;
                padding-bottom: 3rem !important;
            }
        }

        /* Smooth transitions for all interactive elements */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Custom pagination styling */
        .pagination {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .pagination a,
        .pagination span {
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .pagination a {
            color: #6b7280;
            background: white;
            border: 1px solid #e5e7eb;
        }

        .pagination a:hover {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination .active span {
            background: #3b82f6;
            color: white;
            border: 1px solid #3b82f6;
        }
    </style>
@endpush
