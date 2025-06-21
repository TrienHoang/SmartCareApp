@extends('admin.dashboard')
@section('title', 'Create Schedule')
@section('content')
<div class="container">
    <h1 class="mb-4">Create New Schedule</h1>
    <form action="{{ route('admin.schedules.store') }}" method="POST" id="scheduleForm">
        @csrf
        <div class="mb-3">
            <label for="doctor_id" class="form-label">Select Doctor</label>
            <select class="form-select" id="doctor_id" name="doctor_id">
                <option value="">Choose a doctor</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->user->full_name }}</option>
                @endforeach
            </select>
            @error('doctor_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="day" class="form-label">Day</label>
            <input type="date" class="form-control" id="day" name="day" value="{{ old('day') }}">
            @error('day')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="day_of_week" class="form-label">Day of Week</label>
            <select class="form-select" id="day_of_week" name="day_of_week" disabled>
                <option value="">Các ngày trong tuần</option>
                <option value="Monday">Thứ Hai</option>
                <option value="Tuesday">Thứ Ba</option>
                <option value="Wednesday">Thứ Tư</option>
                <option value="Thursday">Thứ Năm</option>
                <option value="Friday">Thứ Sáu</option>
                <option value="Saturday">Thứ Bảy</option>
                <option value="Sunday">Chủ Nhật</option>
            </select>
            @error('day_of_week')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}">
            @error('start_time')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_time" class="form-label">End Time</label>
            <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}">
            @error('end_time')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Tạo mới lịch làm việc</button>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dayInput = document.getElementById('day');
        const dayOfWeekSelect = document.getElementById('day_of_week');
        const form = document.getElementById('scheduleForm');

        const daysMap = {
            0: 'Sunday',
            1: 'Monday',
            2: 'Tuesday',
            3: 'Wednesday',
            4: 'Thursday',
            5: 'Friday',
            6: 'Saturday'
        };

        dayInput.addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            let warning = document.getElementById('sunday-warning');

            if (!isNaN(selectedDate)) {
                const dayNumber = selectedDate.getDay();
                const dayName = daysMap[dayNumber];

                if (dayName === 'Sunday') {
                    dayOfWeekSelect.value = '';
                    if (!warning) {
                        warning = document.createElement('small');
                        warning.id = 'sunday-warning';
                        warning.classList.add('text-danger', 'd-block', 'mt-1');
                        warning.innerText = 'Bác sĩ không làm việc vào Chủ Nhật.';
                        dayInput.parentNode.appendChild(warning);
                    }
                } else {
                    dayOfWeekSelect.value = dayName;
                    if (warning) warning.remove();
                }
            }
        });

        form.addEventListener('submit', function (e) {
            const selectedDate = new Date(dayInput.value);
            if (!isNaN(selectedDate) && selectedDate.getDay() === 0) {
                e.preventDefault();
                alert("Không thể tạo lịch vào Chủ Nhật. Vui lòng chọn ngày khác.");
            }
        });
    });
</script>
@endsection
