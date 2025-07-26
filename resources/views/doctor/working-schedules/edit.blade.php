@extends('doctor.dashboard')

@section('title', 'Chỉnh sửa lịch làm việc')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="form-container">
            <h3 class="mb-4">Chỉnh sửa lịch làm việc</h3>

            <div id="alert-area"></div>

            <form id="updateScheduleForm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tên bác sĩ</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->full_name }}" disabled>
                </div>

                <!-- Room select -->
                <div class="mb-3">
                    <label for="room_id" class="form-label fw-semibold">
                        <i class="fas fa-door-open text-secondary me-1"></i>Phòng thực hiện
                    </label>
                    <select name="room_id" id="room_id" class="form-select">
                        <option value="">-- Chọn phòng --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}"
                                {{ old('room_id', $schedule->room_id) == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="day" class="form-label">Ngày làm việc</label>
                    <input type="date" id="day" name="day" class="form-control"
                        value="{{ old('day', $schedule->day) }}" required>
                </div>

                <div class="mb-3">
                    <label for="weekday" class="form-label">Thứ</label>
                    <input type="text" id="weekday" class="form-control" value="" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ca làm</label>
                    <div class="d-flex flex-column gap-2">
                        @foreach ($shifts as $shift)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="shift_id"
                                    id="shift_{{ $shift->id }}" value="{{ $shift->id }}"
                                    {{ $shift->id == old('shift_id', $schedule->shift_id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="shift_{{ $shift->id }}">
                                    {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('doctor.working_schedules.index') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Cập nhật lịch</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateWeekday() {
            const dayInput = document.getElementById('day');
            const weekdayInput = document.getElementById('weekday');
            const days = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
            const date = new Date(dayInput.value);
            weekdayInput.value = isNaN(date.getTime()) ? '' : days[date.getDay()];
        }

        document.getElementById('day').addEventListener('change', updateWeekday);
        window.addEventListener('DOMContentLoaded', updateWeekday);

        document.getElementById('updateScheduleForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const alertArea = document.getElementById('alert-area');
            alertArea.innerHTML = '';

            // Lấy shift_id duy nhất từ checkbox được check
            const shiftCheckbox = document.querySelector('input[name="shift_id"]:checked');
            if (!shiftCheckbox) {
                alertArea.innerHTML = `<div class="alert alert-danger">Vui lòng chọn một ca làm việc.</div>`;
                return;
            }
            formData.set('shift_id', shiftCheckbox.value);

            try {
                const response = await fetch("{{ route('doctor.working_schedules.update', $schedule->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    const errorMessages = result.errors
                        ? Object.values(result.errors).flat().join('<br>')
                        : result.error;
                    alertArea.innerHTML = `<div class="alert alert-danger">${errorMessages}</div>`;
                    return;
                }

                alertArea.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                setTimeout(() => {
                    window.location.href = "{{ route('doctor.working_schedules.index') }}";
                }, 1500);

            } catch (err) {
                alertArea.innerHTML = `<div class="alert alert-danger">Lỗi gửi dữ liệu: ${err.message}</div>`;
            }
        });
    </script>
@endsection
