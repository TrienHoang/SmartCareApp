@extends('admin.dashboard')

@section('title', 'Tạo Lịch làm việc')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-12">
            <h2><i class="bx bx-calendar-plus text-primary me-2"></i> Tạo mới Lịch làm việc</h2>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.schedules.index') }}">Lịch làm việc</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo mới</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('admin.schedules.store') }}" method="POST" id="formLichLamViec">
        @csrf
        <div class="row">
            <div class="col-md-8">
                {{-- Bác sĩ --}}
                <div class="mb-3">
                    <label for="doctor_id" class="form-label">Bác sĩ <span class="text-danger">*</span></label>
                    <select class="form-select" name="doctor_id" id="doctor_id">
                        <option value="">-- Chọn bác sĩ --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Ngày làm việc --}}
                <div class="mb-3">
                    <label for="day" class="form-label">Ngày làm việc <span class="text-danger">*</span></label>
                    <input type="date" name="day" id="day" class="form-control" value="{{ old('day') }}">
                    <input type="hidden" name="day_of_week" id="day_of_week" value="{{ old('day_of_week') }}">
                    <select class="form-select mt-2" id="thu_hien_thi" disabled>
                        <option value="">-- Thứ trong tuần --</option>
                        <option value="Thứ hai">Thứ Hai</option>
                        <option value="Thứ ba">Thứ Ba</option>
                        <option value="Thứ tư">Thứ Tư</option>
                        <option value="Thứ năm">Thứ Năm</option>
                        <option value="Thứ sáu">Thứ Sáu</option>
                        <option value="Thứ bảy">Thứ Bảy</option>
                        <option value="Chủ nhật">Chủ Nhật</option>
                    </select>
                    @error('day')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Giờ bắt đầu --}}
                <div class="mb-3">
                    <label for="start_time" class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                    <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}">
                    @error('start_time')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Giờ kết thúc --}}
                <div class="mb-3">
                    <label for="end_time" class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
                    <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}">
                    @error('end_time')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                {{-- Hành động --}}
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Hành động</h6>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bx bx-save me-1"></i> Tạo lịch
                        </button>
                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bx bx-arrow-back me-1"></i> Quay lại
                        </a>
                    </div>
                </div>

                {{-- Mẹo --}}
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bx bx-bulb me-1"></i> Mẹo</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0 ps-3">
                            <li>Không chọn Chủ Nhật.</li>
                            <li>Không chọn ngày đã qua.</li>
                            <li>Ca làm không vượt quá 8 tiếng.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dayInput = document.getElementById('day');
    const dayOfWeekInput = document.getElementById('day_of_week');
    const dayDisplay = document.getElementById('thu_hien_thi');
    const doctorInput = document.getElementById('doctor_id');
    const startInput = document.getElementById('start_time');
    const endInput = document.getElementById('end_time');

    const map = {
        0: 'Chủ nhật',
        1: 'Thứ hai',
        2: 'Thứ ba',
        3: 'Thứ tư',
        4: 'Thứ năm',
        5: 'Thứ sáu',
        6: 'Thứ bảy'
    };

    const existingSchedules = @json($existingSchedules);

    function setDayOfWeek(dateStr) {
        const date = new Date(dateStr);
        if (!isNaN(date)) {
            const weekday = map[date.getDay()];
            dayDisplay.value = weekday;
            dayOfWeekInput.value = weekday;
        }
    }

    function validateDay() {
        const selectedDoctor = doctorInput.value;
        const selectedDay = dayInput.value;

        if (!selectedDoctor || !selectedDay) return;

        const today = new Date();
        const selectedDate = new Date(selectedDay);
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
            alert("Không thể chọn ngày trong quá khứ.");
            resetDay();
            return;
        }

        if (selectedDate.getDay() === 0) {
            alert("Không thể chọn Chủ Nhật.");
            resetDay();
            return;
        }

        const existingDays = existingSchedules[selectedDoctor] || [];
        if (existingDays.includes(selectedDay)) {
            alert("Bác sĩ đã có lịch làm việc vào ngày này.");
            resetDay();
            return;
        }

        setDayOfWeek(selectedDay);
    }

    function resetDay() {
        dayInput.value = '';
        dayOfWeekInput.value = '';
        dayDisplay.value = '';
    }

    dayInput.addEventListener('change', validateDay);
    doctorInput.addEventListener('change', validateDay);

    document.getElementById('formLichLamViec').addEventListener('submit', function (e) {
        const [h1, m1] = startInput.value.split(':');
        const [h2, m2] = endInput.value.split(':');
        const totalMinutes = (h2 * 60 + +m2) - (h1 * 60 + +m1);

        if (totalMinutes > 480) {
            if (!confirm("Ca làm vượt quá 8 tiếng. Bạn có chắc muốn tiếp tục?")) {
                e.preventDefault();
            }
        }
    });

    if (dayInput.value) setDayOfWeek(dayInput.value);
});
</script>
@endsection
