@extends('client.layouts.app')

@section('title', 'Đặt lịch khám bệnh')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .date-cell.disabled {
            color: #bbb;
            background-color: #f1f1f1;
            cursor: not-allowed;
        }

        .date-cell.selected {
            background-color: #007bff;
            /* Blue for selected date */
            color: white;
            font-weight: bold;
            border-color: #007bff;
        }

        .date-cell.today {
            border: 2px solid #fd7e14;
            /* Orange border for today's date */
            background-color: #fff3e6;
            /* Lighter orange background */
            color: #e65100;
            /* Darker orange text */
        }

        .date-cell.saturday-text {
            color: #ffc107;
            /* Orange for Saturday text */
        }

        .date-cell.sunday-text {
            color: #ff3d07;
            /* Red for Sunday text */
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="flex flex-col lg:flex-row justify-center gap-6">

            {{-- Left Column: Thông tin cơ sở y tế --}}
            <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-blue-600 text-white p-4 font-bold text-lg rounded-t-xl">
                        Thông tin cơ sở y tế
                    </div>
                    <div class="p-6"> {{-- Tailwind equivalent of card-body --}}
                        <h5 class="text-xl font-semibold text-gray-800 mb-3">
                            Trung Tâm Nội Soi Tiêu Hoá Doctor Check
                            <i class="bi bi-check-circle-fill text-green-500 text-sm ml-1"></i>
                        </h5>
                        <p class="text-base text-gray-700 mb-3 flex items-start">
                            <i class="bi bi-geo-alt-fill text-blue-600 text-lg mr-2 flex-shrink-0 mt-0.5"></i>
                            429 Tô Hiến Thành, Phường 14, Quận 10, Thành phố Hồ Chí Minh
                        </p>
                        <p class="text-base text-gray-700 mb-3 flex items-start">
                            <i class="bi bi-person-fill text-blue-600 text-lg mr-2 flex-shrink-0 mt-0.5"></i>
                            Chuyên khoa: Đặt khám Bệnh Tiêu Hoá - Gan Mật
                        </p>
                        <p class="text-base text-gray-700 flex items-start">
                            <i class="bi bi-card-checklist text-blue-600 text-lg mr-2 flex-shrink-0 mt-0.5"></i>
                            Dịch vụ: Đặt khám Bệnh Tiêu Hoá - Gan Mật
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right Column: Chọn ngày khám --}}
            <div class="w-full lg:w-2/3"> {{-- Tailwind equivalent of col-lg-8 --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden"> {{-- Tailwind equivalent of card --}}
                    <div class="bg-blue-600 text-white p-4 font-bold text-xl text-center rounded-t-xl">
                        {{-- Tailwind equivalent of calendar-card-header --}}
                        Vui lòng chọn ngày khám
                    </div>
                    <div class="p-6"> {{-- Tailwind equivalent of card-body calendar-body --}}
                        {{-- Month Navigation --}}
                        <div class="flex justify-center items-center text-xl font-semibold text-gray-800 mb-6">
                            <button id="prevMonth"
                                class="bg-transparent border-none text-blue-600 text-3xl cursor-pointer p-2 hover:bg-blue-100 rounded-full">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span id="monthYearDisplay" class="mx-6 text-blue-600"></span>
                            <button id="nextMonth"
                                class="bg-transparent border-none text-blue-600 text-3xl cursor-pointer p-2 hover:bg-blue-100 rounded-full">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        {{-- Calendar Grid --}}
                        <div id="calendarGrid" class="grid grid-cols-7 gap-1 text-center">
                            <div class="font-bold text-gray-600 py-2 text-sm text-red-600">CN</div>
                            <div class="font-bold text-gray-600 py-2 text-sm">Hai</div>
                            <div class="font-bold text-gray-600 py-2 text-sm">Ba</div>
                            <div class="font-bold text-gray-600 py-2 text-sm">Tư</div>
                            <div class="font-bold text-gray-600 py-2 text-sm">Năm</div>
                            <div class="font-bold text-gray-600 py-2 text-sm">Sáu</div>
                            <div class="font-bold text-gray-600 py-2 text-sm text-yellow-600">Bảy</div>

                            {{-- Dates will be injected here by JavaScript --}}
                        </div>
                    </div>
                </div>

                {{-- Quay lại link --}}
                <div class="text-center mt-8">
                    <a href="#" class="text-blue-600 hover:underline text-base font-medium">
                        <i class="bi bi-arrow-counterclockwise mr-2"></i>Quay lại
                    </a>
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

        let currentViewDate = new Date(); // Represents the month/year currently displayed
        let selectedDate = null; // Stores the currently selected date

        const monthYearDisplay = document.getElementById('monthYearDisplay');
        const calendarGrid = document.getElementById('calendarGrid');
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');

        function renderCalendar() {
            calendarGrid.innerHTML = ''; // Clear existing dates
            // Re-add day headers (CN, Hai, Ba...) as they were cleared
            calendarGrid.innerHTML += `
                <div class="font-bold text-gray-600 py-2 text-sm text-red-600">CN</div>
                <div class="font-bold text-gray-600 py-2 text-sm">Hai</div>
                <div class="font-bold text-gray-600 py-2 text-sm">Ba</div>
                <div class="font-bold text-gray-600 py-2 text-sm">Tư</div>
                <div class="font-bold text-gray-600 py-2 text-sm">Năm</div>
                <div class="font-bold text-gray-600 py-2 text-sm">Sáu</div>
                <div class="font-bold text-gray-600 py-2 text-sm text-yellow-600">Bảy</div>
            `;


            const year = currentViewDate.getFullYear();
            const month = currentViewDate.getMonth(); // 0-indexed month

            monthYearDisplay.textContent = `${monthNames[month]} - ${year}`;

            // Get the first day of the month (0 = Sunday, 1 = Monday...)
            const firstDayOfMonth = new Date(year, month, 1).getDay();

            // Get the number of days in the current month
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Get the number of days in the previous month
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // Add leading days (from previous month)
            for (let i = 0; i < firstDayOfMonth; i++) {
                const day = daysInPrevMonth - firstDayOfMonth + i + 1;
                const cell = document.createElement('div');
                cell.classList.add('date-cell', 'disabled', 'p-3', 'bg-gray-100', 'rounded-lg', 'text-gray-400', 'text-lg',
                    'font-medium');
                cell.textContent = day;
                calendarGrid.appendChild(cell);
            }

            // Add days of the current month
            for (let day = 1; day <= daysInMonth; day++) {
                const cell = document.createElement('div');
                cell.classList.add('date-cell', 'p-3', 'bg-white', 'rounded-lg', 'text-gray-800', 'text-lg', 'font-medium',
                    'cursor-pointer', 'transition', 'duration-200', 'ease-in-out', 'hover:bg-gray-100', 'border',
                    'border-transparent');
                cell.textContent = day;

                const fullDate = new Date(year, month, day);
                fullDate.setHours(0, 0, 0, 0);
                // Add 'today' class if it's the current real date
                const today = new Date();
                if (fullDate.getDate() === today.getDate() &&
                    fullDate.getMonth() === today.getMonth() &&
                    fullDate.getFullYear() === today.getFullYear()) {
                    cell.classList.add('today');
                }

                if (fullDate.getDay() === 6) { // Saturday
                    cell.classList.add('saturday-text');
                }

                if (fullDate.getDay() === 0) { // Sunday
                    cell.classList.add('sunday-text');
                }

                // Add 'selected' class if this cell matches the globally selectedDate
                if (selectedDate &&
                    fullDate.getDate() === selectedDate.getDate() &&
                    fullDate.getMonth() === selectedDate.getMonth() &&
                    fullDate.getFullYear() === selectedDate.getFullYear()) {
                    cell.classList.add('selected');
                }


                if (fullDate.getTime() < today.getTime()) { // If the date is strictly before today
                    cell.classList.add('disabled');
                    cell.classList.remove('cursor-pointer', 'hover:bg-gray-100'); // Remove interactive styles
                    // Do NOT add event listener for disabled cells
                } else {
                    // Only add click event listener for selectable dates (today or future)
                    cell.addEventListener('click', () => {
                        if (selectedDate) {
                            const prevSelectedCell = document.querySelector('.date-cell.selected');
                            if (prevSelectedCell) {
                                prevSelectedCell.classList.remove('selected');
                            }
                        }
                        cell.classList.add('selected');
                        selectedDate = new Date(year, month,
                            day); // Store the actual selected date without time issues
                        console.log('Selected date:', selectedDate.toLocaleDateString('vi-VN'));
                        // Your logic to load available time slots for selectedDate goes here
                    });
                }

                calendarGrid.appendChild(cell);
            }

            // Add trailing days (from next month) to complete the last week
            const totalCells = firstDayOfMonth + daysInMonth;
            const remainingCells = (7 - (totalCells % 7)) % 7;
            for (let i = 1; i <= remainingCells; i++) {
                const cell = document.createElement('div');
                cell.classList.add('date-cell', 'disabled', 'p-3', 'bg-gray-100', 'rounded-lg', 'text-gray-400', 'text-lg',
                    'font-medium');
                cell.textContent = i;
                calendarGrid.appendChild(cell);
            }
        }

        // Event listeners for month navigation
        prevMonthBtn.addEventListener('click', () => {
            currentViewDate.setMonth(currentViewDate.getMonth() - 1);
            renderCalendar();
        });

        nextMonthBtn.addEventListener('click', () => {
            currentViewDate.setMonth(currentViewDate.getMonth() + 1);
            renderCalendar();
        });

        // Initial render when the page loads
        document.addEventListener('DOMContentLoaded', renderCalendar);
    </script>
@endpush
