@extends('doctor.dashboard')

@section('title', 'Tạo lịch làm việc mới')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Tạo lịch làm việc mới</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Lỗi!</strong> Vui lòng kiểm tra lại thông tin.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('doctor.working_schedules.store') }}" method="POST">
            @csrf

            {{-- Ngày làm việc --}}
            <div class="mb-3">
                <label for="day" class="form-label">Ngày làm việc <span class="text-danger">*</span></label>
                <input type="date" name="day" id="day" class="form-control" value="{{ old('day') }}"
                    required>
            </div>

            {{-- Tên bác sĩ --}}
            <div class="mb-3">
                <label class="form-label">Tên bác sĩ:</label>
                <input type="text" class="form-control" value="{{ auth()->user()->doctor->user->full_name }}" readonly>
            </div>

            <!-- Room -->
            <div class="mb-3">
                <label for="room_id" class="form-label fw-semibold">
                    <i class="fas fa-door-open text-primary me-1"></i>
                    Phòng thực hiện
                </label>
                <select name="room_id" class="form-select @error('room_id') is-invalid @enderror">
                    <option value="">-- Chọn phòng --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
                @error('room_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
                                    value="{{ $shift->id }}" id="shift_{{ $shift->id }}"
                                    {{ is_array(old('shift_ids')) && in_array($shift->id, old('shift_ids')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="shift_{{ $shift->id }}">
                                    {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
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

        dayInput.addEventListener('change', function() {
            const day = new Date(this.value);
            const weekdays = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
            const weekday = weekdays[day.getDay()];
            dayOfWeekInput.value = weekday;
        });
    </script>
@endpush
