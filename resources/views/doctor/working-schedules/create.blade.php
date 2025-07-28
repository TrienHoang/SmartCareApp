@extends('doctor.dashboard')

@section('title', 'Tạo lịch làm việc mới')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Tạo lịch làm việc mới</h2>

    <div id="alert-area"></div>

    <form id="working-schedule-form">
        @csrf

        {{-- Ngày làm việc --}}
        <div class="mb-3">
            <label for="day" class="form-label">Ngày làm việc <span class="text-danger">*</span></label>
            <input type="date" name="day" id="day" class="form-control" required min="{{ now()->toDateString() }}">
        </div>

        {{-- Tên bác sĩ --}}
        <div class="mb-3">
            <label class="form-label">Tên bác sĩ:</label>
            <input type="text" class="form-control" value="{{ auth()->user()->doctor->user->full_name }}" readonly>
        </div>

        {{-- Phòng khám --}}
        <div class="mb-3">
            <label for="room_id" class="form-label fw-semibold">
                <i class="fas fa-door-open text-primary me-1"></i> Phòng thực hiện
            </label>
            <select name="room_id" class="form-select" required>
                <option value="">-- Chọn phòng --</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Thứ trong tuần --}}
        <div class="mb-3">
            <label class="form-label">Thứ:</label>
            <input type="text" id="day_of_week" class="form-control" readonly>
        </div>

        {{-- Chọn ca làm việc --}}
        <div class="mb-3">
            <label class="form-label">Chọn ca làm việc <span class="text-danger">*</span></label>
            <div class="row">
                @foreach ($shifts as $shift)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="shift_ids[]"
                                value="{{ $shift->id }}" id="shift_{{ $shift->id }}">
                            <label class="form-check-label" for="shift_{{ $shift->id }}">
                                {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Lặp lại hàng tuần --}}
        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" id="repeat_weekly" name="repeat_weekly" value="1">
            <label class="form-check-label" for="repeat_weekly">
                Lặp lại mỗi tuần
            </label>
        </div>

        <div class="mb-3" id="repeat_weeks_container" style="display:none;">
            <label class="form-label" for="repeat_weeks">Số tuần lặp <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="repeat_weeks" id="repeat_weeks" min="1" max="52" value="1">
        </div>

        {{-- Nút gửi --}}
        <button type="submit" class="btn btn-primary">Tạo lịch</button>
        <a href="{{ route('doctor.working_schedules.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const dayInput = document.getElementById('day');
    const dayOfWeekInput = document.getElementById('day_of_week');

    dayInput.addEventListener('change', function () {
        const day = new Date(this.value);
        const weekdays = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
        dayOfWeekInput.value = weekdays[day.getDay()];
    });

    // Hiện/ẩn ô số tuần lặp
    document.getElementById('repeat_weekly').addEventListener('change', function () {
        const container = document.getElementById('repeat_weeks_container');
        container.style.display = this.checked ? 'block' : 'none';
    });

    // Submit form bằng fetch API
    document.getElementById('working-schedule-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const alertArea = document.getElementById('alert-area');
        alertArea.innerHTML = '';

        try {
            const response = await fetch("{{ route('doctor.working_schedules.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Phản hồi không hợp lệ từ server.");
            }

            const result = await response.json();

            let messageHTML = `<div class="alert alert-success">${result.message}</div>`;

            if (result.duplicates?.length) {
                messageHTML += `<div class="alert alert-warning">⚠ Một số ca đã trùng lịch: ${result.duplicates.join(', ')}</div>`;
            }

            if (result.room_conflicts?.length) {
                messageHTML += `<div class="alert alert-warning">⚠ Phòng đã có người sử dụng ở các ca: ${result.room_conflicts.join(', ')}</div>`;
            }

            alertArea.innerHTML = messageHTML;
            form.reset();
            dayOfWeekInput.value = '';
            document.getElementById('repeat_weeks_container').style.display = 'none';

        } catch (error) {
            let msg = error?.message ?? 'Lỗi không xác định';
            alertArea.innerHTML = `<div class="alert alert-danger">Lỗi gửi dữ liệu: ${msg}</div>`;
        }
    });
</script>
@endpush
