<header class="bg-white shadow-md sticky top-0 z-50" x-data="{ menuOpen: false }">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center">
                    <i data-lucide="stethoscope" class="w-6 h-6 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold gradient-text">SmartCare</h1>
            </div>

            {{-- Desktop Menu --}}
            <nav class="hidden md:flex space-x-8">
                @php
                    $menuItems = [
                        ['name' => 'Trang Chủ', 'path' => '/'],
                        ['name' => 'Giới Thiệu', 'path' => '/gioi-thieu'],
                        ['name' => 'Dịch Vụ', 'path' => '/dich-vu'],
                        ['name' => 'Đặt Lịch', 'path' => '/dat-lich'],
                        ['name' => 'Liên Hệ', 'path' => '/lien-he'],
                    ];
                @endphp

                @foreach ($menuItems as $item)
                    <a href="{{ url($item['path']) }}"
                        class="font-medium transition-colors hover:text-blue-600 {{ request()->is(ltrim($item['path'], '/')) ? 'text-blue-600' : 'text-gray-700' }}">
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Desktop Contact & Button --}}
            <div class="hidden md:flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-gray-600">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                    <span class="text-sm">0123.456.789</span>
                </div>
                <a href="{{ url('/appointment') }}"
                    class="gradient-bg text-white px-6 py-2 rounded-full hover:opacity-90 transition-opacity">
                    Đặt Lịch Ngay
                </a>
            </div>

            {{-- Mobile Toggle Button --}}
            <button class="md:hidden" @click="menuOpen = !menuOpen">
                <template x-if="menuOpen">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </template>
                <template x-if="!menuOpen">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </template>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div class="md:hidden mt-4 pb-4 border-t pt-4" x-show="menuOpen" x-transition>
            @foreach ($menuItems as $item)
                <a href="{{ url($item['path']) }}"
                    class="block w-full text-left py-2 px-4 rounded transition-colors
            {{ request()->is(ltrim($item['path'], '/')) ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}"
                    @click="menuOpen = false">
                    {{ $item['name'] }}
                </a>
            @endforeach

            <a href="{{ url('/appointment') }}"
                class="w-full block text-center gradient-bg text-white py-2 px-4 rounded-full mt-4 hover:opacity-90 transition-opacity"
                @click="menuOpen = false">
                Đặt Lịch Ngay
            </a>
        </div>
    </div>
</header>
