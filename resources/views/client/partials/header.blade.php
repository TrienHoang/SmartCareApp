<header class="bg-white shadow-md sticky top-0 z-50" x-data="{ menuOpen: false }">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-around items-center">
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
                        ['name' => 'Tin Tức', 'path' => '/tin-tuc'],
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
            <!-- Chuông thông báo -->
            <div class="relative group cursor-pointer" style="justify-content: end" onclick="toggleNotifications()">
                <i class="fas fa-bell text-xl text-gray-700"></i>

                <!-- Số lượng chưa đọc -->
                @if ($unreadNotificationsCount > 0)
                    <span
                        class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                        {{ $unreadNotificationsCount }}
                    </span>
                @endif

                <!-- Dropdown danh sách thông báo -->
                <div id="notification-dropdown"
                    class="hidden absolute right-0 mt-2 w-96 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                    <div class="p-4 font-semibold border-b">Thông báo</div>

                    <div class="max-h-60 overflow-y-auto divide-y" id="notification-list">
                        @forelse ($notifications->take(5) as $notification)
                            <div class="p-3 hover:bg-gray-100 text-sm">
                                <div class="font-medium text-gray-800">
                                    {{ $notification->title }}
                                </div>
                                <div class="text-gray-500 text-xs">
                                    {{ $notification->sent_at ? \Carbon\Carbon::parse($notification->sent_at)->diffForHumans() : '' }}
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-sm text-gray-500">Không có thông báo nào.</div>
                        @endforelse
                    </div>

                    <div class="text-center p-2 text-sm text-blue-500 hover:underline">
                        <a href="{{ route('client.notifications.index') }}">Xem tất cả</a>
                    </div>
                </div>
            </div>
            {{-- Desktop Contact & Button --}}
            <div class="hidden md:flex items-center space-x-4">
                @if (Auth::check())
                    {{-- Nếu đã đăng nhập --}}
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                alt="Avatar" class="w-8 h-8 rounded-full border object-cover">
                            <span class="text-sm">{{ Auth::user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>


                        {{-- Dropdown menu --}}
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded shadow-lg invisible opacity-0 group-hover:visible group-hover:opacity-100 hover:visible hover:opacity-100 transition-all z-50">
                            <a href="{{ route('client.profile.show') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Trang cá nhân
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Lịch hẹn của tôi
                            </a>
                            <a href="{{ route('client.uploads.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="upload" class="w-5 h-5"></i>
                                <span>Upload File</span>
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Cài đặt
                            </a>
                            <a href="{{ route('logout') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Đăng xuất
                            </a>
                            <a href="{{ route('client.payment_history.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Lịch sử thanh toán
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Nếu chưa đăng nhập --}}
                    <a href="{{ route('login') }}"
                        class="flex items-center space-x-1 text-gray-600 hover:text-blue-600 transition-colors">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        <span class="text-sm">Đăng Nhập</span>
                    </a>
                @endif
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
<script>
    function toggleNotifications() {
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.toggle('hidden');
    }

    // Đóng dropdown khi click ra ngoài
    document.addEventListener('click', function(event) {
        const bell = event.target.closest('.group');
        const dropdown = document.getElementById('notification-dropdown');

        if (!bell && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
