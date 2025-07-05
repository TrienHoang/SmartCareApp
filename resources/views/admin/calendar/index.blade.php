@extends('admin.dashboard')

@section('content')
    <h4 class="fw-bold mb-4"><i class="bx bx-calendar"></i> Lịch làm việc bác sĩ</h4>

    {{-- Bộ lọc --}}
    <form id="filterForm" class="row g-3 mb-4">
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
                <button type="button" class="btn btn-primary w-100" id="btnSearch">🔍 Tìm</button>
                <button type="button" class="btn btn-secondary w-100" id="btnReset">♻️ Đặt lại</button>
            </div>
        </div>
    </form>

    {{-- Debug Info (Chỉ hiển thị khi APP_DEBUG=true) --}}
    {{-- @if(config('app.debug'))
        <div class="alert alert-info" id="debugInfo" style="display: none;">
            <h6>Debug Information:</h6>
            <div id="debugContent"></div>
        </div>
    @endif --}}

    {{-- Lịch --}}
    <div id="calendar" style="height: 700px;"></div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
@endpush

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

                    // Debug: Log request parameters
                    console.log('📤 Request Parameters:', params);
                    
                    // Debug: Show loading state
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
                    
                    if (info.event.url) {
                        window.open(info.event.url, '_blank');
                        info.jsEvent.preventDefault();
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
                
                // Test endpoint without filters
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