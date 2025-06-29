@extends('admin.dashboard')
@section('title', 'Chỉnh sửa lịch hẹn khám')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Appointments /</span> Chỉnh sửa lịch hẹn khám
        </h4>

        <div class="card">
            <div class="card-header">
                <h5>Chỉnh sửa lịch hẹn</h5>
            </div>

            @if (session('error'))
                <script>
                    toastr.error("{{ session('error') }}", "Lỗi");
                </script>
            @endif

            <div class="card-body">
                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $appointment->status }}">

                    <div class="row g-3">
                        <!-- Patient Selection -->
                        <div class="col-12 col-md-6">
                            <label for="patient_id" class="form-label">Bệnh nhân</label>
                            <select name="patient_id" id="patient_id"
                                class="form-select @error('patient_id') is-invalid @enderror" disabled>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Doctor Selection -->
                        <div class="col-12 col-md-6">
                            <label for="doctor_id" class="form-label">Bác sĩ</label>
                            <select name="doctor_id" id="doctor_id"
                                class="form-select @error('doctor_id') is-invalid @enderror">
                                <option value="">Chọn bác sĩ</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Service Selection -->
                        <div class="col-12 col-md-6">
                            <label for="service_id" class="form-label">Dịch vụ</label>
                            <select name="service_id" id="service_id"
                                class="form-select @error('service_id') is-invalid @enderror">
                                <option value="">Chọn dịch vụ</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ $appointment->service_id == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} ({{ $service->department->name ?? 'Không rõ khoa' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Appointment Time Input -->
                        <div class="col-12 col-md-6">
                            <label for="appointment_time" class="form-label">Thời gian hẹn</label>
                            <input type="text" name="appointment_time" id="appointment_time"
                                value="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i') }}"
                                class="form-control @error('appointment_time') is-invalid @enderror"
                                placeholder="Chọn ngày và giờ">
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Selection -->
                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}
                                    disabled>Chờ xác nhận</option>
                                <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}
                                    {{ !in_array($appointment->status, ['pending']) ? 'disabled' : '' }}>Đã xác nhận
                                </option>
                                <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}
                                    {{ $appointment->status !== 'confirmed' ? 'disabled' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}
                                    {{ !in_array($appointment->status, ['pending', 'confirmed']) ? 'disabled' : '' }}>Đã
                                    hủy</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reason Input -->
                        <div class="col-12">
                            <label for="reason" class="form-label">Lý do khám (nếu có)</label>
                            <input type="text" name="reason" id="reason"
                                value="{{ old('reason', $appointment->reason) }}"
                                class="form-control @error('reason') is-invalid @enderror">
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 d-flex flex-wrap justify-content-between mt-3">
                            <button type="submit" class="btn btn-primary mb-2">Cập nhật</button>
                            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary mb-2">Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        flatpickr("#appointment_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            // minDate: "today",
            disableMobile: true
        });

        $(document).ready(function() {
            $('#doctor_id, #service_id, #status').select2({
                width: '100%',
                placeholder: 'Chọn mục...',
                allowClear: true
            });
        });
    </script>
@endpush
