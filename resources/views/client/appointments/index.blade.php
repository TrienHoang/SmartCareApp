@extends('client.layouts.app')

@section('title', 'Lịch Hẹn Của Bạn')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                {{-- Sidebar: KHÔNG THAY ĐỔI, GIỮ NGUYÊN TỪ LAYOUT CHUNG --}}
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                        {{-- Profile Avatar --}}
                        <div class="text-center mb-8">
                            <div class="relative inline-block">
                                <img id="profile-avatar" src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}"
                                    alt="Avatar"
                                    class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-blue-100 object-cover">
                                <button onclick="openAvatarModal()"
                                    class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 transition-colors shadow-lg">
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ auth()->user()->name ?? 'Người dùng' }}</h3>
                            <p class="text-gray-600">{{ auth()->user()->email ?? 'email@example.com' }}</p>
                        </div>

                        {{-- Menu --}}
                        <nav class="space-y-2">
                            <a href="{{ url('/thong-tin-ca-nhan') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="user" class="w-5 h-5"></i>
                                <span>Thông Tin Cá Nhân</span>
                            </a>
                            <a href="#lich-su-kham"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                                <span>Lịch Sử Khám</span>
                            </a>
                            <a href="{{ route('client.prescriptions.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                <span>Đơn Thuốc</span>
                            </a>
                            <a href="{{ route('client.appointments.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 text-blue-600 border-l-4 border-blue-600">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                                <span class="font-semibold">Lịch Hẹn</span>
                            </a>
                            <a href="#ho-so-y-te"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <span>Hồ Sơ Y Tế</span>
                            </a>
                            <a href="{{ route('client.uploads.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="upload" class="w-5 h-5"></i>
                                <span>Upload File</span>
                            </a>
                            <a href="{{ route('client.notifications.index') }}"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                <span>Thông Báo</span>
                                @php
                                    $currentUnreadCount = $notifications
                                        ->where('userStatuses.0.is_read', false)
                                        ->count();
                                @endphp
                                @if ($currentUnreadCount > 0)
                                    <span id="unreadCount"
                                        class="ml-2 px-2 py-0.5 bg-red-500 text-white rounded-full text-xs font-semibold">
                                        {{ $currentUnreadCount }}
                                    </span>
                                @endif
                            </a>
                            <a href="#cai-dat"
                                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-blue-600 transition-colors">
                                <i data-lucide="settings" class="w-5 h-5"></i>
                                <span>Cài Đặt</span>
                            </a>
                        </nav>
                    </div>
                </div>

                {{-- Main Content: Lịch Hẹn Của Bạn --}}
                <div class="lg:w-3/4">
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        {{-- Tiêu đề --}}
                        <div class="page-header-content mb-6">
                            <h1 class="page-title-main flex items-center gap-2 text-xl font-bold text-blue-600"
                                style="font-size: 2rem;">
                                <i data-lucide="clock" class="w-7 h-7"></i>
                                Lịch Hẹn Của Bạn
                            </h1>
                            <p class="text-gray-600 mt-2">Lưu ý: bạn chỉ có thể hủy lịch hẹn trong trạng thái đang chờ xác
                                nhận</p>
                        </div>

                        {{-- Tìm kiếm --}}
                        <form method="GET" action="{{ route('client.appointments.index') }}"
                            class="flex flex-wrap items-center gap-3 mb-6">
                            <input type="text" name="search" placeholder="Tìm theo bác sĩ hoặc dịch vụ..."
                                value="{{ request('search') }}"
                                class="flex-1 border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring focus:ring-blue-400" />
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm flex items-center gap-1">
                                <i class="fas fa-search"></i>
                                <span>Tìm Kiếm</span>
                            </button>
                            @if (request('search'))
                                <a href="{{ route('client.appointments.index') }}"
                                    class="btn-secondary flex items-center gap-1">
                                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Reset
                                </a>
                            @endif
                        </form>

                        {{-- Danh sách lịch hẹn --}}
                        <div class="appointment-list-wrapper">
                            @if ($appointments->isEmpty())
                                <div class="empty-state-appointments text-center py-12">
                                    <div class="flex justify-center mb-4">
                                        <i data-lucide="calendar-x" class="w-16 h-16 text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Chưa có lịch hẹn nào</h3>
                                    <p class="text-gray-600 mb-4">Bạn chưa có lịch hẹn nào được lên kế hoạch.</p>
                                    <a href="#" class="btn-primary-lg inline-flex items-center gap-2">
                                        <i data-lucide="plus-circle" class="w-5 h-5"></i> Đặt lịch hẹn mới
                                    </a>
                                </div>
                            @else
                                <div class="grid gap-6">
                                    @foreach ($appointments as $item)
                                        @php
                                            $statusClass = match ($item->status) {
                                                'pending' => 'text-yellow-600 bg-yellow-100',
                                                'confirmed' => 'text-blue-600 bg-blue-100',
                                                'completed' => 'text-green-600 bg-green-100',
                                                'cancelled' => 'text-red-600 bg-red-100',
                                                default => 'text-gray-600 bg-gray-100',
                                            };
                                        @endphp

                                        <div class="border border-gray-200 rounded-lg p-5 shadow-sm bg-white">
                                            {{-- Z --}}
                                            <div class="text-lg font-semibold text-blue-600 mb-2 uppercase">
                                                {{ $item->patient->full_name ?? auth()->user()->name }}
                                            </div>
                                            <div class="mb-2 text-sm">
                                                <span class="font-medium text-gray-700">Chuyên khoa:</span>
                                                {{ $item->doctor->specialization ?? '---' }}
                                            </div>
                                            <div class="mb-2 text-sm">
                                                <span class="font-medium text-gray-700">Dịch vụ:</span>
                                                {{ $item->service->name ?? '---' }}
                                            </div>
                                            <div class="mb-2 text-sm">
                                                <span class="font-medium text-gray-700">Ngày khám:</span>
                                                {{ \Carbon\Carbon::parse($item->appointment_time)->format('d/m/Y') }}
                                            </div>
                                            <div class="mb-2 text-sm">
                                                <span class="font-medium text-gray-700">Giờ khám dự kiến:</span>
                                                {{ \Carbon\Carbon::parse($item->appointment_time)->format('H:i') }}
                                            </div>
                                            <div class="mb-4 text-sm">
                                                <span class="font-medium text-gray-700">Trạng thái:</span>
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                    {{ $item->status_text ?? ucfirst($item->status) }}
                                                </span>
                                            </div>
                                            <div class="flex gap-3 items-center">
                                                <a href="{{ route('client.appointments.show', $item) }}"
                                                    title="Xem chi tiết" class="text-blue-600 hover:underline text-sm">
                                                    Xem chi tiết
                                                </a>
                                                @if ($item->status === 'pending')
                                                    <a href="{{ route('client.appointments.edit', $item) }}"
                                                        title="Chỉnh sửa" class="text-yellow-600 hover:underline text-sm">
                                                        Chỉnh sửa
                                                    </a>
                                                    <button type="button" onclick="openCancelModal({{ $item->id }})"
                                                        class="action-btn-cancel">
                                                        xóa
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div id="cancelModal"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                                <h2 class="text-lg font-semibold mb-4">Chọn lý do hủy lịch hẹn</h2>

                                <form id="cancelForm" method="POST" action="">
                                    @csrf
                                    @method('DELETE')

                                    @php
                                        $cancelReasons = [
                                            'Tôi có việc đột xuất',
                                            'Tôi đặt nhầm lịch',
                                            'Tôi đã khỏi bệnh',
                                            'Thời gian không phù hợp',
                                            'Tôi muốn đổi bác sĩ/dịch vụ',
                                            'Lý do khác',
                                        ];
                                    @endphp

                                    <div class="space-y-2">
                                        @foreach ($cancelReasons as $reason)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="reason_option" value="{{ $reason }}"
                                                    class="form-radio" onchange="toggleCustomReason(this)">
                                                <span>{{ $reason }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div id="customReasonContainer" class="mt-4 hidden">
                                        <label class="block mb-1 font-medium">Vui lòng ghi rõ lý do:</label>
                                        <textarea name="cancel_reason" id="customReason" class="w-full border rounded p-2" rows="3"></textarea>
                                    </div>

                                    <input type="hidden" name="cancel_reason_final" id="cancel_reason_final" required>

                                    <div class="flex justify-end mt-4 space-x-2">
                                        <button type="button" onclick="closeCancelModal()"
                                            class="px-4 py-2 bg-gray-300 rounded">Huỷ</button>
                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Xác nhận
                                            huỷ</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* General Container */
            .appointment-list-wrapper {
                padding: 0;
                /* Remove padding as outer container handles it */
                background: none;
                /* Remove background as outer container handles it */
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

            /* Table Styling */
            table {
                width: 100%;
                border-spacing: 0;
            }

            th,
            td {
                text-align: left;
                padding: 0.75rem 1rem;
            }

            th {
                background-color: #f3f4f6;
                color: #374151;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
            }

            tr:last-child td {
                border-bottom: none;
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
                /* yellow-100 */
                color: #d97706;
                /* yellow-700 */
            }

            .status-confirmed {
                background-color: #dbeafe;
                /* blue-100 */
                color: #2563eb;
                /* blue-700 */
            }

            .status-completed {
                background-color: #d1fae5;
                /* green-100 */
                color: #059669;
                /* green-700 */
            }

            .status-canceled {
                background-color: #fee2e2;
                /* red-100 */
                color: #dc2626;
                /* red-700 */
            }

            .status-info {
                background-color: #e0f2fe;
                /* light blue for general info */
                color: #0284c7;
                /* darker blue */
            }

            /* Service Badge */
            .service-badge {
                background-color: #eff6ff;
                /* blue-50 */
                color: #1e40af;
                /* blue-800 */
                padding: 0.25rem 0.6rem;
                border-radius: 6px;
                font-size: 0.75rem;
                font-weight: 500;
            }

            /* Action Buttons */
            .action-btn-view,
            .action-btn-edit,
            .action-btn-cancel {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.5rem;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
                text-decoration: none;
            }

            .action-btn-view {
                background-color: #eff6ff;
                /* blue-50 */
                color: #2563eb;
                /* blue-600 */
            }

            .action-btn-view:hover {
                background-color: #dbeafe;
                /* blue-100 */
            }

            .action-btn-edit {
                background-color: #fffbeb;
                /* yellow-50 */
                color: #d97706;
                /* yellow-600 */
            }

            .action-btn-edit:hover {
                background-color: #fef3c7;
                /* yellow-100 */
            }

            .action-btn-cancel {
                background-color: #fef2f2;
                /* red-50 */
                color: #ef4444;
                /* red-600 */
                border: none;
                /* remove default button border */
                cursor: pointer;
            }

            .action-btn-cancel:hover {
                background-color: #fee2e2;
                /* red-100 */
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
                    justify-content: center;
                }

                .page-header-content {
                    text-align: center;
                }

                table,
                thead,
                tbody,
                th,
                td,
                tr {
                    display: block;
                }

                thead {
                    display: none;
                }

                tr {
                    margin-bottom: 1rem;
                    border: 1px solid #e5e7eb;
                    border-radius: 0.5rem;
                    overflow: hidden;
                }

                td {
                    border-bottom: 1px solid #e5e7eb;
                    position: relative;
                    padding-left: 50%;
                    text-align: right;
                }

                td:before {
                    content: attr(data-label);
                    position: absolute;
                    left: 0;
                    width: 45%;
                    padding-left: 1rem;
                    font-weight: 600;
                    text-align: left;
                    color: #4b5563;
                }

                td:last-child {
                    border-bottom: none;
                }

                .action-btn-view,
                .action-btn-edit,
                .action-btn-cancel {
                    padding: 0.6rem 1rem;
                    font-size: 0.875rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Khởi tạo lại Lucide icons nếu chúng được thêm động hoặc nếu trang này tải qua AJAX
            // Đảm bảo thư viện Lucide đã được tải trước đó trong client.layouts.app
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        </script>
    @endpush
   <script>
    function openCancelModal(appointmentId) {
        // Hiển thị modal
        const modal = document.getElementById('cancelModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Gán appointment_id cho form
            const form = document.getElementById('cancelForm');
            form.action = `/client/appointments/${appointmentId}/cancel`; // Cập nhật URL

            // Reset các lựa chọn
            document.getElementById('customReasonContainer').classList.add('hidden');
            document.getElementById('customReason').value = '';
            document.getElementById('cancel_reason_final').value = '';
        }
    }

    function closeCancelModal() {
        const modal = document.getElementById('cancelModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function toggleCustomReason(radio) {
        const customContainer = document.getElementById('customReasonContainer');
        const finalReasonInput = document.getElementById('cancel_reason_final');

        if (radio.value === 'Lý do khác') {
            customContainer.classList.remove('hidden');
            finalReasonInput.value = '';
        } else {
            customContainer.classList.add('hidden');
            finalReasonInput.value = radio.value;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const customReasonTextarea = document.getElementById('customReason');
        customReasonTextarea?.addEventListener('input', function () {
            const finalReasonInput = document.getElementById('cancel_reason_final');
            finalReasonInput.value = this.value;
        });
    });
</script>

@endsection
