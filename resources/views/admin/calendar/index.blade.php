
@extends('admin.dashboard')

@section('content')
<style>
    /* Container chính với màu nhạt */
    .calendar-container {
        background: linear-gradient(135deg, #60A5FA, #FEF3C7); /* Xanh dương nhạt và vàng nhạt */
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        margin: 3rem 1rem;
    }

    .calendar-container:hover {
        transform: translateY(-12px);
        box-shadow: 0 18px 36px rgba(0, 0, 0, 0.3);
    }

    /* Header lịch */
    .calendar-header {
        background: linear-gradient(to right, #3B82F6, #FBBF24); /* Xanh dương và vàng nhạt */
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        color: #FFFFFF; /* Chữ trắng */
    }

    .calendar-header h4 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    }

    /* Form bộ lọc */
    .filter-card {
        background: #FEE2E2; /* Đỏ nhạt */
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filter-card:hover {
        transform: scale(1.02);
    }

    .form-label {
        color: #1F2937; /* Xám đậm */
        font-weight: 600;
    }

    .form-select {
        border: 1px solid #60A5FA; /* Xanh dương nhạt */
        border-radius: 6px;
        background: #BFDBFE; /* Xanh dương rất nhạt */
        color: #1F2937;
    }

    .form-select:focus {
        border-color: #3B82F6; /* Xanh dương đậm */
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    .btn-search, .btn-reset {
        border-radius: 8px;
        padding: 0.8rem 1.8rem;
        font-size: 1rem;
        transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-search {
        background: #3B82F6; /* Xanh dương */
        color: #FFFFFF;
    }

    .btn-search:hover {
        background: #2563EB; /* Xanh dương đậm */
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-reset {
        background: #FCA5A5; /* Đỏ nhạt */
        color: #FFFFFF;
    }

    .btn-reset:hover {
        background: #F87171; /* Đỏ đậm hơn */
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    /* FullCalendar */
    #calendar {
        max-width: 100%;
        height: 700px;
        border-radius: 12px;
        background: #FFFFFF; /* Trắng */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .fc-daygrid-day-number {
        font-size: 1.2rem;
        font-weight: 500;
        color: #1F2937; /* Xám đậm */
        transition: color 0.3s ease;
    }

    .fc-daygrid-day-number:hover {
        color: #3B82F6; /* Xanh dương */
    }

    .fc-event {
        border-radius: 6px;
        font-size: 1rem;
        padding: 6px;
        cursor: pointer;
        color: #FFFFFF;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .fc-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    /* Sự kiện công việc (đỏ nhạt) */
    .fc-event.task {
        background: #FCA5A5;
        border: 1px solid #F87171;
    }

    /* Sự kiện cuộc hẹn (xanh dương nhạt) */
    .fc-event.appointment {
        background: #60A5FA;
        border: 1px solid #3B82F6;
    }

    .fc-button {
        border-radius: 6px !important;
        background: #FBBF24 !important; /* Vàng nhạt */
        border: none !important;
        color: #1F2937 !important; /* Xám đậm */
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .fc-button:hover {
        background: #F59E0B !important; /* Vàng đậm */
        transform: translateY(-1px);
    }

    /* Debug Info */
    .debug-info {
        background: #BFDBFE; /* Xanh dương rất nhạt */
        border: 1px solid #3B82F6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .debug-info h6 {
        color: #1F2937; /* Xám đậm */
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .calendar-container {
            padding: 1.5rem;
            margin: 1.5rem 1rem;
        }

        .calendar-header h4 {
            font-size: 1.6rem;
        }

        #calendar {
            height: 500px;
        }

        .fc-daygrid-day-number {
            font-size: 1rem;
        }

        .fc-event {
            font-size: 0.9rem;
            padding: 4px;
        }

        .btn-search, .btn-reset {
            font-size: 0.9rem;
            padding: 0.6rem 1.2rem;
        }
    }

    @media (max-width: 576px) {
        .calendar-header h4 {
            font-size: 1.3rem;
        }

        #calendar {
            height: 400px;
        }

        .btn-search, .btn-reset {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }
    }
</style>

<div class="calendar-container">
    <!-- Header -->
    <div class="calendar-header">
        <h4><i class="bx bx-calendar me-2"></i>Lịch làm việc bác sĩ</h4>
    </div>

    <!-- Bộ lọc -->
    <div class="filter-card">
        <form id="filterForm" class="row g-3">
            <div class="col-md-3">
                <label for="filterType" class="form-label">Loại sự kiện:</label>
                <select id="filterType" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="task">🗂️ Công việc</option>
                    <option value="appointment">🩺 Cuộc hẹn</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filterDepartment" class="form-label">Phòng ban:</label>
                <select id="filterDepartment" class="form-select">
                    <option value="">-- Tất cả --</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filterUser" class="form-label">Bác sĩ:</label>
                <select id="filterUser" class="form-select">
                    <option value="">-- Tất cả --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="d-flex gap-2 w-100">
                    <button type="button" class="btn btn-search w-100" id="btnSearch">🔍 Tìm</button>
                    <button type="button" class="btn btn-reset w-100" id="btnReset">♻️ Đặt lại</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Debug Info (Chỉ hiển thị khi APP_DEBUG=true) -->
    @if(config('app.debug'))
        <div class="debug-info" id="debugInfo" style="display: none;">
            <h6>Debug Information:</h6>
            <div id="debugContent"></div>
        </div>
    @endif

    <!-- Lịch -->
    <div id="calendar"></div>
</div>
@endsection

@push('scripts')
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
        events: function (fetchInfo, successCallback, failureCallback) {
            const params = {
                type: document.getElementById('filterType').value,
                department_id: document.getElementById('filterDepartment').value,
                user_id: document.getElementById('filterUser').value,
                start: fetchInfo.startStr,
                end: fetchInfo.endStr,
            };

            console.log('📤 Request Parameters:', params);
            
            const debugInfo = document.getElementById('debugInfo');
            const debugContent = document.getElementById('debugContent');
            
            @if(config('app.debug'))
                if (debugInfo && debugContent) {
                    debugInfo.style.display = 'block';
                    debugContent.innerHTML = `
                        <strong>Loading events...</strong><br>
                        Start: ${params.start}<br>
                        End: ${params.end}<br>
                        Type: ${params.type || 'All'}<br>
                        Department: ${params.department_id || 'All'}<br>
                        User: ${params.user_id || 'All'}
                    `;
                }
            @endif

            axios.get("{{ route('admin.calendar.events') }}", { params })
                .then(response => {
                    console.log('✅ Events loaded successfully:', response.data);
                    
                    @if(config('app.debug'))
                        if (debugContent) {
                            debugContent.innerHTML = `
                                <strong>✅ Events loaded: ${response.data.length}</strong><br>
                                <details>
                                    <summary>Click to view events data</summary>
                                    <pre>${JSON.stringify(response.data, null, 2)}</pre>
                                </details>
                            `;
                        }
                    @endif

                    // Thêm class cho sự kiện để áp dụng màu
                    response.data.forEach(event => {
                        if (event.id.startsWith('task_')) {
                            event.classNames = ['task'];
                        } else if (event.id.startsWith('appt_')) {
                            event.classNames = ['appointment'];
                        }
                    });
                    successCallback(response.data);
                })
                .catch(error => {
                    console.error('❌ Error loading events:', error);
                    
                    @if(config('app.debug'))
                        if (debugContent) {
                            debugContent.innerHTML = `
                                <strong>❌ Error loading events</strong><br>
                                Status: ${error.response?.status || 'Unknown'}<br>
                                Message: ${error.response?.data?.message || error.message}<br>
                                <details>
                                    <summary>Full error details</summary>
                                    <pre>${JSON.stringify(error.response?.data || error, null, 2)}</pre>
                                </details>
                            `;
                        }
                    @endif

                    failureCallback(error);
                });
        },
        eventClick: function (info) {
            console.log('🖱️ Event clicked:', info.event);
            
            if (info.event.url && info.event.url !== '#') {
                window.open(info.event.url, '_blank');
                info.jsEvent.preventDefault();
            } else {
                console.warn('Invalid event URL:', info.event.url);
                alert('Không thể xem chi tiết sự kiện do URL không hợp lệ.');
            }
        },
        loading: function(bool) {
            console.log('📊 Calendar loading:', bool);
        },
        eventDidMount: function(info) {
            console.log('📅 Event mounted:', info.event.title);
        }
    });

    calendar.render();

    // Tìm kiếm
    document.getElementById('btnSearch').addEventListener('click', function () {
        console.log('🔍 Search button clicked');
        calendar.refetchEvents();
    });

    // Reset bộ lọc
    document.getElementById('btnReset').addEventListener('click', function () {
        console.log('♻️ Reset button clicked');
        document.getElementById('filterType').value = '';
        document.getElementById('filterDepartment').value = '';
        document.getElementById('filterUser').value = '';
        calendar.refetchEvents();
    });

    // Debug: Test API endpoint directly
    @if(config('app.debug'))
        console.log('🔧 Debug mode enabled');
        
        setTimeout(() => {
            console.log('🧪 Testing API endpoint...');
            axios.get("{{ route('admin.calendar.events') }}", {
                params: {
                    start: '2024-01-01',
                    end: '2024-12-31'
                }
            })
            .then(response => {
                console.log('🧪 API Test Result:', response.data);
            })
            .catch(error => {
                console.error('🧪 API Test Error:', error);
            });
        }, 1000);
    @endif
});
</script>
@endpush
