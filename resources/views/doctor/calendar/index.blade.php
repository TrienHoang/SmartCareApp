@extends('doctor.dashboard')


@section('content')
<style>
:root {
    /* --- Bảng màu "Blue Ocean" --- */
    --ocean-primary: #0852f2; /* Blue-600 */
    --ocean-primary-dark: #1d4ed8; /* Blue-700 */
    --ocean-bg-start: #bfdbfe; /* Blue-200 */
    --ocean-bg-end: #eff6ff; /* Blue-50 */
    --ocean-card-bg: rgba(255, 255, 255, 0.85);
    --ocean-glass-bg: rgba(255, 255, 255, 0.6);
    --ocean-text-dark: #1e3a8a; /* Blue-900 */
    --ocean-text-muted: #3b82f6; /* Blue-500 */
    --ocean-border: rgba(255, 255, 255, 0.9);
    --ocean-border-light: #dbeafe; /* Blue-100 */

    /* Event Status Colors (Adjusted for contrast) */
    --color-red-bg: #fee2e2;
    --color-red-border: #dc2626;
    --color-red-text: #b91c1c;

    --color-yellow-bg: #fef3c7;
    --color-yellow-border: #f59e0b;
    --color-yellow-text: #b45309;

    --color-green-bg: #dcfce7;
    --color-green-border: #16a34a;
    --color-green-text: #15803d;
    
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.07);
    --shadow-lg-ocean: 0 10px 15px -3px rgba(37, 99, 235, 0.1), 0 4px 6px -4px rgba(37, 99, 235, 0.1);
}

/* --- Animated Background "4D" Effect --- */
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
    perspective: 1500px; /* Creates 3D space for children */
}

/* --- Search Section "Glassmorphism" Effect --- */
.search-section {
    background: var(--ocean-glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid var(--ocean-border);
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease-out;
}

.search-section-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--ocean-text-dark);
}
/* Other styles from before... */
.search-section-header { display: flex; align-items: center; margin-bottom: 20px; }
.search-section-header svg { width: 20px; height: 20px; margin-right: 10px; color: var(--ocean-primary); }
.search-form { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
.form-group { flex: 1; min-width: 180px; }
.form-group label { color: var(--ocean-text-dark); font-weight: 500; margin-bottom: 8px; font-size: 0.875rem; display: block; }
.form-control { width: 100%; padding: 10px 12px; border: 1px solid var(--ocean-border-light); border-radius: 8px; font-size: 0.95rem; background-color: rgba(255, 255, 255, 0.5); transition: all 0.2s ease-in-out; }
.form-control:focus { outline: none; border-color: var(--ocean-primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2); background-color: white; }

/* --- Buttons with 3D Hover Effect --- */
.btn { padding: 10px 20px; border: 1px solid transparent; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease-out; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; }
.btn-search { background: var(--ocean-primary); color: white; box-shadow: var(--shadow-md); }
.btn-search:hover { background: var(--ocean-primary-dark); transform: translateY(-3px) scale(1.05); box-shadow: var(--shadow-lg-ocean); }
.btn-reset { background: var(--ocean-card-bg); color: var(--ocean-text-muted); border: 1px solid var(--ocean-border-light); }
.btn-reset:hover { background: white; color: var(--ocean-text-dark); border-color: #a7c5eb; transform: translateY(-2px); }

/* --- Calendar Card with 3D Hover Effect --- */
.calendar-card {
    background: var(--ocean-card-bg);
    backdrop-filter: blur(5px);
    border: 1px solid var(--ocean-border);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-lg-ocean);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Bouncy transition */
}
.calendar-card:hover {
    transform: rotateX(5deg) rotateY(-5deg) scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.25);
}

.calendar-card-header { padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--ocean-border-light); }
.calendar-title { display: flex; align-items: center; gap: 12px; color: var(--ocean-text-dark); }
.calendar-title svg { width: 22px; height: 22px; color: var(--ocean-primary); }
.calendar-title h4 { margin: 0; font-size: 1.25rem; font-weight: 600; }
#refreshCalendar { background: transparent; color: var(--ocean-text-muted); border: none; border-radius: 50%; padding: 8px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; }
#refreshCalendar svg { width: 20px; height: 20px; transition: transform 0.5s ease; }
#refreshCalendar:hover { background: rgba(37, 99, 235, 0.1); color: var(--ocean-primary); }
#refreshCalendar:hover svg { transform: rotate(360deg); }

.calendar-card-body { position: relative; }
#calendar { padding: 20px; }
#calendarError { border-left: 4px solid #ef4444; background: rgba(239, 68, 68, 0.1); color: #c53030; padding: 12px 16px; margin: 20px; border-radius: 4px; font-size: 0.9rem; }

/* Loading Spinner (Ocean Themed) */
.loading .calendar-card-body::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.7); z-index: 10; backdrop-filter: blur(2px); }
.loading .calendar-card-body::after { content: ''; position: absolute; top: 50%; left: 50%; width: 32px; height: 32px; margin: -16px 0 0 -16px; border: 4px solid var(--ocean-border-light); border-top: 4px solid var(--ocean-primary); border-radius: 50%; animation: spin 1s linear infinite; z-index: 11; }
@keyframes spin { 0% { transform: rotate(0deg); } 80% { transform: rotate(360deg); } }

/* --- FullCalendar Event Styling --- */
.fc-event {
    border-radius: 6px !important;
    padding: 4px 8px !important;
    font-weight: 500 !important;
    font-size: 0.85rem !important;
    cursor: pointer;
    box-shadow: var(--shadow-md) !important;
    transition: all 0.2s ease-out;
}
.fc-event:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
}

/* Default Appointment Color */
.fc-event.appointment {
    background-color: var(--ocean-primary) !important;
    border: 1px solid var(--ocean-primary-dark) !important;
    color: white !important;
}
/* Status Colors */
.fc-event.event-past, .fc-event.appointment.event-past { background-color: var(--color-red-bg) !important; border: 1px solid var(--color-red-border) !important; color: var(--color-red-text) !important; }
.fc-event.event-near-due, .fc-event.appointment.event-near-due { background-color: var(--color-yellow-bg) !important; border: 1px solid var(--color-yellow-border) !important; color: var(--color-yellow-text) !important; }
.fc-event.event-normal { background-color: var(--color-green-bg) !important; border: 1px solid var(--color-green-border) !important; color: var(--color-green-text) !important; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .calendar-container { padding: 16px; perspective: none; }
    .search-form { flex-direction: column; align-items: stretch; }
    .calendar-card:hover { transform: none; } /* Disable 3D effect on mobile for performance */
}
</style>

<!-- HTML Markup (Giữ nguyên cấu trúc) -->
<div class="calendar-container">
    <!-- Search Form -->
    <div class="search-section">
        <div class="search-section-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <h5>Bộ lọc tìm kiếm</h5>
        </div>
        <form class="search-form" id="searchForm">
            <!-- Form groups ... -->
            <div class="form-group">
                <label for="searchKeyword">Từ khóa</label>
                <input type="text" id="searchKeyword" class="form-control" placeholder="Tên công việc, nhân viên...">
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
                </select>
            </div>
            <div class="btn-group">
                 <button type="submit" class="btn btn-search">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                    <span>Tìm</span>
                </button>
                <button type="button" class="btn btn-reset" id="resetSearch">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201-4.42 5.5 5.5 0 011.663-1.43l.865.865A4.25 4.25 0 006.5 9.883a4.25 4.25 0 008.463.385.75.75 0 00-.66-1.074.75.75 0 00-1.073.662A2.75 2.75 0 018.75 9.883a2.75 2.75 0 01-2.635-4.149l.942.942a.75.75 0 001.06-1.06l-2.25-2.25a.75.75 0 00-1.06 0l-2.25 2.25a.75.75 0 101.06 1.06l.942-.942A4.002 4.002 0 0110 5.883a4.002 4.002 0 013.248 6.335.75.75 0 00.964.906z" clip-rule="evenodd" /><path d="M13.28 11.72a.75.75 0 00-1.06-.02l-2.22 2.22v-3.67a.75.75 0 00-1.5 0v3.67l-2.22-2.22a.75.75 0 00-1.06 1.06l3.5 3.5a.75.75 0 001.06 0l3.5-3.5a.75.75 0 00.02-1.06z" /></svg>
                    <span>Reset</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Calendar -->
    <div class="calendar-card">
        <div class="calendar-card-header">
            <div class="calendar-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 12.75h.008v.008H12v-.008z" /></svg>
                <h4>Lịch làm việc</h4>
            </div>
            <button id="refreshCalendar" aria-label="Làm mới lịch">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.664 0l3.181-3.183m-11.664 0l4.992-4.993m-4.993 0l-3.181 3.183a8.25 8.25 0 000 11.664l3.181 3.183" /></svg>
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
{{-- JavaScript không thay đổi logic, chỉ cần đảm bảo nó hoạt động với HTML và CSS mới --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Toàn bộ JavaScript từ lần trước được giữ nguyên ở đây
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendarBody = document.querySelector('.calendar-card-body');
        const errorDiv = document.getElementById('calendarError');
        const refreshBtn = document.getElementById('refreshCalendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'vi',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
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
                        const errorMsg = error.response?.data?.error || 'Không thể tải lịch hẹn.';
                        errorDiv.innerText = errorMsg;
                        errorDiv.classList.remove('d-none');
                        failureCallback(error);
                    });
            },
            eventDataTransform: function(eventInfo) {
                const NEAR_DUE_DAYS = 3;
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const eventDate = new Date(eventInfo.start);
                eventDate.setHours(0, 0, 0, 0);
                const diffTime = eventDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                let statusClassName = '';
                if (diffDays < 0) { statusClassName = 'event-past'; }
                else if (diffDays >= 0 && diffDays < NEAR_DUE_DAYS) { statusClassName = 'event-near-due'; }

                let finalClassNames = [...(eventInfo.classNames || [])];
                if (eventInfo.id && String(eventInfo.id).startsWith('appt_')) {
                    finalClassNames.push('appointment');
                }
                if(statusClassName) {
                    finalClassNames.push(statusClassName);
                }
                if (!finalClassNames.includes('appointment') && !statusClassName) {
                    finalClassNames.push('event-normal');
                }
                eventInfo.classNames = finalClassNames;
                return eventInfo;
            },
            eventClick: function (info) {
                info.jsEvent.preventDefault();
                if (info.event.url) { window.location.href = info.event.url; }
            },
            loading: function (isLoading) {
                calendarBody.classList.toggle('loading', isLoading);
                if (isLoading) errorDiv.classList.add('d-none');
            },
        });
        calendar.render();
        document.getElementById('searchForm').addEventListener('submit', (e) => { e.preventDefault(); calendar.refetchEvents(); });
        document.getElementById('resetSearch').addEventListener('click', () => { document.getElementById('searchForm').reset(); calendar.refetchEvents(); });
        refreshBtn.addEventListener('click', () => calendar.refetchEvents());
    });
</script>
@endpush