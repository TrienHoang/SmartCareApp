<div class="lg:w-1/4">
    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
        {{-- Avatar --}}
        <div class="text-center mb-8">
            <div class="relative inline-block">
                <img id="profile-avatar"
                    src="{{ optional($user)->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                    alt="Avatar"
                    class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-blue-100 object-cover">
                <button onclick="openAvatarModal()"
                    class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 transition-colors shadow-lg">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                </button>
            </div>
            <h3 class="text-xl font-bold mb-2">{{ $user->full_name ?? 'Người dùng' }}</h3>
            <p class="text-gray-600">{{ $user->email ?? 'email@example.com' }}</p>
        </div>

        {{-- Menu --}}
        <nav class="space-y-2">
            <a href="{{ route('client.profile.show') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('client.profile.show') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : 'hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors' }}">
                <i data-lucide="user" class="w-5 h-5"></i>
                <span class="font-semibold">Thông Tin Cá Nhân</span>
            </a>
            <a href="{{ route('client.appointments.history') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('client.appointments.history') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : 'hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors' }}">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span>Lịch Sử Khám</span>
            </a>
            <a href="{{ route('client.prescriptions.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('client.prescriptions.index') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : 'hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors' }}">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                <span>Đơn Thuốc</span>
            </a>
            <a href="{{ route('client.wallet.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('client.wallet.index') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : 'hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors' }}">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
                <span>Ví Tiền</span>
            </a>
            <a href="{{ route('client.appointments.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('client.appointments.index') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : 'hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors' }}">
                <i data-lucide="clock" class="w-5 h-5"></i>
                <span>Lịch Hẹn</span>
            </a>
            <a href="{{ route('client.notifications.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('client.notifications.index') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : 'hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors' }}">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span>Thông Báo</span>
                @php
                    $currentUnreadCount = $notifications->where('userStatuses.0.is_read', false)->count();
                @endphp
                @if ($currentUnreadCount > 0)
                    <span id="unreadCount" class="ml-2 px-2 py-0.5 bg-red-500 text-white rounded-full text-xs font-semibold">
                        {{ $currentUnreadCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('client.payment_history.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('client.payment_history.index') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : 'hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors' }}">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
                <span>Lịch Sử Thanh Toán</span>
            </a>
            <a href="{{ route('client.review.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('client.review.index') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : 'hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors' }}">
                <i data-lucide="message-square" class="w-5 h-5"></i>
                <span>Bình luận của tôi</span>
            </a>
        </nav>
    </div>
</div>
<style>
  .bg-white.rounded-xl.shadow-lg.p-6.sticky {
    min-height:   1000px; /* Chiều cao tối thiểu */
  }
</style>



