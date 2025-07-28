@extends('client.layouts.app')

@section('title', 'Đặt lịch khám bệnh')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Calendar styles */
        .date-cell.disabled {
            color: #9ca3af;
            background-color: #f3f4f6;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .date-cell.selected {
            background-color: #3b82f6 !important;
            color: white !important;
            font-weight: bold;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
            transform: scale(1.05);
        }

        .date-cell.today {
            border: 2px solid #f59e0b !important;
            background-color: #fef3c7;
            color: #d97706;
            font-weight: 600;
        }

        .date-cell.saturday-text {
            color: #f59e0b;
        }

        .date-cell.sunday-text {
            color: #ef4444;
        }

        .date-cell {
            transition: all 0.2s ease-in-out;
        }

        .date-cell:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Time slot buttons - Using direct CSS instead of @apply */
        .time-slot-btn {
            display: inline-block;
            padding: 12px 16px;
            border: 2px solid #60a5fa;
            border-radius: 8px;
            background-color: white;
            color: #2563eb;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            user-select: none;
            min-width: 120px;
            font-size: 14px;
        }

        .time-slot-btn:hover:not(.disabled-slot) {
            background-color: #dbeafe;
            border-color: #3b82f6;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        }

        .time-slot-btn.selected-slot {
            background-color: #3b82f6 !important;
            color: white !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
            transform: scale(1.02);
        }

        .time-slot-btn.disabled-slot {
            background-color: #f3f4f6 !important;
            color: #9ca3af !important;
            border-color: #d1d5db !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }

        .time-slot-btn.disabled-slot:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        /* Smooth animations */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        .slide-up {
            animation: slideUp 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                opacity: 0; 
                transform: translateY(20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        /* Loading animation */
        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Card hover effects */
        .info-card {
            transition: all 0.3s ease-in-out;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Navigation buttons */
        .nav-btn {
            transition: all 0.2s ease-in-out;
        }

        .nav-btn:hover {
            background-color: #dbeafe;
            transform: scale(1.1);
        }

        /* Time slots section */
        .time-slots-enter {
            opacity: 0;
            transform: translateY(-10px);
        }

        .time-slots-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease-in-out;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .time-slot-btn {
                min-width: 100px;
                font-size: 13px;
                padding: 10px 12px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="flex flex-col lg:flex-row justify-center gap-6">

            {{-- Left Column: Thông tin cơ sở y tế --}}
            <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden info-card">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 font-bold text-lg">
                        <i class="bi bi-hospital mr-2"></i>
                        Thông tin cơ sở y tế
                    </div>
                    <div class="p-6">
                        <h2 class="font-bold text-blue-700 text-2xl lg:text-3xl mb-4">
                            {{ $service->name }}
                        </h2>
                        <div class="space-y-3">
                            <p class="text-gray-700 text-base flex items-start">
                                <i data-lucide="building-2" class="w-5 h-5 mr-3 mt-0.5 text-blue-600 flex-shrink-0"></i>
                                <span><strong>Loại dịch vụ:</strong> {{ $service->category->name }}</span>
                            </p>
                            <p class="text-gray-700 text-base flex items-start">
                                <i data-lucide="map-pin" class="w-5 h-5 mr-3 mt-0.5 text-blue-600 flex-shrink-0"></i>
                                <span><strong>Khoa:</strong> {{ $service->department->name }}</span>
                            </p>
                            <p class="text-gray-600 text-sm leading-relaxed mt-4 p-3 bg-gray-50 rounded-lg">
                                <i data-lucide="info" class="w-4 h-4 mr-2 text-blue-600 inline"></i>
                                {{ $service->description }}
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- Quay lại link --}}
                <div class="mt-6">
                    <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline text-base font-medium transition-colors duration-200">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Quay lại danh sách dịch vụ
                    </a>
                </div>
            </div>

            {{-- Right Column: Chọn ngày khám --}}
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 font-bold text-xl text-center">
                        <i class="bi bi-calendar-check mr-2"></i>
                        Vui lòng chọn ngày khám
                    </div>
                    <div class="p-6">
                        {{-- Month Navigation --}}
                        <div class="flex justify-center items-center text-xl font-semibold text-gray-800 mb-6">
                            <button id="prevMonth" class="nav-btn bg-transparent border-none text-blue-600 text-2xl cursor-pointer p-3 rounded-full hover:bg-blue-50 transition-all duration-200">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span id="monthYearDisplay" class="mx-8 text-blue-700 font-bold text-xl"></span>
                            <button id="nextMonth" class="nav-btn bg-transparent border-none text-blue-600 text-2xl cursor-pointer p-3 rounded-full hover:bg-blue-50 transition-all duration-200">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        {{-- Calendar Grid --}}
                        <div id="calendarGrid" class="grid grid-cols-7 gap-2 text-center mb-4">
                            <div class="font-bold text-gray-600 py-3 text-sm text-red-600">CN</div>
                            <div class="font-bold text-gray-600 py-3 text-sm">Hai</div>
                            <div class="font-bold text-gray-600 py-3 text-sm">Ba</div>
                            <div class="font-bold text-gray-600 py-3 text-sm">Tư</div>
                            <div class="font-bold text-gray-600 py-3 text-sm">Năm</div>
                            <div class="font-bold text-gray-600 py-3 text-sm">Sáu</div>
                            <div class="font-bold text-gray-600 py-3 text-sm text-yellow-600">Bảy</div>
                        </div>

                        {{-- Time Slots Section --}}
                        <div id="timeSlotsSection" class="mt-8 pt-6 border-t-2 border-gray-100 hidden">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                    <i class="bi bi-clock mr-2 text-blue-600"></i>
                                    Chọn giờ khám
                                </h3>
                                <button id="closeTimeSlots" class="text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium transition-colors duration-200 flex items-center">
                                    <i class="bi bi-x-lg mr-1"></i>
                                    Đóng
                                </button>
                            </div>

                            <div class="mb-8">
                                <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                    <i class="bi bi-sun mr-2 text-yellow-500"></i>
                                    Buổi sáng
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" id="morningSlots">
                                    <button type="button" class="time-slot-btn" data-time="07:00-08:00">07:00 - 08:00</button>
                                    <button type="button" class="time-slot-btn" data-time="08:00-09:00">08:00 - 09:00</button>
                                    <button type="button" class="time-slot-btn" data-time="09:00-10:00">09:00 - 10:00</button>
                                    <button type="button" class="time-slot-btn" data-time="10:00-11:00">10:00 - 11:00</button>
                                    <button type="button" class="time-slot-btn" data-time="11:00-12:00">11:00 - 12:00</button>
                                    <button type="button" class="time-slot-btn disabled-slot" data-time="06:00-07:00" disabled>06:00 - 07:00</button>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                    <i class="bi bi-sunset mr-2 text-orange-500"></i>
                                    Buổi chiều
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" id="afternoonSlots">
                                    <button type="button" class="time-slot-btn" data-time="13:00-14:00">13:00 - 14:00</button>
                                    <button type="button" class="time-slot-btn" data-time="14:00-15:00">14:00 - 15:00</button>
                                    <button type="button" class="time-slot-btn" data-time="15:00-16:00">15:00 - 16:00</button>
                                    <button type="button" class="time-slot-btn" data-time="16:00-17:00">16:00 - 17:00</button>
                                    <button type="button" class="time-slot-btn disabled-slot" data-time="12:00-13:00" disabled>12:00 - 13:00</button>
                                </div>
                            </div>

                            {{-- Booking confirmation section --}}
                            <div id="bookingConfirm" class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200 hidden">
                                <h5 class="font-semibold text-blue-800 mb-2">Thông tin đặt lịch:</h5>
                                <p class="text-blue-700 text-sm">
                                    <i class="bi bi-calendar-date mr-2"></i>
                                    <span id="selectedDateDisplay"></span>
                                </p>
                                <p class="text-blue-700 text-sm mt-1">
                                    <i class="bi bi-clock mr-2"></i>
                                    <span id="selectedTimeDisplay"></span>
                                </p>
                                <button class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                    Xác nhận đặt lịch
                                </button>
                            </div>

                            <p class="text-xs text-gray-500 mt-6 text-center italic">
                                <i class="bi bi-info-circle mr-1"></i>
                                Tất cả thời gian theo múi giờ Việt Nam GMT+7
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const monthNames = [
            "THÁNG 01", "THÁNG 02", "THÁNG 03", "THÁNG 04", "THÁNG 05", "THÁNG 06",
            "THÁNG 07", "THÁNG 08", "THÁNG 09", "THÁNG 10", "THÁNG 11", "THÁNG 12"
        ];

        let currentViewDate = new Date();
        let selectedDate = null;
        let selectedTimeSlot = null;

        const monthYearDisplay = document.getElementById('monthYearDisplay');
        const calendarGrid = document.getElementById('calendarGrid');
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');
        const timeSlotsSection = document.getElementById('timeSlotsSection');
        const closeTimeSlotsBtn = document.getElementById('closeTimeSlots');
        const bookingConfirm = document.getElementById('bookingConfirm');
        const selectedDateDisplay = document.getElementById('selectedDateDisplay');
        const selectedTimeDisplay = document.getElementById('selectedTimeDisplay');

        // Debounce function for smooth interactions
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function showTimeSlots() {
            timeSlotsSection.classList.remove('hidden');
            timeSlotsSection.classList.add('slide-up');
            setTimeout(() => {
                timeSlotsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }

        function hideTimeSlots() {
            timeSlotsSection.classList.add('hidden');
            timeSlotsSection.classList.remove('slide-up');
            bookingConfirm.classList.add('hidden');
        }

        function updateBookingConfirmation() {
            if (selectedDate && selectedTimeSlot) {
                const dateStr = selectedDate.toLocaleDateString('vi-VN', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                selectedDateDisplay.textContent = dateStr;
                selectedTimeDisplay.textContent = selectedTimeSlot.dataset.time;
                bookingConfirm.classList.remove('hidden');
                bookingConfirm.classList.add('fade-in');
            } else {
                bookingConfirm.classList.add('hidden');
            }
        }

        function renderCalendar() {
            // Add loading state
            calendarGrid.classList.add('loading');
            
            setTimeout(() => {
                calendarGrid.innerHTML = '';
                calendarGrid.innerHTML += `
                    <div class="font-bold text-gray-600 py-3 text-sm text-red-600">CN</div>
                    <div class="font-bold text-gray-600 py-3 text-sm">Hai</div>
                    <div class="font-bold text-gray-600 py-3 text-sm">Ba</div>
                    <div class="font-bold text-gray-600 py-3 text-sm">Tư</div>
                    <div class="font-bold text-gray-600 py-3 text-sm">Năm</div>
                    <div class="font-bold text-gray-600 py-3 text-sm">Sáu</div>
                    <div class="font-bold text-gray-600 py-3 text-sm text-yellow-600">Bảy</div>
                `;

                const year = currentViewDate.getFullYear();
                const month = currentViewDate.getMonth();

                monthYearDisplay.textContent = `${monthNames[month]} - ${year}`;

                const firstDayOfMonth = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const daysInPrevMonth = new Date(year, month, 0).getDate();

                const today = new Date();
                today.setHours(0, 0, 0, 0);

                // Add leading days (from previous month)
                for (let i = 0; i < firstDayOfMonth; i++) {
                    const day = daysInPrevMonth - firstDayOfMonth + i + 1;
                    const cell = document.createElement('div');
                    cell.classList.add('date-cell', 'disabled', 'p-3', 'bg-gray-50', 'rounded-lg', 'text-gray-400', 'text-base', 'font-medium');
                    cell.textContent = day;
                    calendarGrid.appendChild(cell);
                }

                // Add days of the current month
                for (let day = 1; day <= daysInMonth; day++) {
                    const cell = document.createElement('div');
                    cell.classList.add('date-cell', 'p-3', 'bg-white', 'rounded-lg', 'text-gray-800', 'text-base', 'font-medium',
                        'cursor-pointer', 'border', 'border-gray-200', 'hover:border-blue-300');
                    cell.textContent = day;

                    const fullDate = new Date(year, month, day);
                    fullDate.setHours(0, 0, 0, 0);

                    // Add 'today' class
                    if (fullDate.getTime() === today.getTime()) {
                        cell.classList.add('today');
                    }

                    // Add weekend styling
                    if (fullDate.getDay() === 6) {
                        cell.classList.add('saturday-text');
                    }
                    if (fullDate.getDay() === 0) {
                        cell.classList.add('sunday-text');
                    }

                    // Add 'selected' class
                    if (selectedDate &&
                        fullDate.getDate() === selectedDate.getDate() &&
                        fullDate.getMonth() === selectedDate.getMonth() &&
                        fullDate.getFullYear() === selectedDate.getFullYear()) {
                        cell.classList.add('selected');
                    }

                    // Disable past dates
                    if (fullDate.getTime() < today.getTime()) {
                        cell.classList.add('disabled');
                        cell.classList.remove('cursor-pointer', 'hover:border-blue-300');
                    } else {
                        // Add click event listener for selectable dates
                        cell.addEventListener('click', debounce(() => {
                            // Remove previous selection
                            const prevSelectedCell = document.querySelector('.date-cell.selected');
                            if (prevSelectedCell) {
                                prevSelectedCell.classList.remove('selected');
                            }

                            cell.classList.add('selected');
                            selectedDate = new Date(year, month, day);
                            console.log('Selected date:', selectedDate.toLocaleDateString('vi-VN'));

                            showTimeSlots();

                            // Reset selected time slot when a new date is selected
                            if (selectedTimeSlot) {
                                selectedTimeSlot.classList.remove('selected-slot');
                                selectedTimeSlot = null;
                            }

                            updateBookingConfirmation();
                        }, 100));
                    }

                    calendarGrid.appendChild(cell);
                }

                // Add trailing days (from next month)
                const totalCells = firstDayOfMonth + daysInMonth;
                const remainingCells = (7 - (totalCells % 7)) % 7;
                for (let i = 1; i <= remainingCells; i++) {
                    const cell = document.createElement('div');
                    cell.classList.add('date-cell', 'disabled', 'p-3', 'bg-gray-50', 'rounded-lg', 'text-gray-400', 'text-base', 'font-medium');
                    cell.textContent = i;
                    calendarGrid.appendChild(cell);
                }

                calendarGrid.classList.remove('loading');
                calendarGrid.classList.add('fade-in');
            }, 200);
        }

        // Event listeners for month navigation
        prevMonthBtn.addEventListener('click', debounce(() => {
            currentViewDate.setMonth(currentViewDate.getMonth() - 1);
            renderCalendar();
            hideTimeSlots();
            if (selectedTimeSlot) {
                selectedTimeSlot.classList.remove('selected-slot');
                selectedTimeSlot = null;
            }
        }, 150));

        nextMonthBtn.addEventListener('click', debounce(() => {
            currentViewDate.setMonth(currentViewDate.getMonth() + 1);
            renderCalendar();
            hideTimeSlots();
            if (selectedTimeSlot) {
                selectedTimeSlot.classList.remove('selected-slot');
                selectedTimeSlot = null;
            }
        }, 150));

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            renderCalendar();

            // Handle time slot selection
            const timeSlotButtons = document.querySelectorAll('.time-slot-btn');
            timeSlotButtons.forEach(button => {
                if (!button.disabled && !button.classList.contains('disabled-slot')) {
                    button.addEventListener('click', function() {
                        if (selectedTimeSlot) {
                            selectedTimeSlot.classList.remove('selected-slot');
                        }
                        this.classList.add('selected-slot');
                        selectedTimeSlot = this;
                        console.log('Selected time:', this.dataset.time);
                        updateBookingConfirmation();
                    });
                }
            });

            // Close time slots
            closeTimeSlotsBtn.addEventListener('click', () => {
                hideTimeSlots();
                const prevSelectedCell = document.querySelector('.date-cell.selected');
                if (prevSelectedCell) {
                    prevSelectedCell.classList.remove('selected');
                }
                selectedDate = null;
                if (selectedTimeSlot) {
                    selectedTimeSlot.classList.remove('selected-slot');
                    selectedTimeSlot = null;
                }
                updateBookingConfirmation();
            });
        });

        // Add keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !timeSlotsSection.classList.contains('hidden')) {
                closeTimeSlotsBtn.click();
            }
        });
    </script>
@endpush