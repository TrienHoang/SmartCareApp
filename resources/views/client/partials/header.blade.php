<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
            <i class="fas fa-heartbeat mr-2"></i>SmartCare
        </a>

        <nav class="space-x-4 text-gray-700 text-sm">
            <a href="{{ route('client.uploads.index') }}" class="{{ request()->is('client/uploads*') ? 'active-link' : '' }}">
                Tài liệu
            </a>
            <a href="#" class="{{ request()->is('appointments*') ? 'active-link' : '' }}">
                Lịch hẹn
            </a>
            <a href="#" class="{{ request()->is('profile') ? 'active-link' : '' }}">
                Hồ sơ
            </a>
            <a href="{{ route('logout') }}" class="text-red-500 hover:underline">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </nav>
    </div>
</header>
