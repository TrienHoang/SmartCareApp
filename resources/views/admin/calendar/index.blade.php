@extends('admin.dashboard')

@section('title', 'Lịch làm việc')

@section('content')
    <h4 class="fw-bold mb-4"><i class="bx bx-calendar"></i> Lịch làm việc</h4>

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
            <label for="filterUser" class="form-label">Người phụ trách:</label>
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
            let calendarEl = document.getElementById('calendar');

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: "auto",
                locale: 'vi',
                events: function(fetchInfo, successCallback, failureCallback) {
                    axios.get("{{ route('admin.calendar.events') }}", {
                        params: {
                            type: document.getElementById('filterType').value,
                            department_id: document.getElementById('filterDepartment').value,
                            user_id: document.getElementById('filterUser').value,
                        }
                    })
                    .then(response => successCallback(response.data))
                    .catch(error => failureCallback(error));
                },
                eventClick: function (info) {
                    if (info.event.url) {
                        window.open(info.event.url, '_blank');
                        info.jsEvent.preventDefault();
                    }
                }
            });

            calendar.render();

            // Tìm kiếm
            document.getElementById('btnSearch').addEventListener('click', function () {
                calendar.refetchEvents();
            });

            // Reset lọc
            document.getElementById('btnReset').addEventListener('click', function () {
                document.getElementById('filterType').value = '';
                document.getElementById('filterDepartment').value = '';
                document.getElementById('filterUser').value = '';
                calendar.refetchEvents();
            });
        });
    </script>
@endpush
