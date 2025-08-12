@extends('doctor.dashboard')
@section('content')
    <style>
        :root {
            /* --- Bảng màu "Blue Ocean" --- */
            --ocean-primary: #0852f2;
            --ocean-primary-dark: #1d4ed8;
            --ocean-bg-start: #bfdbfe;
            --ocean-bg-end: #eff6ff;
            --ocean-card-bg: rgba(255, 255, 255, 0.85);
            --ocean-glass-bg: rgba(255, 255, 255, 0.6);
            --ocean-text-dark: #1e3a8a;
            --ocean-text-muted: #3b82f6;
            --ocean-border: rgba(255, 255, 255, 0.9);
            --ocean-border-light: #dbeafe;

            /* Event Status Colors */
            --color-pending-bg: #fef3c7;
            --color-pending-border: #f59e0b;
            --color-pending-text: #b45309;
            --color-confirmed-bg: #dcfce7;
            --color-confirmed-border: #16a34a;
            --color-confirmed-text: #15803d;
            --color-completed-bg: #e0f2fe;
            --color-completed-border: #0284c7;
            --color-completed-text: #0369a1;
            --color-cancelled-bg: #fee2e2;
            --color-cancelled-border: #dc2626;
            --color-cancelled-text: #b91c1c;
            --color-no-show-bg: #e9d5ff;
            --color-no-show-border: #6f42c1;
            --color-no-show-text: #5b21b6;
            --color-rescheduled-bg: #ffedd5;
            --color-rescheduled-border: #fd7e14;
            --color-rescheduled-text: #c05621;

            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.07);
            --shadow-lg-ocean: 0 10px 15px -3px rgba(37, 99, 235, 0.1), 0 4px 6px -4px rgba(37, 99, 235, 0.1);
        }

        /* Animated Background */
        @keyframes animated-ocean {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            background: linear-gradient(-45deg, var(--ocean-bg-start), var(--ocean-bg-end), #1a76ed, #dbeafe);
            background-size: 400% 400%;
            animation: animated-ocean 20s ease infinite;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .calendar-container {
            padding: 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Search Section */
        .search-section {
            background: var(--ocean-glass-bg);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid var(--ocean-border);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease-out;
        }

        .search-section-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-section-header svg {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            color: var(--ocean-primary);
        }

        .search-section-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--ocean-text-dark);
        }

        .search-form {
            display: flex;
            gap: 16px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            color: var(--ocean-text-dark);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.875rem;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--ocean-border-light);
            border-radius: 8px;
            font-size: 0.95rem;
            background-color: rgba(255, 255, 255, 0.5);
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--ocean-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            background-color: white;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease-out;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-search {
            background: var(--ocean-primary);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-search:hover {
            background: var(--ocean-primary-dark);
            transform: translateY(-2px);
        }

        .btn-reset {
            background: var(--ocean-card-bg);
            color: var(--ocean-text-muted);
            border: 1px solid var(--ocean-border-light);
        }

        .btn-reset:hover {
            background: white;
            color: var(--ocean-text-dark);
            transform: translateY(-2px);
        }

        /* Calendar Card */
        .calendar-card {
            background: var(--ocean-card-bg);
            backdrop-filter: blur(5px);
            border: 1px solid var(--ocean-border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg-ocean);
            transition: all 0.3s ease-out;
        }

        .calendar-card-header {
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--ocean-border-light);
        }

        .calendar-title {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--ocean-text-dark);
        }

        .calendar-title svg {
            width: 22px;
            height: 22px;
            color: var(--ocean-primary);
        }

        .calendar-title h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        #refreshCalendar {
            background: transparent;
            color: var(--ocean-text-muted);
            border: none;
            border-radius: 50%;
            padding: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #refreshCalendar svg {
            width: 20px;
            height: 20px;
            transition: transform 0.5s ease;
        }

        #refreshCalendar:hover {
            background: rgba(37, 99, 235, 0.1);
            color: var(--ocean-primary);
        }

        #refreshCalendar:hover svg {
            transform: rotate(360deg);
        }

        .calendar-card-body {
            position: relative;
            padding: 20px;
        }

        #calendarError {
            border-left: 4px solid #ef4444;
            background: rgba(239, 68, 68, 0.1);
            color: #c53030;
            padding: 12px 16px;
            margin: 20px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        /* Loading Spinner */
        .loading .calendar-card-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            z-index: 10;
            backdrop-filter: blur(2px);
        }

        .loading .calendar-card-body::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 32px;
            height: 32px;
            margin: -16px 0 0 -16px;
            border: 4px solid var(--ocean-border-light);
            border-top: 4px solid var(--ocean-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 11;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* FullCalendar Styling */
        .fc {
            font-size: 0.9rem;
        }

        .fc .fc-daygrid-day {
            background: white;
            border: 1px solid var(--ocean-border-light);
            transition: background 0.2s ease;
        }

        .fc .fc-daygrid-day:hover {
            background: rgba(37, 99, 235, 0.05);
        }

        .fc .fc-daygrid-day.fc-day-today {
            background: rgba(37, 99, 235, 0.1);
        }

        .fc .fc-timegrid-slot {
            height: 2.5em;
            border-color: var(--ocean-border-light);
        }

        .fc .fc-timegrid-col {
            background: white;
        }

        .fc .fc-timegrid-col.fc-day-today {
            background: rgba(37, 99, 235, 0.1);
        }

        .fc .fc-daygrid-day-number,
        .fc .fc-timegrid-slot-label {
            color: var(--ocean-text-dark);
        }

        .fc .fc-col-header-cell {
            background: var(--ocean-bg-end);
            color: var(--ocean-text-dark);
            font-weight: 600;
        }

        /* Event Styling */
        .fc-event {
            border-radius: 6px !important;
            padding: 4px 8px !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            cursor: pointer;
            box-shadow: var(--shadow-md) !important;
            transition: all 0.2s ease-out;
            line-height: 1.4 !important;
        }

        .fc-event:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15) !important;
        }

        /* Status-specific Event Colors */
        .fc-event.event-pending {
            background-color: var(--color-pending-bg) !important;
            border: 1px solid var(--color-pending-border) !important;
            color: var(--color-pending-text) !important;
        }

        .fc-event.event-confirmed {
            background-color: var(--color-confirmed-bg) !important;
            border: 1px solid var(--color-confirmed-border) !important;
            color: var(--color-confirmed-text) !important;
        }

        .fc-event.event-completed {
            background-color: var(--color-completed-bg) !important;
            border: 1px solid var(--color-completed-border) !important;
            color: var(--color-completed-text) !important;
        }

        .fc-event.event-cancelled {
            background-color: var(--color-cancelled-bg) !important;
            border: 1px solid var(--color-cancelled-border) !important;
            color: var(--color-cancelled-text) !important;
        }

        .fc-event.event-no-show {
            background-color: var(--color-no-show-bg) !important;
            border: 1px solid var(--color-no-show-border) !important;
            color: var(--color-no-show-text) !important;
        }

        .fc-event.event-rescheduled {
            background-color: var(--color-rescheduled-bg) !important;
            border: 1px solid var(--color-rescheduled-border) !important;
            color: var(--color-rescheduled-text) !important;
        }

        /* View-specific Adjustments */
        .fc-dayGridMonth-view .fc-event {
            padding: 2px 4px !important;
            font-size: 0.8rem !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fc-timeGridWeek-view .fc-event,
        .fc-timeGridDay-view .fc-event {
            padding: 6px 8px !important;
            font-size: 0.9rem !important;
            display: flex;
            align-items: center;
            white-space: normal;
        }

        .fc-timeGridWeek-view .fc-event-main,
        .fc-timeGridDay-view .fc-event-main {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .calendar-container {
                padding: 16px;
            }

            .search-form {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group {
                min-width: 100%;
            }

            .fc .fc-daygrid-day-number,
            .fc .fc-timegrid-slot-label {
                font-size: 0.8rem;
            }

            .fc .fc-event {
                font-size: 0.75rem !important;
            }

            .fc-timeGridWeek-view .fc-event,
            .fc-timeGridDay-view .fc-event {
                padding: 4px 6px !important;
            }
        }

        @media (max-width: 576px) {
            .fc .fc-toolbar-title {
                font-size: 1rem;
            }

            .fc .fc-button {
                padding: 4px 8px;
                font-size: 0.8rem;
            }
        }
    </style>

    <div class="calendar-container">
        <!-- Search Form -->
        <div class="search-section">
            <div class="search-section-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <h5>Bộ lọc tìm kiếm</h5>
            </div>
            <form class="search-form" id="searchForm">
                <div class="form-group">
                    <label for="searchKeyword">Từ khóa</label>
                    <input type="text" id="searchKeyword" class="form-control" placeholder="Tên bệnh nhân, dịch vụ...">
                </div>
                <div class="form-group">
                    <label for="searchDate">Ngày</label>
                    <input type="date" id="searchDate" class="form-control">
                </div>
                <div class="form-group">
                    <label for="searchStatus">Trạng thái</label>
                    <select id="searchStatus" class="form-control">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending">Chờ xử lý</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="cancelled">Đã hủy</option>
                        <option value="no_show">Không đến</option>
                        <option value="rescheduled">Đã dời lịch</option>
                    </select>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-search">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                        Tìm
                    </button>
                    <button type="button" class="btn btn-reset" id="resetSearch">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                            <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201-4.42 5.5 5.5 0 011.663-1.43l.865.865A4.25 4.25 0 006.5 9.883a4.25 4.25 0 008.463.385.75.75 0 00-.66-1.074.75.75 0 00-1.073.662A2.75 2.75 0 018.75 9.883a2.75 2.75 0 01-2.635-4.149l.942.942a.75.75 0 001.06-1.06l-2.25-2.25a.75.75 0 00-1.06 0l-2.25 2.25a.75.75 0 101.06 1.06l.942-.942A4.002 4.002 0 0110 5.883a4.002 4.002 0 013.248 6.335.75.75 0 00.964.906z" clip-rule="evenodd" />
                            <path d="M13.28 11.72a.75.75 0 00-1.06-.02l-2.22 2.22v-3.67a.75.75 0 00-1.5 0v3.67l-2.22-2.22a.75.75 0 00-1.06 1.06l3.5 3.5a.75.75 0 001.06 0l3.5-3.5a.75.75 0 00.02-1.06z" />
                        </svg>
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Calendar -->
        <div class="calendar-card">
            <div class="calendar-card-header">
                <div class="calendar-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 12.75h.008v.008H12v-.008z" />
                    </svg>
                    <h4>Lịch làm việc</h4>
                </div>
                <button id="refreshCalendar" aria-label="Làm mới lịch">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.664 0l3.181-3.183m-11.664 0l4.992-4.993m-4.993 0l-3.181 3.183a8.25 8.25 0 000 11.664l3.181 3.183" />
                    </svg>
                </button>
            </div>
            <div class="calendar-card-body">
                <div id="calendarError" class="d-none" role="alert"></div>
                <div id="calendar"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const calendarBody = document.querySelector('.calendar-card-body');
            const errorDiv = document.getElementById('calendarError');
            const refreshBtn = document.getElementById('refreshCalendar');
            const searchForm = document.getElementById('searchForm');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'vi',
                timeZone: 'Asia/Ho_Chi_Minh',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                views: {
                    dayGridMonth: {
                        eventMaxStack: 3,
                        dayMaxEvents: 3,
                        eventOrder: 'start,-duration,allDay,title',
                        dayPopoverFormat: { month: 'long', day: 'numeric', year: 'numeric' }
                    },
                    timeGridWeek: {
                        slotDuration: '00:30:00',
                        slotLabelInterval: '01:00:00',
                        slotLabelFormat: {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: false
                        },
                        allDaySlot: false,
                        eventMinHeight: 60,
                        expandRows: true
                    },
                    timeGridDay: {
                        slotDuration: '00:15:00',
                        slotLabelInterval: '01:00:00',
                        slotLabelFormat: {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: false
                        },
                        allDaySlot: false,
                        eventMinHeight: 60,
                        expandRows: true
                    }
                },
                now: '{{ Carbon\Carbon::now()->toIso8601String() }}',
                events: function (fetchInfo, successCallback, failureCallback) {
                    const keyword = document.getElementById('searchKeyword').value;
                    const date = document.getElementById('searchDate').value;
                    const status = document.getElementById('searchStatus').value;

                    const params = {
                        start: fetchInfo.startStr,
                        end: fetchInfo.endStr,
                        keyword: keyword,
                        date: date,
                        status: status
                    };

                    axios.get("{{ route('doctor.calendar.events') }}", { params })
                        .then(response => {
                            successCallback(response.data);
                            errorDiv.classList.add('d-none');
                        })
                        .catch(error => {
                            console.error('Lỗi khi tải sự kiện:', error);
                            const errorMsg = error.response?.data?.error || 'Không thể tải lịch hẹn. Vui lòng thử lại.';
                            errorDiv.innerText = errorMsg;
                            errorDiv.classList.remove('d-none');
                            failureCallback(error);
                        });
                },
                eventDataTransform: function (eventInfo) {
                    let statusClass = '';
                    switch (eventInfo.extendedProps.status) {
                        case 'Chờ xử lý':
                            statusClass = 'event-pending';
                            break;
                        case 'Đã xác nhận':
                            statusClass = 'event-confirmed';
                            break;
                        case 'Hoàn thành':
                            statusClass = 'event-completed';
                            break;
                        case 'Đã hủy':
                            statusClass = 'event-cancelled';
                            break;
                        case 'Không đến':
                            statusClass = 'event-no-show';
                            break;
                        case 'Đã dời lịch':
                            statusClass = 'event-rescheduled';
                            break;
                        default:
                            statusClass = 'event-pending';
                    }

                    let finalClassNames = [...(eventInfo.classNames || [])];
                    if (eventInfo.id && String(eventInfo.id).startsWith('appt_')) {
                        finalClassNames.push('appointment');
                    }
                    if (statusClass) {
                        finalClassNames.push(statusClass);
                    }

                    eventInfo.classNames = finalClassNames;
                    return eventInfo;
                },
                eventClick: function (info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
                eventContent: function (arg) {
                    const isMonthView = arg.view.type === 'dayGridMonth';
                    const props = arg.event.extendedProps;
                    const timeText = arg.timeText ? `${arg.timeText} ` : '';
                    const title = isMonthView
                        ? arg.event.title
                        : `${timeText}${arg.event.title}<br><small>${props.service || 'N/A'} - ${props.patient || 'N/A'}</small>`;

                    const div = document.createElement('div');
                    div.innerHTML = title;
                    div.style.overflow = 'hidden';
                    div.style.textOverflow = isMonthView ? 'ellipsis' : 'initial';
                    div.style.whiteSpace = isMonthView ? 'nowrap' : 'normal';
                    return { domNodes: [div] };
                },
                loading: function (isLoading) {
                    calendarBody.classList.toggle('loading', isLoading);
                    if (isLoading) errorDiv.classList.add('d-none');
                },
                datesSet: function (info) {
                    const viewTitle = info.view.title;
                    document.querySelector('.fc-toolbar-title').textContent = viewTitle;
                }
            });

            calendar.render();

            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                calendar.refetchEvents();
            });

            document.getElementById('resetSearch').addEventListener('click', () => {
                searchForm.reset();
                calendar.refetchEvents();
            });

            refreshBtn.addEventListener('click', () => {
                calendar.refetchEvents();
            });
        });
    </script>
@endpush