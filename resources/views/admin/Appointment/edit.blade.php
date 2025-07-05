@extends('admin.dashboard')
@section('title', 'Chỉnh sửa lịch hẹn khám')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        .vacation-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 0.375rem;
            padding: 0.75rem;
            margin-bottom: 1rem;
        }
        .vacation-notice .icon {
            color: #856404;
        }
        .vacation-notice .text {
            color: #856404;
            font-size: 0.875rem;
        }
        
        /* Custom styling for disabled dates in flatpickr */
        .flatpickr-day.vacation-day {
            background-color: #ffebee !important;
            color: #c62828 !important;
            border-color: #f48fb1 !important;
        }
        
        .flatpickr-day.vacation-day:hover {
            background-color: #ffcdd2 !important;
            color: #c62828 !important;
        }
    </style>
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
                <!-- Thông báo về lịch nghỉ phép -->
                <div id="vacation-notice" class="vacation-notice d-none">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle icon me-2"></i>
                        <div class="text">
                            <strong>Thông báo:</strong> <span id="vacation-text"></span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $appointment->status }}">
                    <input type="hidden" id="doctorServicesUrl"
                        value="{{ url('admin/appointments/doctor') }}/:id/services">
                    <input type="hidden" id="doctorWorkingDaysUrl"
                        value="{{ url('admin/appointments/doctor') }}/:id/working-days">

                    <div class="row g-3">
                        {{-- Bệnh nhân --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">Bệnh nhân</label>
                            <select disabled class="form-select">
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                        </div>

                        {{-- Bác sĩ --}}
                        <div class="col-12 col-md-6">
                            <label for="doctor_id" class="form-label">Bác sĩ</label>
                            <select name="doctor_id" id="doctor_id"
                                class="form-select @error('doctor_id') is-invalid @enderror">
                                <option value="">Chọn bác sĩ</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Dịch vụ --}}
                        <div class="col-12 col-md-6">
                            <label for="service_id" class="form-label">Dịch vụ</label>
                            <select name="service_id" id="service_id"
                                class="form-select @error('service_id') is-invalid @enderror"
                                data-old="{{ old('service_id', $appointment->service_id) }}">
                                <option value="">Chọn dịch vụ</option>
                                {{-- Options sẽ được đổ bằng JS --}}
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Thời gian hẹn --}}
                        <div class="col-12 col-md-6">
                            <label for="appointment_time" class="form-label">Thời gian hẹn</label>
                            <input type="text" name="appointment_time" id="appointment_time"
                                class="form-control @error('appointment_time') is-invalid @enderror"
                                value="{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i')) }}"
                                data-old="{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i')) }}"
                                placeholder="Chọn ngày và giờ" disabled>
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Các ngày nghỉ phép của bác sĩ sẽ không thể chọn được
                                </small>
                            </div>
                        </div>

                        {{-- Trạng thái (disable 1 số tuỳ chọn) --}}
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

                        {{-- Lý do --}}
                        <div class="col-12">
                            <label for="reason" class="form-label">Lý do khám (nếu có)</label>
                            <input type="text" name="reason" id="reason"
                                value="{{ old('reason', $appointment->reason) }}"
                                class="form-control @error('reason') is-invalid @enderror">
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nút --}}
                        <div class="col-12 d-flex justify-content-between mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Cập nhật
                            </button>
                            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Hủy
                            </a>
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
    <script src="{{ asset('js/Appointment/edit.js') }}"></script>
@endpush