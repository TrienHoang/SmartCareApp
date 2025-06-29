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

                    <!-- Bác sĩ -->
                    <div class="col-12 col-md-6">
                        <label for="doctor_id" class="form-label">Bác sĩ</label>
                        <select name="doctor_id" id="doctor_id"
                            class="form-select @error('doctor_id') is-invalid @enderror">
                            <option value="">Chọn bác sĩ</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
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
                        <select name="service_id" id="service_id"
                            class="form-select @error('service_id') is-invalid @enderror">
                            <option value="">Chọn dịch vụ</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} ({{ $service->department->name ?? 'Không rõ khoa' }})
                                </option>
                            @endforeach
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
                            value="{{ old('appointment_time') }}" placeholder="Chọn ngày và giờ">
                        @error('appointment_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-12 col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">Chọn trạng thái</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lý do khám -->
                    <div class="col-12">
                        <label for="reason" class="form-label">Lý do khám (nếu có)</label>
                        <input type="text" name="reason" id="reason"
                            class="form-control @error('reason') is-invalid @enderror"
                            value="{{ old('reason') }}">
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
<script src="{{ asset('js/Appointment/create.js') }}"></script>
    <script>
        // Khởi tạo Flatpickr
        flatpickr("#appointment_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today",
            locale: "vn",
            disableMobile: true

        });

        // Khởi tạo Select2
        $(document).ready(function () {
            $('#doctor_id, #service_id, #status').select2({
                width: '100%',
                placeholder: 'Chọn mục...',
                allowClear: true
            });
        });
    </script>
@endpush
