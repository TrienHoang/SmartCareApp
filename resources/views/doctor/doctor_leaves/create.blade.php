@extends('doctor.dashboard')

@section('title', 'Đăng ký lịch nghỉ')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .form-container {
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .doctor-info {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    .emergency-warning, .no-replacement-warning {
        color: #dc3545;
        font-size: 0.9rem;
        margin-top: 4px;
    }
    .disabled-select {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <h3 class="mb-4 text-primary">Đăng ký lịch nghỉ</h3>

    {{-- Thông tin bác sĩ --}}
    <div class="doctor-info mb-4 p-4">
        <h5 class="text-secondary mb-3">Thông tin bác sĩ</h5>
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2"><strong>Họ tên:</strong> {{ Auth::user()->full_name }}</p>
                <p class="mb-2"><strong>Tên đăng nhập:</strong> {{ Auth::user()->username }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Khoa:</strong> {{ Auth::user()->doctor->department->name ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>
    </div>

    {{-- Thông báo lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form đăng ký --}}
    <div class="form-container">
        <form method="POST" action="{{ route('doctor.leaves.store') }}" id="leaveForm" novalidate>
            @csrf

            <div class="row g-3">
                <!-- Ngày bắt đầu -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        <div class="invalid-feedback">Vui lòng chọn ngày bắt đầu nghỉ.</div>
                    </div>
                </div>

                <!-- Ngày kết thúc -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                        <div class="invalid-feedback">Vui lòng chọn ngày kết thúc nghỉ (tối đa 3 ngày).</div>
                    </div>
                </div>

                <!-- Lý do -->
                <div class="col-12">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do nghỉ <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" class="form-control" rows="5" required>{{ old('reason') }}</textarea>
                        <div class="invalid-feedback">Vui lòng nêu rõ lý do nghỉ của bạn.</div>
                    </div>
                </div>

                <!-- Chọn loại nghỉ -->
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">Loại nghỉ <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input type="radio" name="leave_type" id="normal" value="normal" class="form-check-input" {{ old('leave_type', 'normal') === 'normal' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="normal">Nghỉ thường</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="leave_type" id="vacation" value="vacation" class="form-check-input" {{ old('leave_type') === 'vacation' ? 'checked' : '' }}>
                                <label class="form-check-label" for="vacation">Nghỉ du lịch</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="leave_type" id="emergency" value="emergency" class="form-check-input" {{ old('leave_type') === 'emergency' ? 'checked' : '' }}>
                                <label class="form-check-label" for="emergency">Nghỉ đột xuất</label>
                            </div>
                        </div>
                        <div class="invalid-feedback">Vui lòng chọn loại nghỉ.</div>
                        <div id="emergency-warning" class="emergency-warning" style="display: none;">
                            Lưu ý: Nếu không chọn được bác sĩ thay thế hợp lệ, các cuộc hẹn sẽ bị hủy và bệnh nhân sẽ được thông báo để đặt lại hoặc hủy lịch.
                        </div>
                    </div>
                </div>

                <!-- Bác sĩ thay thế -->
                <div class="col-12" id="replacement-doctor-group" style="display: {{ old('leave_type') === 'emergency' ? 'block' : 'none' }};">
                    <div class="mb-3">
                        <label for="replacement_doctor_id" class="form-label">Chọn bác sĩ thay thế</label>
                        <select name="replacement_doctor_id" id="replacement_doctor_id" class="form-select" @if($doctors->isEmpty()) disabled class="disabled-select" @endif>
                            <option value="">-- Chọn bác sĩ --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('replacement_doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->user->full_name }} ({{ $doctor->department->name ?? 'Chưa cập nhật' }})
                                </option>
                            @endforeach
                        </select>
                        @if($doctors->isEmpty())
                            <div class="no-replacement-warning">
                                Không có bác sĩ thay thế sẵn có. Nếu bạn tiếp tục, các cuộc hẹn sẽ bị hủy và bệnh nhân sẽ được thông báo.
                            </div>
                        @else
                            <div class="invalid-feedback">Vui lòng chọn một bác sĩ thay thế cho nghỉ đột xuất.</div>
                        @endif
                        <small class="form-text text-muted">Chọn bác sĩ trong cùng khoa để thay thế cho lịch hẹn.</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-success">Gửi đơn nghỉ</button>
                <a href="{{ route('doctor.leaves.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('leaveForm');
        const doctorGroup = document.getElementById('replacement-doctor-group');
        const radios = document.querySelectorAll('input[name="leave_type"]');
        const warning = document.getElementById('emergency-warning');
        const replacementSelect = document.getElementById('replacement_doctor_id');

        function toggleDoctorDropdown() {
            const selectedType = document.querySelector('input[name="leave_type"]:checked')?.value;
            doctorGroup.style.display = selectedType === 'emergency' ? 'block' : 'none';
            warning.style.display = selectedType === 'emergency' ? 'block' : 'none';
            replacementSelect.required = selectedType === 'emergency' && !replacementSelect.disabled;
            form.classList.add('was-validated'); // Trigger validation styles
        }

        // Handle form submission
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });

        radios.forEach(radio => {
            radio.addEventListener('change', toggleDoctorDropdown);
        });

        toggleDoctorDropdown(); // Run on page load
    });
</script>
@endsection
