@extends('client.layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Article Header Section -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a></li>
                    <li><span class="mx-2">/</span></li>
                    {{-- <li><a href="{{ route('news.index') }}" class="hover:text-blue-600 transition-colors">Tin tức</a></li> --}}
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-800 font-medium">{{ Str::limit($post->title, 50) }}</li>
                </ol>
            </nav>

            <!-- Article Title -->
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
                {{ $post->title }}
            </h1>

            <!-- Article Meta Information -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-8">
                @if($post->created_at)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $post->created_at->format('d/m/Y') }}</span>
                    </div>
                @endif

                @if($post->author)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ $post->author }}</span>
                    </div>
                @endif

                @if($post->category)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                            {{ $post->category }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Article Content Section -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Featured Image -->
            @if($post->thumbnail)
                <div class="mb-12">
                    <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                        <img src="{{ asset('storage/' . $post->thumbnail) }}" 
                             alt="{{ $post->title }}" 
                             class="w-full h-auto object-cover transition-transform duration-700 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                </div>
            @endif

            <!-- Article Content -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-8 lg:p-12">
                    <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-900 prose-img:rounded-xl prose-img:shadow-lg">
                        {!! $post->content !!}
                    </div>
                </div>

                <!-- Article Footer -->
                <div class="border-t border-gray-200 bg-gray-50 p-8 lg:p-12">
                    <!-- Tags -->
                    @if($post->tags && count($post->tags) > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Thẻ liên quan</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                    <span class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-full text-sm font-medium transition-colors cursor-pointer">
                                        #{{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Share Buttons -->
                    <div class="border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Chia sẻ bài viết</h3>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" 
                               class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd"></path>
                                </svg>
                                Facebook
                            </a>
                            
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" 
                               target="_blank" 
                               class="flex items-center bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                                </svg>
                                Twitter
                            </a>
                            
                            <button onclick="copyToClipboard('{{ request()->url() }}')" 
                                    class="flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                </svg>
                                Sao chép link
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-12 flex flex-col sm:flex-row gap-4">
                {{-- <a href="{{ route('posts.index') }}" 
                 --}}
                   class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-8 py-4 rounded-xl transition-colors font-medium group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại danh sách tin tức
                </a>
                
                @if($nextPost ?? false)
                    <a href="{{ route('posts.show', $nextPost->slug) }}" 
                       class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl transition-colors font-medium group">
                        Bài viết tiếp theo
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for copy to clipboard functionality -->
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Đã sao chép!
        `;
        setTimeout(() => {
            button.innerHTML = originalText;
        }, 2000);
    });
}
</script>

@endsection