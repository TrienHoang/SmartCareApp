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

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('doctor.working_schedules.update', $schedule->id) }}" method="POST">
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
                    <select name="room_id" class="form-select @error('room_id') is-invalid @enderror">
                        <option value="">-- Chọn phòng --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}"
                                {{ old('room_id', $schedule->room->id) == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
            if (!isNaN(date.getTime())) {
                const dayIndex = date.getDay();
                weekdayInput.value = days[dayIndex];
            } else {
                weekdayInput.value = '';
            }
        }

        document.getElementById('day').addEventListener('change', updateWeekday);
        window.addEventListener('DOMContentLoaded', updateWeekday);
    </script>
@endsection
