@extends('client.layouts.profile-layout')


@section('title', 'chi tiết thông báo')

@section('profile-content')
<div class="max-w-3xl mx-auto py-6 px-4">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-6 text-center relative">
        <i class="fas fa-bell text-yellow-500 mr-3"></i>Thông báo của bạn
        @if (isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
            <span class="absolute top-0 -right-6 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform -translate-y-1/2 translate-x-1/2">
                {{ $unreadNotificationsCount }}
            </span>
        @endif
    </h2>

    @if ($notifications->isEmpty())
        <div class="bg-white p-8 rounded-lg shadow-xl flex flex-col items-center justify-center min-h-[300px]">
            <img src="{{ asset('images/empty-notifications.svg') }}" alt="Không có thông báo" class="mx-auto mb-6 w-48 h-48 object-contain">
            <p class="text-xl text-gray-600 font-medium mb-2">Bạn chưa có thông báo nào.</p>
            <p class="text-gray-500 text-sm">Hãy kiểm tra lại sau nhé!</p>
        </div>
    @else
        <div x-data="{ open: false, activeNotification: null }">
            @foreach ($notifications as $notification)
                <div class="bg-white p-5 rounded-lg shadow-md mb-4 flex justify-between items-start border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-200">
                    <div class="flex-grow pr-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1 leading-tight">{{ $notification->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{!! Str::limit($notification->content, 100) !!}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 flex items-center">
                                <i class="fas fa-clock mr-2"></i>Gửi lúc: {{ $notification->sent_at->format('d/m/Y H:i') }}
                            </span>
                            <button @click="open = true; activeNotification = {{ $notification->id }};" class="text-blue-500 hover:underline focus:outline-none text-sm">Xem chi tiết</button>
                        </div>
                    </div>
                    <form action="{{ route('client.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa thông báo này? Hành động này không thể hoàn tác.');" class="ml-4 flex-shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 focus:outline-none p-2 rounded-full hover:bg-red-100 transition-colors duration-200">
                            <i class="fas fa-trash-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            @endforeach

            {{-- Modal xem chi tiết thông báo --}}
            <div x-show="open" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="open = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title" x-text="activeNotification ? notifications.find(n => n.id === activeNotification)?.title : ''">
                                        Tiêu đề thông báo
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500" x-html="activeNotification ? notifications.find(n => n.id === activeNotification)?.message : ''">
                                            Nội dung chi tiết thông báo sẽ hiển thị ở đây.
                                        </p>
                                        <p class="text-xs text-gray-500 mt-2" x-text="activeNotification ? 'Gửi lúc: ' + (new Date(notifications.find(n => n.id === activeNotification)?.sent_at).toLocaleDateString() + ' ' + new Date(notifications.find(n => n.id === activeNotification)?.sent_at).toLocaleTimeString()) : ''">
                                            Gửi lúc: ...
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto" @click="open = false">
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationsData', () => ({
            notifications: @json($notifications),
            open: false,
            activeNotification: null,
        }));
    });
</script>
@endsection