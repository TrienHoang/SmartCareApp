@extends('client.layouts.app')

@section('content')
    <div class="bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen">
        <!-- Article Header Section -->
        <div class="bg-white shadow-lg border-b border-gray-100">
            <div class="container mx-auto px-4 py-6 lg:py-10">
                <!-- Breadcrumb với animation -->
                <nav class="mb-8" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="{{ route('home') }}"
                                class="text-gray-500 hover:text-blue-600 transition-all duration-300 hover:underline flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                                Trang chủ
                            </a>
                        </li>
                        <li>
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </li>
                        <li>
                            <a href="{{ route('client.news.index') }}"
                                class="text-gray-500 hover:text-blue-600 transition-all duration-300 hover:underline">
                                Tin tức
                            </a>
                        </li>
                        <li>
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </li>
                        <li>
                            <span
                                class="text-gray-700 font-medium">{{ $post->serviceCategory->name ?? 'Chuyên mục' }}</span>
                        </li>
                    </ol>
                </nav>

                <!-- Article Title với typography cải tiến -->
                <div class="mb-8">
                    <h1
                        class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-900 leading-tight mb-4 tracking-tight">
                        {{ $post->title }}
                    </h1>

                    <!-- Excerpt/Description nếu có -->

                </div>

                <!-- Article Meta Information với design mới -->
                <div class="flex flex-wrap items-center gap-6 text-sm">
                    @if ($post->created_at)
                        <div
                            class="flex items-center bg-gray-100 rounded-full px-4 py-2 hover:bg-gray-200 transition-colors">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-gray-500 text-xs">Ngày đăng</div>
                                <div class="font-semibold text-gray-800">{{ $post->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($post->author)
                        <div
                            class="flex items-center bg-gray-100 rounded-full px-4 py-2 hover:bg-gray-200 transition-colors">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-gray-500 text-xs">Tác giả</div>
                                <div class="font-semibold text-gray-800">{{ $post->author }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($post->category)
                        <div class="flex items-center">
                            <div
                                class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $post->category }}
                            </div>
                        </div>
                    @endif

                    <!-- Reading time estimate -->
                    <div class="flex items-center text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} phút đọc</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Article Content Section -->
        <div class="px-4 lg:px-8 py-8 lg:py-16">
            <div class="max-w-full mx-auto">
                <!-- Featured Image với effect mới -->
                @if ($post->thumbnail)
                    <div class="mb-16 group">
                        <div
                            class="relative overflow-hidden rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-500">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-transparent to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10">
                            </div>
                            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}"
                                class="w-full h-auto object-cover transition-all duration-700 group-hover:scale-105 group-hover:brightness-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent">
                            </div>

                            <!-- Image caption nếu có -->
                            @if ($post->image_caption ?? false)
                                <div
                                    class="absolute bottom-4 left-4 right-4 bg-black/60 backdrop-blur-sm text-white p-4 rounded-xl">
                                    <p class="text-sm">{{ $post->image_caption }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Main Content Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12">
                    <!-- Main Article Content -->
                    <div class="lg:col-span-4">
                        <div
                            class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-shadow duration-500">
                            <div class="p-6 lg:p-16 xl:p-20">
                                <!-- Content with enhanced styling -->
                                <div class="article-content">
                                    {!! $post->content !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-8">
                        <!-- Table of Contents (if content has headings) -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sticky top-8">
                            <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Mục lục
                            </h3>
                            <div id="table-of-contents" class="space-y-1 text-xs">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>

                        <!-- Article Info Card -->
                        <div
                            class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg border border-blue-100 p-4">
                            <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Thông tin
                            </h3>
                            <div class="space-y-2 text-xs">
                                @if ($post->created_at)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Ngày đăng:</span>
                                        <span
                                            class="font-semibold text-gray-900">{{ $post->created_at->format('d/m/Y') }}</span>
                                    </div>
                                @endif
                                @if ($post->author)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Tác giả:</span>
                                        <span class="font-semibold text-gray-900 truncate ml-2">{{ $post->author }}</span>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <!-- Quick Share -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                            <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                </svg>
                                Chia sẻ
                            </h3>
                            <div class="grid grid-cols-1 gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                    target="_blank"
                                    class="flex items-center justify-center bg-[#1877F2] hover:bg-[#166FE5] text-white p-2 rounded-lg transition-all duration-300 group text-xs">
                                    <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-300"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                                    target="_blank"
                                    class="flex items-center justify-center bg-[#1DA1F2] hover:bg-[#1A91DA] text-white p-2 rounded-lg transition-all duration-300 group text-xs">
                                    <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-300"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                    </svg>
                                    Twitter
                                </a>
                                <button onclick="copyToClipboard('{{ request()->url() }}')"
                                    class="flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white p-2 rounded-lg transition-all duration-300 group text-xs">
                                    <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    <span class="copy-text font-medium">Sao chép</span>
                                </button>
                            </div>
                        </div>

                        <!-- Related Articles Placeholder -->
                        @if (isset($relatedPosts) && count($relatedPosts) > 0)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                                <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                    Liên quan
                                </h3>
                                <div class="space-y-3">
                                    @foreach ($relatedPosts as $related)
                                        <a href="{{ route('posts.show', $related->slug) }}" class="group block">
                                            <div class="flex space-x-2">
                                                @if ($related->thumbnail)
                                                    <img src="{{ asset('storage/' . $related->thumbnail) }}"
                                                        alt="{{ $related->title }}"
                                                        class="w-12 h-12 object-cover rounded-lg group-hover:scale-105 transition-transform duration-300 flex-shrink-0">
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <h4
                                                        class="text-xs font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 line-clamp-2 leading-tight">
                                                        {{ $related->title }}
                                                    </h4>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $related->created_at->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Article Footer - Full Width -->
                <div class="mt-12 bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="border-t border-gray-100">
                        <div class="bg-gradient-to-br from-gray-50 to-white p-8 lg:p-12">

                            <!-- Article Footer với design mới -->
                            <div class="border-t border-gray-100">
                                <div class="bg-gradient-to-br from-gray-50 to-white p-8 lg:p-16">
                                    <!-- Tags với animation -->
                                    @if ($post->tags && count($post->tags) > 0)
                                        <div class="mb-12">
                                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                Thẻ liên quan
                                            </h3>
                                            <div class="flex flex-wrap gap-3">
                                                @foreach ($post->tags as $tag)
                                                    <span
                                                        class="group bg-white hover:bg-blue-50 border-2 border-gray-200 hover:border-blue-300 text-gray-700 hover:text-blue-700 px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300 cursor-pointer transform hover:-translate-y-1 hover:shadow-lg">
                                                        <span
                                                            class="group-hover:scale-110 transition-transform duration-300 inline-block">#{{ $tag }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Share Buttons với design mới -->
                                    <div class="border-t border-gray-200 pt-12">
                                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                            <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                            </svg>
                                            Chia sẻ bài viết
                                        </h3>
                                        <div class="flex flex-wrap gap-4">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                                target="_blank"
                                                class="group flex items-center bg-[#1877F2] hover:bg-[#166FE5] text-white px-8 py-4 rounded-2xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Facebook
                                            </a>

                                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                                                target="_blank"
                                                class="group flex items-center bg-[#1DA1F2] hover:bg-[#1A91DA] text-white px-8 py-4 rounded-2xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                                </svg>
                                                Twitter
                                            </a>

                                            <button onclick="copyToClipboard('{{ request()->url() }}')"
                                                class="group flex items-center bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-8 py-4 rounded-2xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                </svg>
                                                <span class="copy-text">Sao chép link</span>
                                            </button>

                                            <button onclick="window.print()"
                                                class="group flex items-center bg-gray-600 hover:bg-gray-700 text-white px-8 py-4 rounded-2xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                                In bài viết
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons với animation -->
                        <div class="mt-16 flex flex-col lg:flex-row gap-6">
                            <a href="{{ route('client.news.index') }}"
                                class="group flex items-center justify-center bg-white hover:bg-gray-50 text-gray-800 border-2 border-gray-200 hover:border-gray-300 px-10 py-5 rounded-2xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex-1">
                                <svg class="w-6 h-6 mr-3 group-hover:-translate-x-2 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <span class="text-lg">Quay lại danh sách tin tức</span>
                            </a>

                            @if ($nextPost ?? false)
                                <a href="{{ route('posts.show', $nextPost->slug) }}"
                                    class="group flex items-center justify-center bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-10 py-5 rounded-2xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex-1">
                                    <span class="text-lg">Bài viết tiếp theo</span>
                                    <svg class="w-6 h-6 ml-3 group-hover:translate-x-2 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced JavaScript -->
            <script>
                function copyToClipboard(text) {
                    navigator.clipboard.writeText(text).then(function() {
                        const button = event.target.closest('button');
                        const copyText = button.querySelector('.copy-text');
                        const originalText = copyText.innerHTML;

                        // Thêm animation success
                        button.classList.add('bg-green-600', 'hover:bg-green-700');
                        button.classList.remove('bg-gradient-to-r', 'from-green-500', 'to-emerald-600',
                            'hover:from-green-600', 'hover:to-emerald-700');

                        copyText.innerHTML = '✓ Đã sao chép!';

                        setTimeout(() => {
                            copyText.innerHTML = originalText;
                            button.classList.remove('bg-green-600', 'hover:bg-green-700');
                            button.classList.add('bg-gradient-to-r', 'from-green-500', 'to-emerald-600',
                                'hover:from-green-600', 'hover:to-emerald-700');
                        }, 2000);
                    }).catch(function() {
                        // Fallback cho trình duyệt cũ
                        const textArea = document.createElement('textarea');
                        textArea.value = text;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);

                        const button = event.target.closest('button');
                        const copyText = button.querySelector('.copy-text');
                        const originalText = copyText.innerHTML;
                        copyText.innerHTML = '✓ Đã sao chép!';

                        setTimeout(() => {
                            copyText.innerHTML = originalText;
                        }, 2000);
                    });
                }

                // Smooth scroll cho các anchor links
                document.addEventListener('DOMContentLoaded', function() {
                    // Generate Table of Contents
                    generateTableOfContents();

                    // Smooth scroll for anchor links
                    const links = document.querySelectorAll('a[href^="#"]');
                    links.forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const target = document.querySelector(this.getAttribute('href'));
                            if (target) {
                                target.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        });
                    });

                    // Highlight active TOC item on scroll
                    const observerOptions = {
                        rootMargin: '-20% 0% -35% 0%',
                        threshold: 0
                    };

                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            const id = entry.target.getAttribute('id');
                            const tocLink = document.querySelector(`a[href="#${id}"]`);

                            if (entry.isIntersecting) {
                                // Remove active class from all TOC links
                                document.querySelectorAll('#table-of-contents a').forEach(link => {
                                    link.classList.remove('bg-blue-100', 'text-blue-800',
                                        'font-semibold');
                                    link.classList.add('text-gray-600');
                                });

                                // Add active class to current TOC link
                                if (tocLink) {
                                    tocLink.classList.remove('text-gray-600');
                                    tocLink.classList.add('bg-blue-100', 'text-blue-800', 'font-semibold');
                                }
                            }
                        });
                    }, observerOptions);

                    // Observe all headings
                    document.querySelectorAll('.prose h1, .prose h2, .prose h3, .prose h4').forEach(heading => {
                        observer.observe(heading);
                    });
                });

                // Reading progress indicator
                window.addEventListener('scroll', function() {
                    const article = document.querySelector('.prose');
                    if (article) {
                        const articleTop = article.offsetTop;
                        const articleHeight = article.offsetHeight;
                        const windowHeight = window.innerHeight;
                        const scrollTop = window.pageYOffset;

                        const progress = Math.max(0, Math.min(100,
                            ((scrollTop - articleTop + windowHeight) / articleHeight) * 100
                        ));

                        // Tạo progress bar nếu chưa có
                        let progressBar = document.getElementById('reading-progress');
                        if (!progressBar) {
                            progressBar = document.createElement('div');
                            progressBar.id = 'reading-progress';
                            progressBar.className =
                                'fixed top-0 left-0 h-1 bg-gradient-to-r from-blue-500 to-purple-600 z-50 transition-all duration-300';
                            document.body.appendChild(progressBar);
                        }

                        progressBar.style.width = progress + '%';
                    }
                });
            </script>

            <!-- Print styles -->
            <style>
                @media print {

                    .no-print,
                    .lg\:col-span-4,
                    nav,
                    .sticky {
                        display: none !important;
                    }

                    .lg\:col-span-8 {
                        width: 100% !important;
                    }

                    .prose {
                        font-size: 12pt;
                        line-height: 1.5;
                    }

                    .prose img {
                        max-width: 100%;
                        height: auto;
                        page-break-inside: avoid;
                    }

                    body {
                        background: white !important;
                    }

                    .bg-gradient-to-br {
                        background: white !important;
                    }

                    .shadow-xl,
                    .shadow-lg {
                        box-shadow: none !important;
                    }
                }

                @media (max-width: 1024px) {
                    .lg\:col-span-4 .sticky {
                        position: relative !important;
                        top: auto !important;
                    }
                }

                /* Custom scrollbar for TOC */
                #table-of-contents {
                    max-height: 300px;
                    overflow-y: auto;
                }

                #table-of-contents::-webkit-scrollbar {
                    width: 4px;
                }

                #table-of-contents::-webkit-scrollbar-track {
                    background: #f1f5f9;
                    border-radius: 2px;
                }

                #table-of-contents::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 2px;
                }

                #table-of-contents::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }

                /* Enhanced prose styles for better readability */
                .prose {
                    --tw-prose-body: #374151;
                    --tw-prose-headings: #111827;
                    --tw-prose-links: #2563eb;
                    --tw-prose-bold: #111827;
                    --tw-prose-counters: #6b7280;
                    --tw-prose-bullets: #d1d5db;
                }

                .prose img {
                    margin: 2rem auto;
                    border-radius: 1rem;
                    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
                }

                .prose blockquote {
                    border-left-width: 4px;
                    border-left-color: #3b82f6;
                    background-color: #eff6ff;
                    padding: 1.5rem;
                    margin: 2rem 0;
                    border-radius: 0 0.75rem 0.75rem 0;
                }

                /* Line clamp utility */
                .line-clamp-2 {
                    overflow: hidden;
                    display: -webkit-box;
                    -webkit-box-orient: vertical;
                    -webkit-line-clamp: 2;
                }
            </style>

        @endsection
