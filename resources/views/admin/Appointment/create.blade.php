@extends('admin.dashboard')
@section('title', 'Tạo lịch hẹn khám mới')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Appointments /</span> Tạo lịch hẹn khám mới
        </h4>

        <div class="card">
            <div class="card-header">
                <h5>Tạo lịch hẹn</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.appointments.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">

                        <!-- Bệnh nhân -->
                        <div class="col-12 col-md-6">
                            <label for="patient_name" class="form-label">Bệnh nhân</label>
                            <input type="text" id="patient_name" name="patient_name"
                                class="form-control @error('patient_id') is-invalid @enderror"
                                placeholder="Tìm bệnh nhân theo tên..." autocomplete="off"
                                value="{{ old('patient_name') }}">
                            <input type="hidden" name="patient_id" id="patient_id_hidden" value="{{ old('patient_id') }}">
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kế hoạch điều trị -->
                        <div class="col-12 col-md-6" id="treatment-plan-wrapper" style="display: none;">
                            <label for="treatment_plan_id" class="form-label">Kế hoạch điều trị (nếu có)</label>
                            <select name="treatment_plan_id" id="treatment_plan_id"
                                class="form-select @error('treatment_plan_id') is-invalid @enderror">
                                <option value="">-- Không chọn --</option>
                                {{-- các option được load động bằng JS --}}
                            </select>
                            @error('treatment_plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bác sĩ -->
                        <div class="col-12 col-md-6">
                            <label for="doctor_id" class="form-label">Bác sĩ</label>
                            <select name="doctor_id" id="doctor_id"
                                class="form-select @error('doctor_id') is-invalid @enderror">
                                <option value="">Chọn bác sĩ</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dịch vụ -->
                        <div class="col-12 col-md-6">
                            <label for="service_id" class="form-label">Dịch vụ</label>
                            <select name="service_id" id="service_id" data-old="{{ old('service_id') }}"
                                class="form-select @error('service_id') is-invalid @enderror">
                                <option value="">-- Vui lòng chọn bác sĩ --</option>
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Thời gian hẹn -->
                        <div class="col-12 col-md-6">
                            <label for="appointment_time" class="form-label">Thời gian hẹn</label>
                            <input type="text" id="appointment_time" name="appointment_time"
                                class="form-control @error('appointment_time') is-invalid @enderror"
                                value="{{ old('appointment_time', isset($appointment) ? Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i') : '') }}"
                                placeholder="Chọn ngày và giờ" readonly>
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Trạng thái -->
                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select name="status" id="status" class="form-select" disabled>
                                <option value="pending" selected>Chờ xác nhận</option>
                                <option value="confirmed" disabled>Đã xác nhận</option>
                                <option value="cancelled" disabled>Đã hủy</option>
                            </select>
                            <input type="hidden" name="status" value="pending">
                        </div>


                        <!-- Lý do khám -->
                        <div class="col-12">
                            <label for="reason" class="form-label">Lý do khám (nếu có)</label>
                            <input type="text" name="reason" id="reason"
                                class="form-control @error('reason') is-invalid @enderror" value="{{ old('reason') }}">
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="col-12 d-flex flex-wrap justify-content-between mt-3">
                            <button type="submit" class="btn btn-primary mb-2">Tạo lịch hẹn</button>
                            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary mb-2">Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Truyền URL cho JS --}}
    <script>
        window.doctorServicesUrl = '{{ url('admin/appointments/doctor') }}/:id/services';
        window.doctorWorkingDaysUrl = '{{ url('admin/appointments/doctor') }}/:id/working-days';
        window.treatmentPlanDetailsUrl = '{{ route('admin.appointments.treatment-plan.details', ['id' => '__ID__']) }}';

        window.treatmentPlansByPatientUrl = '{{ route('admin.appointments.treatment-plans.by-patient', ':id') }}';
    </script>

    {{-- Flatpickr Vietnamese --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/vi.js"></script>

    {{-- JS Logic --}}
    <script src="{{ asset('js/Appointment/create.js') }}"></script>

    {{-- Khởi tạo Select2 --}}
    <script>
        $(document).ready(function() {
            $('#doctor_id, #service_id, #status, #treatment_plan_id').select2({
                width: '100%',
                placeholder: 'Chọn mục...',
                allowClear: true
            });
        });
    </script>
@endpush
