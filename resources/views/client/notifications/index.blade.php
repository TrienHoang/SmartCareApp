@extends('client.layouts.profile-layout')


@section('title', 'Danh sách thông báo')

@section('profile-content')
    {{-- <div class="lg:w-3/4"> --}}
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <div class="py-6 px-4" x-data="notificationModal()">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-extrabold text-blue-600 relative">
                    <i class="fas fa-bell text-blue-500 mr-3"></i>Danh sách thông báo
                </h1>

                {{-- Dropdown Actions --}}
                @php
                    $currentUnreadCount = $notifications->where('userStatuses.0.is_read', false)->count();
                    $currentNotificationCount = $notifications->count();
                @endphp

                <div class="relative" x-cloak x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-cog mr-2"></i>
                        Hành động
                        <i class="fas fa-chevron-down ml-2 transform transition-transform duration-200"
                            :class="{ 'rotate-180': dropdownOpen }"></i>
                    </button>

                    <div x-cloak x-show="dropdownOpen" @click.away="dropdownOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-10">

                        <div class="py-2">
                            {{-- Đánh dấu tất cả đã đọc --}}
                            <div class="flex justify-end mb-4">
                                <button @click="openMarkAllModal(); setTimeout(() => dropdownOpen = false, 200)"
                                    x-bind:disabled="$store.notification.unreadCount === 0"
                                    class="w-full flex items-center px-4 py-3 text-left text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <div class="bg-blue-100 p-1.5 rounded-full mr-3">
                                        <i class="fas fa-eye text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Đánh dấu tất cả đã đọc</div>
                                        <div class="text-xs text-gray-500"
                                            x-text="$store.notification.unreadCount + ' thông báo chưa đọc'">
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <!-- Separator -->
                            <div class="border-t border-gray-100 my-1"></div>


                            {{-- Separator --}}
                            <div class="border-t border-gray-100 my-1"></div>

                            {{-- Xóa tất cả --}}
                            <button @click="openDeleteAllModal(); dropdownOpen = false"
                                :disabled="{{ $currentNotificationCount }} === 0"
                                class="w-full flex items-center px-4 py-3 text-left text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-gray-700">
                                <div class="bg-red-100 p-1.5 rounded-full mr-3">
                                    <i class="fas fa-trash text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Xóa tất cả thông báo</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $currentNotificationCount }} thông báo
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hiển thị thông báo thành công/lỗi --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)" x-transition
                    class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded shadow z-50">
                    {{ session('success') }}
                </div>
            @endif


            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($notifications->isEmpty())
                <div class="bg-white p-8 rounded-lg shadow-xl flex flex-col items-center justify-center min-h-[300px]">
                    <img src="{{ asset('admin/assets/img/null-data.webp') }}" alt="Không có thông báo"
                        class="mx-auto mb-6 w-48 h-48 object-contain">
                    <p class="text-xl text-gray-600 font-medium mb-2">Bạn chưa có thông báo nào.</p>
                    <p class="text-gray-500 text-sm">Hãy kiểm tra lại sau nhé!</p>
                </div>
            @else
                @foreach ($notifications as $notification)
                    @php
                        $userStatus = $notification->userStatuses->firstWhere('user_id', Auth::id());
                    @endphp

                    @if ($userStatus && $userStatus->is_deleted)
                        @continue
                    @endif

                    <div id="notification-{{ $notification->id }}" @click="openModal({{ $notification->id }})"
                        class="bg-white p-5 rounded-lg shadow-md mb-4 flex justify-between items-start border-l-4
        {{ $userStatus && $userStatus->is_read ? 'border-gray-300' : 'border-blue-500' }}
        hover:shadow-lg hover:bg-gray-50 cursor-pointer transition-all duration-200 relative"
                        data-notification-id="{{ $notification->id }}">
                        <div class="flex-grow pr-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1 leading-tight">
                                {{ $notification->title }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">
                                {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($notification->content)), 180) }}
                            </p>
                            <span class="text-xs text-gray-500 flex items-center">
                                <i class="fas fa-clock mr-2"></i>Gửi lúc:
                                {{ $notification->sent_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        {{-- 🔴 Chấm đỏ chỉ báo chưa đọc --}}
                        @if (!$userStatus || !$userStatus->is_read)
                            <span
                                class="unread-indicator absolute top-2 right-2 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                        @endif
                    </div>
                @endforeach

                {{-- Modal xem chi tiết thông báo --}}
                <div x-show="open" x-cloak class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title"
                    role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                            @click="open = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full max-w-full md:max-w-4xl lg:max-w-6xl xl:max-w-7xl 2xl:max-w-screen-xl">
                            <div class="bg-blue-600 px-4 py-3 sm:px-6 flex justify-between items-center rounded-t-lg">
                                <h3 class="text-xl leading-6 font-semibold text-white" id="modal-title">
                                    Chi tiết thông báo
                                </h3>
                                <button @click="open = false" type="button"
                                    class="text-white hover:text-gray-200 focus:outline-none">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                        <h4 class="text-lg font-bold text-gray-900 mb-2"
                                            x-text="activeNotification ? activeNotification.title : 'Đang tải...'">
                                        </h4>
                                        <div class="mt-2 text-gray-700 leading-relaxed">
                                            <p class="text-base"
                                                x-html="activeNotification ? activeNotification.content : 'Đang tải...'">
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-4 border-t pt-2">
                                            Gửi lúc: <span
                                                x-text="activeNotification ? formatDate(activeNotification.sent_at) : 'Đang tải...'"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto"
                                    @click="open = false">
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- </div> --}}

    {{-- Modal Đánh dấu tất cả đã đọc --}}
    <div id="markAllModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-2 rounded-full mr-3">
                    <i class="fas fa-eye text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Đánh dấu tất cả đã đọc</h3>
            </div>

            <p class="text-gray-600 mb-6">
                Bạn có chắc chắn muốn đánh dấu tất cả
                <span class="font-semibold text-blue-600" x-text="$store.notification.unreadCount"></span>
                thông báo là đã đọc không?
            </p>

            <div class="flex justify-end space-x-3">
                <button onclick="closeMarkAllModal()"
                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-200">
                    Hủy
                </button>

                <form method="POST" action="{{ route('client.notifications.mark-all-as-read') }}" class="inline">
                    @csrf
                    <button type="submit" id="markAllSubmitBtn"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200 flex items-center">
                        <span class="submit-text">Xác nhận</span>
                        <div class="loading-spinner hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Xóa tất cả --}}
    <div id="deleteAllModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-2 rounded-full mr-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Xóa tất cả thông báo</h3>
            </div>

            <p class="text-gray-600 mb-6">
                <strong class="text-red-600">Cảnh báo:</strong> Bạn có chắc chắn muốn xóa TẤT CẢ thông báo không?
                Hành động này không thể hoàn tác.
            </p>

            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteAllModal()"
                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-200">
                    Hủy
                </button>
                <form method="POST" action="{{ route('client.notifications.deleteAll') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="ids" value="delete-all">
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-200">
                        Xóa tất cả
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                // ✅ Store toàn cục cho thông báo
                Alpine.store('notification', {
                    unreadCount: {{ $currentUnreadCount ?? 0 }},
                    notificationCount: {{ $currentNotificationCount ?? 0 }}
                });

                Alpine.data('notificationModal', () => ({
                    open: false,
                    activeNotification: null,
                    loading: false,

                    async openModal(notificationId) {
                        this.loading = true;
                        this.activeNotification = null;
                        this.open = true;

                        try {
                            const response = await fetch(
                                `{{ url('/client/notifications') }}/${notificationId}`);
                            const data = await response.json();

                            if (response.ok && data) {
                                this.activeNotification = data;

                                // Đánh dấu UI là đã đọc
                                const notifEl = document.querySelector(
                                    `#notification-${notificationId}`);
                                if (notifEl) {
                                    notifEl.classList.remove('bg-blue-50', 'border-blue-500');
                                    notifEl.classList.add('bg-white', 'border-gray-300');
                                    notifEl.querySelector('.status-dot, .unread-indicator')?.remove();
                                }

                                // ✅ Giảm biến toàn cục unreadCount
                                const unreadBadge = document.querySelector('#unreadCount');
                                if (Alpine.store('notification').unreadCount > 0) {
                                    Alpine.store('notification').unreadCount--;
                                    if (Alpine.store('notification').unreadCount <= 0) {
                                        Alpine.store('notification').unreadCount = 0;
                                        if (unreadBadge) unreadBadge.remove();
                                    } else if (unreadBadge) {
                                        unreadBadge.textContent = Alpine.store('notification')
                                            .unreadCount;
                                    }
                                }

                                this.markAsRead(notificationId);
                            } else {
                                alert(data.message || 'Không thể tải chi tiết thông báo.');
                                this.open = false;
                            }
                        } catch (error) {
                            console.error('Lỗi khi tải thông báo:', error);
                            this.activeNotification = {
                                title: 'Lỗi',
                                content: 'Không thể tải chi tiết thông báo.'
                            };
                        } finally {
                            this.loading = false;
                        }
                    },

                    closeModal() {
                        this.open = false;
                        this.activeNotification = null;
                    },

                    markAsRead(notificationId) {
                        if (!notificationId) return;

                        fetch(`{{ url('/client/notifications') }}/${notificationId}/mark-as-read`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Đánh dấu đã đọc thành công');
                                }
                            })
                            .catch(error => {
                                console.error('Lỗi khi đánh dấu đã đọc:', error);
                            });
                    },

                    formatDate(isoString) {
                        if (!isoString) return '';
                        const date = new Date(isoString);
                        return date.toLocaleDateString('vi-VN', {
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                }));
            });

            // Modal xử lý đánh dấu tất cả / xoá tất cả
            function openMarkAllModal() {
                document.getElementById('markAllModal').classList.remove('hidden');
                document.getElementById('markAllModal').classList.add('flex');
            }

            function closeMarkAllModal() {
                document.getElementById('markAllModal').classList.add('hidden');
                document.getElementById('markAllModal').classList.remove('flex');
            }

            function openDeleteAllModal() {
                document.getElementById('deleteAllModal').classList.remove('hidden');
                document.getElementById('deleteAllModal').classList.add('flex');
            }

            function closeDeleteAllModal() {
                document.getElementById('deleteAllModal').classList.add('hidden');
                document.getElementById('deleteAllModal').classList.remove('flex');
            }

            document.getElementById('markAllModal').addEventListener('click', function(e) {
                if (e.target === this) closeMarkAllModal();
            });

            document.getElementById('deleteAllModal').addEventListener('click', function(e) {
                if (e.target === this) closeDeleteAllModal();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMarkAllModal();
                    closeDeleteAllModal();
                }
            });

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* General Container */
            [x-cloak] {
                display: none !important;
            }

            .appointment-list-wrapper {
                padding: 0;
                background: none;
            }

            /* Page Header */
            .page-header-content {
                margin-bottom: 2rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid #e5e7eb;
            }

            .page-title-main {
                font-size: 2rem;
                font-weight: 700;
                color: #1f2937;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin: 0;
            }

            /* Status Badges */
            .status-badge-appointments {
                display: inline-flex;
                align-items: center;
                padding: 0.3em 0.7em;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
                line-height: 1;
            }

            .status-pending {
                background-color: #fef3c7;
                color: #d97706;
            }

            .status-confirmed {
                background-color: #dbeafe;
                color: #2563eb;
            }

            .status-completed {
                background-color: #d1fae5;
                color: #059669;
            }

            .status-canceled {
                background-color: #fee2e2;
                color: #dc2626;
            }

            .status-info {
                background-color: #e0f2fe;
                color: #0284c7;
            }

            /* Service Badge */
            .service-badge {
                background-color: #eff6ff;
                color: #1e40af;
                padding: 0.25rem 0.6rem;
                border-radius: 6px;
                font-size: 0.75rem;
                font-weight: 500;
            }

            /* Empty State */
            .empty-state-appointments {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 4rem 2rem;
                border: 2px dashed #e5e7eb;
                border-radius: 12px;
                margin-top: 2rem;
                color: #6b7280;
            }

            .empty-icon-appointments {
                margin-bottom: 1.5rem;
            }

            .btn-primary-lg {
                display: inline-flex;
                align-items: center;
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .btn-primary-lg:hover {
                background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .page-title-main {
                    font-size: 1.75rem;
                    /* justify-content: center; */
                }

                .page-header-content {
                    text-align: center;
                }
            }
        </style>
    @endpush
@endsection
