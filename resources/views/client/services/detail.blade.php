@extends('client.layouts.app')

@section('title', $service->name)

@section('content')
    <div class="min-h-screen bg-gray-50">
        {{-- Hero Section --}}
        <div class="bg-gradient-to-br from-teal-400 via-teal-500 to-emerald-600 text-white py-20">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    {{-- Breadcrumb --}}
                    <nav class="flex items-center mb-8">
                        <ol class="flex items-center space-x-2 text-teal-100">
                            <li>
                                <a href="{{ route('client.services') }}" class="hover:text-white transition-colors">
                                    Dịch vụ
                                </a>
                            </li>
                            <li class="flex items-center">
                                <i data-lucide="chevron-right" class="w-4 h-4 mx-2"></i>
                                {{-- <a href="{{ route('client.services.category', $service->category->id ?? '#') }}"  --}}
                                   class="hover:text-white transition-colors">
                                    {{ $service->category->name ?? 'Không có danh mục' }}
                                </a>
                            </li>
                            <li class="flex items-center">
                                <i data-lucide="chevron-right" class="w-4 h-4 mx-2"></i>
                                <span class="text-white font-medium">{{ $service->name }}</span>
                            </li>
                        </ol>
                    </nav>

                    {{-- Service Header --}}
                    <div class="flex flex-col lg:flex-row items-start gap-8">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                    <i data-lucide="stethoscope" class="w-8 h-8 text-white"></i>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center space-x-1 bg-yellow-400/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                        <i data-lucide="star" class="w-4 h-4 text-yellow-300 fill-current"></i>
                                        <span class="text-sm text-yellow-100 font-medium">4.9</span>
                                    </div>
                                    <span class="text-teal-100 text-sm">Đánh giá cao</span>
                                </div>
                            </div>

                            <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">{{ $service->name }}</h1>
                            
                            {{-- Quick Info --}}
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                                <div class="bg-white/15 rounded-2xl p-4 backdrop-blur-sm border border-white/10">
                                    <div class="text-2xl font-bold">{{ number_format($service->price, 0, ',', '.') }}₫</div>
                                    <div class="text-sm text-teal-100">Giá khám</div>
                                </div>
                                @if($service->duration)
                                <div class="bg-white/15 rounded-2xl p-4 backdrop-blur-sm border border-white/10">
                                    <div class="text-2xl font-bold">{{ $service->duration }}</div>
                                    <div class="text-sm text-teal-100">Phút</div>
                                </div>
                                @endif
                                <div class="bg-white/15 rounded-2xl p-4 backdrop-blur-sm border border-white/10">
                                    <div class="text-2xl font-bold">24/7</div>
                                    <div class="text-sm text-teal-100">Hỗ trợ</div>
                                </div>
                            </div>

                            {{-- CTA Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button class="bg-white text-teal-600 px-8 py-4 rounded-2xl font-bold hover:bg-gray-50 transition-all duration-300 flex items-center justify-center text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i data-lucide="calendar-plus" class="w-6 h-6 mr-3"></i>
                                    Đặt lịch khám ngay
                                </button>
                                <button class="border-2 border-white text-white px-8 py-4 rounded-2xl font-bold hover:bg-white hover:text-teal-600 transition-all duration-300 flex items-center justify-center text-lg">
                                    <i data-lucide="phone" class="w-6 h-6 mr-3"></i>
                                    Gọi tư vấn
                                </button>
                            </div>
                        </div>

                        {{-- Service Image --}}
                        @if($service->image)
                        <div class="lg:w-1/3">
                            <div class="relative overflow-hidden rounded-3xl shadow-2xl">
                                <img src="{{ asset('storage/' . $service->image) }}" 
                                     alt="{{ $service->name }}" 
                                     class="w-full h-80 object-cover transform hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="container mx-auto px-4 py-20">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-12">
                        {{-- Description --}}
                        @if($service->description)
                        <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
                                    <i data-lucide="info" class="w-6 h-6 text-teal-600"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Tổng quan dịch vụ</h2>
                            </div>
                            <p class="text-gray-700 leading-relaxed text-lg">{{ $service->description }}</p>
                        </div>
                        @endif

                        {{-- Content --}}
                        @if($service->content)
                        <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-6 h-6 text-emerald-600"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Nội dung chi tiết</h2>
                            </div>
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                {!! $service->content !!}
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-8">
                        {{-- Service Info Card --}}
                        <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100 sticky top-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Thông tin dịch vụ</h3>
                            
                            <div class="space-y-6">
                                {{-- Category --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="folder" class="w-5 h-5 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 font-medium">Chuyên khoa</p>
                                        <p class="text-gray-900 font-semibold">{{ $service->category->name ?? 'Không có danh mục' }}</p>
                                    </div>
                                </div>

                                {{-- Price --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="dollar-sign" class="w-5 h-5 text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 font-medium">Giá khám</p>
                                        <p class="text-2xl font-bold text-teal-600">{{ number_format($service->price, 0, ',', '.') }}₫</p>
                                    </div>
                                </div>

                                {{-- Duration --}}
                                @if($service->duration)
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="clock" class="w-5 h-5 text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 font-medium">Thời gian thực hiện</p>
                                        <p class="text-gray-900 font-semibold">{{ $service->duration }} phút</p>
                                    </div>
                                </div>
                                @endif

                                {{-- Features --}}
                                <div class="pt-4 border-t border-gray-100">
                                    <p class="text-sm text-gray-500 font-medium mb-3">Đặc điểm nổi bật</p>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                                            <span class="text-sm text-gray-700">Chuyên nghiệp</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                                            <span class="text-sm text-gray-700">Hiện đại</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                                            <span class="text-sm text-gray-700">An toàn</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                                            <span class="text-sm text-gray-700">Hiệu quả</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Button --}}
                            <button class="w-full bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white py-4 px-6 rounded-2xl font-bold transition-all duration-300 transform hover:scale-105 flex items-center justify-center text-lg shadow-lg hover:shadow-xl mt-8">
                                <i data-lucide="calendar-plus" class="w-6 h-6 mr-3"></i>
                                Đặt lịch ngay
                            </button>
                        </div>

                        {{-- Contact Card --}}
                        <div class="bg-gradient-to-br from-teal-500 to-emerald-600 rounded-3xl p-8 text-white">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="headphones" class="w-8 h-8"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-3">Hỗ trợ 24/7</h3>
                                <p class="text-teal-50 mb-6 text-sm">
                                    Đội ngũ chuyên viên sẵn sàng tư vấn và hỗ trợ bạn mọi lúc
                                </p>
                                <div class="space-y-3">
                                    <a href="tel:+84123456789" 
                                       class="w-full bg-white text-teal-600 py-3 px-4 rounded-xl font-semibold hover:bg-gray-50 transition-colors flex items-center justify-center text-sm">
                                        <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                                        0123 456 789
                                    </a>
                                    <a href="#" 
                                       class="w-full border border-white text-white py-3 px-4 rounded-xl font-semibold hover:bg-white hover:text-teal-600 transition-colors flex items-center justify-center text-sm">
                                        <i data-lucide="message-circle" class="w-4 h-4 mr-2"></i>
                                        Chat ngay
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Back Button --}}
                <div class="text-center mt-16">
                    {{-- <a href="{{ route('client.services.category', $service->category->id ?? '#') }}" --}}
                        class="inline-flex items-center px-8 py-4 bg-gray-100 text-gray-700 rounded-2xl hover:bg-gray-200 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i data-lucide="arrow-left" class="w-6 h-6 mr-3"></i>
                        Quay lại {{ $service->category->name ?? 'danh mục' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        .prose h1, .prose h2, .prose h3 {
            color: #1f2937;
            font-weight: 700;
        }
        
        .prose p {
            margin-bottom: 1rem;
            line-height: 1.75;
        }
        
        .prose ul, .prose ol {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }
        
        .prose li {
            margin-bottom: 0.5rem;
        }
        
        .prose strong {
            color: #0d9488;
            font-weight: 600;
        }

        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #14b8a6;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0d9488;
        }
    </style>
@endsection