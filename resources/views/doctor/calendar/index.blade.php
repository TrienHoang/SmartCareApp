@extends('doctor.dashboard')

@section('content')
<style>
/* Simple Clean Theme */
:root {
    --ocean-blue: #1e40af;
    --ocean-dark: #1d4ed8;
    --bg-white: #ffffff;
    --bg-light: #f8fafc;
    --text-dark: #1e293b;
    --text-gray: #64748b;
    --border-light: #e2e8f0;
}

.calendar-container {
    padding: 20px;
    background: var(--bg-light);
}

/* Compact Search Form */
.search-section {
    background: var(--bg-white);
    border: 2px solid var(--ocean-blue);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.search-section h5 {
    color: var(--ocean-blue);
    margin-bottom: 15px;
    font-weight: 600;
    font-size: 1.1rem;
}

.search-form {
    display: flex;
    gap: 15px;
    align-items: end;
    flex-wrap: wrap;
}

.form-group {
    flex: 1;
    min-width: 150px;
}

.form-group label {
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 4px;
    font-size: 0.85rem;
    display: block;
}

.form-control {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid var(--border-light);
    border-radius: 6px;
    font-size: 0.9rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--ocean-blue);
}

.btn-group {
    display: flex;
    gap: 8px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-search {
    background: var(--ocean-blue);
    color: white;
}

.btn-search:hover {
    background: var(--ocean-dark);
}

.btn-reset {
    background: var(--text-gray);
    color: white;
}

.btn-reset:hover {
    background: #475569;
}

/* Calendar Card */
.calendar-card {
    background: var(--bg-white);
    border: 2px solid var(--ocean-blue);
    border-radius: 8px;
    overflow: hidden;
}

.calendar-card-header {
    background: var(--ocean-blue);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.calendar-card-header h4 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
}

#refreshCalendar {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 6px;
    padding: 8px 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

#refreshCalendar:hover {
    background: rgba(255,255,255,0.3);
}

.calendar-card-body {
    background: var(--bg-white);
}

#calendar {
    padding: 15px;
}

/* FullCalendar Styling */
.fc-button {
    background: var(--ocean-blue) !important;
    border-color: var(--ocean-blue) !important;
    color: white !important;
    border-radius: 4px !important;
    font-weight: 500 !important;
    font-size: 0.85rem !important;
}

.fc-button:hover {
    background: var(--ocean-dark) !important;
    border-color: var(--ocean-dark) !important;
}

.fc-event {
    background: var(--ocean-blue) !important;
    border-color: var(--ocean-blue) !important;
    border-radius: 4px !important;
    font-weight: 500 !important;
    font-size: 0.85rem !important;
}

.fc-event:hover {
    background: var(--ocean-dark) !important;
}

/* Search Results */
.search-results {
    background: rgba(30, 64, 175, 0.1);
    border: 1px solid var(--ocean-blue);
    border-radius: 6px;
    padding: 10px 15px;
    margin-top: 15px;
    color: var(--ocean-blue);
    font-weight: 500;
    font-size: 0.9rem;
}

/* Error Message */
#calendarError {
    border-left: 4px solid var(--ocean-blue);
    background: rgba(30, 64, 175, 0.1);
    color: var(--text-dark);
    padding: 12px 15px;
    margin: 15px;
    border-radius: 4px;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .search-form {
        flex-direction: column;
    }
    
    .form-group {
        min-width: auto;
    }
    
    .btn-group {
        justify-content: center;
    }
    
    .calendar-card-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
}

/* Loading */
.loading {
    opacity: 0.7;
}

.loading #calendar::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #ddd;
    border-top: 2px solid var(--ocean-blue);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div class="calendar-container">
    <!-- Compact Search -->
    <div class="search-section">
        <h5>🔍 Tìm kiếm</h5>
        <form class="search-form" id="searchForm">
            <div class="form-group">
                <label>Từ khóa</label>
                <input type="text" id="searchKeyword" class="form-control" placeholder="Nhập từ khóa...">
            </div>
            <div class="form-group">
                <label>Ngày</label>
                <input type="date" id="searchDate" class="form-control">
            </div>
            <div class="form-group">
                <label>Trạng thái</label>
                <select id="searchStatus" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="pending">Chờ xử lý</option>
                    <option value="confirmed">Đã xác nhận</option>
                    <option value="completed">Hoàn thành</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-search">Tìm</button>
                <button type="button" class="btn btn-reset" id="resetSearch">Reset</button>
            </div>
        </form>
        
        <div id="searchResults" class="search-results" style="display: none;">
            <span id="searchResultsText">Tìm thấy 0 kết quả</span>
        </div>
    </div>

    <!-- Calendar -->
    <div class="calendar-card">
        <div class="calendar-card-header">
            <h4>📅 Lịch làm việc</h4>
            <button id="refreshCalendar">🔄 Làm mới</button>
        </div>
        <div class="calendar-card-body">
            <div id="calendarError" class="d-none" role="alert"></div>
            <div id="calendar"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const resetBtn = document.getElementById('resetSearch');
    const refreshBtn = document.getElementById('refreshCalendar');
    const searchResults = document.getElementById('searchResults');
    const searchResultsText = document.getElementById('searchResultsText');
    const calendarContainer = document.querySelector('.calendar-container');

    // Search
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    // Reset
    resetBtn.addEventListener('click', function() {
        searchForm.reset();
        searchResults.style.display = 'none';
        refreshCalendar();
    });

    // Refresh
    refreshBtn.addEventListener('click', function() {
        refreshCalendar();
    });

    function performSearch() {
        calendarContainer.classList.add('loading');
        
        setTimeout(() => {
            const mockResults = Math.floor(Math.random() * 15) + 1;
            searchResults.style.display = 'block';
            searchResultsText.textContent = `Tìm thấy ${mockResults} kết quả`;
            calendarContainer.classList.remove('loading');
        }, 800);
    }

    function refreshCalendar() {
        calendarContainer.classList.add('loading');
        setTimeout(() => {
            calendarContainer.classList.remove('loading');
        }, 800);
    }
});
</script>

@endsection

@push('scripts')
<!-- FullCalendar và Axios -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'vi',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        // Tùy chọn ngày hiện tại
        now: '{{ Carbon\Carbon::now()->toIso8601String() }}',
        // Tải sự kiện từ API
        events: function (fetchInfo, successCallback, failureCallback) {
            const params = {
                start: fetchInfo.startStr,
                end: fetchInfo.endStr,
            };
            console.log('Gửi yêu cầu API với params:', params); // Debug params
            axios.get("{{ route('doctor.calendar.events') }}", { params })
                .then(response => {
                    console.log('Dữ liệu từ API:', response.data); // Debug dữ liệu API
                    const events = response.data.map(event => {
                        if (event.id.startsWith('appt_')) {
                            event.classNames = ['appointment'];
                        }
                        return event;
                    });
                    successCallback(events);
                    // Ẩn thông báo lỗi nếu có
                    document.getElementById('calendarError').classList.add('d-none');
                })
                .catch(error => {
                    console.error('Lỗi khi tải sự kiện:', error);
                    const errorMsg = error.response?.data?.error || 'Không thể tải lịch hẹn.';
                    document.getElementById('calendarError').innerText = errorMsg;
                    document.getElementById('calendarError').classList.remove('d-none');
                    alert('Không thể tải lịch hẹn. Vui lòng thử lại.');
                    failureCallback(error);
                });
        },
        // Xử lý click vào sự kiện
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            const url = info.event.url;
            if (url && url !== '#') {
                window.location.href = url; // Chuyển hướng trong cùng tab
            } else {
                alert('Không có trang chi tiết cho sự kiện này.');
            }
        },
        // Trạng thái loading
        loading: function (isLoading) {
            const errorDiv = document.getElementById('calendarError');
            if (isLoading) {
                console.log('Đang tải sự kiện...');
                errorDiv.classList.add('d-none');
            } else {
                console.log('Đã tải xong sự kiện.');
            }
        },
        // Debug khi sự kiện được gắn vào DOM
        eventDidMount: function (info) {
            console.log('📅 Gắn sự kiện:', info.event.title, info.event);
        },
        // Hiển thị ngày hiện tại
        dayCellContent: function (arg) {
            return { html: arg.dayNumberText.replace(' ', '') };
        }
    });

    // Render lịch
    calendar.render();

    // Xử lý nút làm mới
    document.getElementById('refreshCalendar').addEventListener('click', function () {
        calendar.refetchEvents();
        console.log('Lịch đã được làm mới.');
    });
});
</script>
@endpush