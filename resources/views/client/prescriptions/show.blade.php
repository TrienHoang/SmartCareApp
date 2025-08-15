@extends('client.layouts.profile-layout')


@section('title', 'Chi tiết đơn thuốc')

@section('profile-content')
    <div class="lg:w-3/4">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            {{-- Header Section (Modified for consistency with main content area) --}}
            <div class="flex items-center justify-between mb-6 border-b pb-4">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl">
                        <i class="fas fa-prescription-bottle-alt"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Chi Tiết Đơn Thuốc</h1>
                        <p class="text-gray-600 text-sm">Mã đơn: <span
                                class="font-semibold text-blue-600">#{{ $prescription->id }}</span></p>
                    </div>
                </div>
            </div>

            {{-- General Prescription Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-b pb-6">
                <div>
                    <p class="text-gray-600 text-sm">Bác sĩ kê đơn:</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Ngày kê đơn:</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y - H:i') }}</p>
                </div>
                @if ($prescription->medicalRecord && $prescription->medicalRecord->diagnosis)
                    <div class="md:col-span-2">
                        <p class="text-gray-600 text-sm">Chẩn đoán:</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $prescription->medicalRecord->diagnosis }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- Notes Section --}}
            @if ($prescription->notes)
                <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 mb-8 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-xl mr-3">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <div>
                            <h6 class="font-semibold mb-1">Ghi chú từ bác sĩ:</h6>
                            <p class="text-sm">{{ $prescription->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Medicines List --}}
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center justify-between">
                Danh Sách Thuốc
                <span
                    class="bg-blue-600 text-white text-sm font-semibold px-3 py-1 rounded-full">{{ count($prescription->prescriptionItems) }}
                    thuốc</span>
            </h3>

            <div class="space-y-4">
                @forelse($prescription->prescriptionItems as $index => $item)
                    <div class="flex items-start bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
                        <div
                            class="flex-shrink-0 w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-4">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $item->medicine->name }}
                            </h4>
                            @if ($item->medicine->description)
                                <p class="text-gray-600 text-sm mb-3">{{ $item->medicine->description }}</p>
                            @endif
                            <div class="flex flex-wrap gap-4 text-sm">
                                <div class="flex items-center space-x-1">
                                    <span class="font-medium text-gray-500">Liều dùng:</span>
                                    <span
                                        class="px-2 py-0.5 bg-green-100 text-green-800 rounded-md font-semibold">{{ $item->medicine->dosage }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <span class="font-medium text-gray-500">Đơn vị:</span>
                                    <span
                                        class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-md font-semibold">{{ $item->medicine->unit }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <span class="font-medium text-gray-500">Số lượng:</span>
                                    <span
                                        class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded-md font-semibold">{{ $item->quantity }}</span>
                                </div>
                                @if ($item->notes)
                                    <div class="flex items-center space-x-1">
                                        <span class="font-medium text-gray-500">Ghi chú thuốc:</span>
                                        <span class="text-gray-700">{{ $item->notes }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-600">
                        <i class="fas fa-exclamation-circle text-4xl mb-3 text-yellow-500"></i>
                        <p class="text-lg font-semibold">Đơn thuốc này chưa có thuốc nào được kê.</p>
                        <p>Vui lòng liên hệ bác sĩ để biết thêm chi tiết.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions & Summary moved to main content area as suggested by original layout --}}
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Thao tác nhanh</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <a href="{{ route('client.prescriptions.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Quay lại
                </a>
                <button onclick="window.print()"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i> In đơn
                </button>
                <button onclick="downloadPrescription()"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i> Tải PDF
                </button>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-6">Tóm tắt</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-gray-600 text-sm">Loại thuốc:</p>
                    <p class="text-xl font-bold text-blue-600">{{ count($prescription->prescriptionItems) }}
                    </p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-gray-600 text-sm">Tổng số lượng:</p>
                    <p class="text-xl font-bold text-blue-600">
                        {{ $prescription->prescriptionItems->sum('quantity') }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-gray-600 text-sm">Ngày kê:</p>
                    <p class="text-xl font-bold text-blue-600">
                        {{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Các CSS tùy chỉnh của bạn */
            /* Đảm bảo các lớp CSS ở đây không xung đột quá mức với Tailwind */
            /* Tôi đã thử điều chỉnh một số, nhưng bạn nên kiểm tra kỹ sau khi tích hợp */

            /* Gradient text for titles, if you have this utility or want to add it */
            .gradient-text {
                background-image: linear-gradient(to right, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                color: transparent;
                /* Fallback for unsupported browsers */
            }

            /* Main container adjustments */
            .prescription-detail-container {
                background: #f8fafc;
                min-height: auto;
                /* Allow the parent min-h-screen to control overall height */
                padding: 0;
                /* Remove padding as parent handles it */
            }

            /* Header Section Styling (using Tailwind-like classes where possible) */
            /* I've removed the original .prescription-header and adjusted the top section directly in HTML
                                       to better align with typical Tailwind structure inside the main content area.
                                       If you prefer the full-width header, you'll need to move it out of the lg:w-3/4 div.
                                    */
            /*
                                    .prescription-header {
                                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                        color: white;
                                        padding: 2rem 0;
                                        margin-bottom: 2rem;
                                    }
                                    .header-content {
                                        display: flex;
                                        align-items: center;
                                        gap: 1rem;
                                    }
                                    .prescription-icon {
                                        width: 60px;
                                        height: 60px;
                                        background: rgba(255, 255, 255, 0.2);
                                        border-radius: 16px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-size: 1.5rem;
                                    }
                                    .title {
                                        font-size: 1.75rem;
                                        font-weight: 700;
                                        margin: 0;
                                        line-height: 1.2;
                                    }
                                    .subtitle {
                                        margin: 0;
                                        opacity: 0.9;
                                        font-size: 0.95rem;
                                    }
                                    .status-badge .badge {
                                        background: rgba(16, 185, 129, 0.2);
                                        color: #10b981;
                                        border: 1px solid rgba(16, 185, 129, 0.3);
                                        padding: 0.5rem 1rem;
                                        border-radius: 25px;
                                        font-size: 0.875rem;
                                        font-weight: 500;
                                    }
                                    */

            /* Info Card */
            .info-card {
                /* Converted to Tailwind classes directly in HTML for better integration */
                /* background: white;
                                        border-radius: 16px;
                                        padding: 2rem;
                                        margin-bottom: 2rem;
                                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); */
            }

            /* Medicines Card */
            .medicines-card {
                /* Converted to Tailwind classes directly in HTML for better integration */
                /* background: white;
                                        border-radius: 16px;
                                        overflow: hidden;
                                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); */
            }

            /* Sidebar actions and summary - converted to Tailwind in HTML */
            /* You can delete all your custom CSS for these if you've done that in HTML */
            /*
                                    .sidebar-card {}
                                    .sidebar-title {}
                                    .actions-grid {}
                                    .action-btn {}
                                    .summary-grid {}
                                    .summary-item {}
                                    .summary-label {}
                                    .summary-value {}
                                    */

            /* Original specific styles that might still be useful or need adjustment */
            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
                /* margin-bottom: 2rem; -- now handled by parent div */
            }

            .info-item {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .info-icon {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
            }

            .info-icon.doctor {
                background: rgba(59, 130, 246, 0.1);
                color: #3b82f6;
            }

            .info-icon.date {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }

            .info-content {
                display: flex;
                flex-direction: column;
            }

            .label {
                font-size: 0.875rem;
                color: #6b7280;
                margin-bottom: 0.25rem;
            }

            .value {
                font-weight: 600;
                color: #1f2937;
                font-size: 1rem;
            }

            /* Notes Section */
            .notes-section {
                /* Converted to Tailwind classes directly in HTML for better integration */
                /* background: rgba(59, 130, 246, 0.05);
                                        border: 1px solid rgba(59, 130, 246, 0.1);
                                        border-radius: 12px;
                                        padding: 1.5rem;
                                        border-left: 4px solid #3b82f6; */
            }

            .notes-content {
                display: flex;
                gap: 1rem;
            }

            .notes-icon {
                color: #3b82f6;
                font-size: 1.25rem;
                margin-top: 0.25rem;
            }

            .notes-text h6 {
                margin: 0 0 0.5rem 0;
                color: #1f2937;
                font-weight: 600;
            }

            .notes-text p {
                margin: 0;
                color: #4b5563;
                line-height: 1.6;
            }

            .medicines-header {
                /* Converted to Tailwind in HTML */
                /* background: #f8fafc;
                                        padding: 1.5rem 2rem;
                                        border-bottom: 1px solid #e5e7eb;
                                        display: flex;
                                        align-items: center;
                                        justify-content: space-between;
                                        gap: 1rem; */
            }

            .medicines-header h3 {
                /* Converted to Tailwind in HTML */
                /* margin: 0;
                                        color: #1f2937;
                                        font-size: 1.25rem;
                                        font-weight: 600;
                                        flex: 1; */
            }

            .medicines-count {
                /* Converted to Tailwind in HTML */
                /* background: #3b82f6;
                                        color: white;
                                        padding: 0.25rem 0.75rem;
                                        border-radius: 20px;
                                        font-size: 0.875rem;
                                        font-weight: 500; */
            }

            .medicines-list {
                /* Removed specific padding as it's handled by medicine-item now */
                padding: 0;
            }

            .medicine-item {
                /* Converted to Tailwind in HTML */
                /* display: flex;
                                        align-items: center;
                                        gap: 1.5rem;
                                        padding: 1.5rem 2rem;
                                        border-bottom: 1px solid #f3f4f6;
                                        transition: all 0.2s ease; */
            }

            .medicine-item:last-child {
                border-bottom: none;
            }

            .medicine-item:hover {
                background: #f8fafc;
            }

            .medicine-number {
                /* Converted to Tailwind in HTML */
                /* width: 40px;
                                        height: 40px;
                                        background: #3b82f6;
                                        color: white;
                                        border-radius: 50%;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-weight: 600;
                                        font-size: 1rem;
                                        flex-shrink: 0; */
            }

            .medicine-info {
                flex: 1;
                min-width: 0;
            }

            .medicine-name {
                margin: 0 0 0.25rem 0;
                color: #1f2937;
                font-size: 1.125rem;
                font-weight: 600;
            }

            .medicine-description {
                margin: 0;
                color: #6b7280;
                font-size: 0.875rem;
                line-height: 1.4;
            }

            .medicine-details {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .detail-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                min-width: 80px;
            }

            .detail-label {
                font-size: 0.75rem;
                color: #6b7280;
                margin-bottom: 0.25rem;
                text-transform: uppercase;
                font-weight: 500;
                letter-spacing: 0.025em;
            }

            .detail-value {
                font-weight: 600;
                padding: 0.375rem 0.75rem;
                border-radius: 8px;
                font-size: 0.875rem;
            }

            .detail-value.dosage {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }

            .detail-value.unit {
                background: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
            }

            .detail-value.quantity {
                background: rgba(139, 92, 246, 0.1);
                color: #8b5cf6;
            }

            /* Responsive */
            @media (max-width: 768px) {

                /* .prescription-header { padding: 1.5rem 0; } */
                /* .header-content { flex-direction: column; text-align: center; gap: 1rem; } */
                .info-grid {
                    grid-template-columns: 1fr;
                    gap: 1rem;
                }

                .medicine-item {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 1rem;
                }

                .medicine-details {
                    justify-content: center;
                }

                /* .actions-grid { grid-template-columns: 1fr; } */
            }

            /* Print Styles */
            @media print {
                body {
                    background: white !important;
                }

                #app {
                    /* Assuming #app is your main container */
                    display: block !important;
                }

                .min-h-screen,
                .container,
                .flex {
                    display: block !important;
                    width: 100% !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    height: auto !important;
                }

                .lg\:w-1\/4,
                .lg\:w-3\/4 {
                    /* Hide sidebar and make main content full width */
                    display: none !important;
                    width: 100% !important;
                }

                .bg-gray-50,
                .shadow-lg,
                .border,
                .rounded-xl {
                    background: none !important;
                    box-shadow: none !important;
                    border: none !important;
                    border-radius: 0 !important;
                }

                .mb-8,
                .pb-6 {
                    margin-bottom: 1rem !important;
                    padding-bottom: 0.5rem !important;
                }

                .border-b {
                    border-bottom: 1px solid #e5e7eb !important;
                }

                .prescription-detail-container {
                    background: white !important;
                    padding: 0 !important;
                    min-height: auto !important;
                }

                .prescription-header {
                    background: white !important;
                    color: black !important;
                    border-bottom: 2px solid #e5e7eb !important;
                    padding: 1rem 0 !important;
                    margin-bottom: 1rem !important;
                }

                .status-badge {
                    display: none !important;
                    /* Hide status badge in print */
                }

                .sidebar-card,
                .actions-grid,
                .summary-grid {
                    display: none !important;
                }

                .col-lg-4 {
                    display: none !important;
                }

                .col-lg-8 {
                    width: 100% !important;
                    flex: none !important;
                    max-width: 100% !important;
                }

                .medicines-card,
                .info-card {
                    box-shadow: none !important;
                    border: 1px solid #e5e7eb !important;
                    margin-bottom: 1rem !important;
                    padding: 1.5rem !important;
                }

                .medicines-header {
                    background: #f8fafc !important;
                    padding: 1rem 1.5rem !important;
                    border-bottom: 1px solid #e5e7eb !important;
                }

                .medicine-item {
                    padding: 1rem 1.5rem !important;
                }

                .action-btn {
                    display: none !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Khởi tạo lại Lucide icons nếu chúng được thêm động hoặc nếu trang này tải qua AJAX
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            function downloadPrescription() {
                // Đây là nơi bạn sẽ gọi API backend để tạo và tải xuống PDF.
                // Hoặc sử dụng thư viện JavaScript như jsPDF để tạo PDF từ nội dung HTML.

                // Ví dụ đơn giản: Mở một cửa sổ mới để in và cho phép người dùng lưu PDF
                window.print();
                alert(
                    'Tính năng tải xuống PDF đang được phát triển. Vui lòng sử dụng chức năng "In" của trình duyệt để lưu trang này dưới dạng PDF.'
                );
                // Để tạo PDF thực sự, bạn sẽ cần một endpoint backend
                // Ví dụ: window.location.href = '/api/prescriptions/' + {{ $prescription->id }} + '/download-pdf';
            }
        </script>
    @endpush
@endsection
