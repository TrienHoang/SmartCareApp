@extends('admin.dashboard')
@section('title', 'Tạo lịch hẹn khám mới')

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
                    <div class="mb-3">
                        <label for="patient_name" class="form-label">Bệnh nhân</label>
                        <input type="text" id="patient_name"
                            class="form-control @error('patient_id') is-invalid @enderror"
                            placeholder="Tìm bệnh nhân theo tên..." autocomplete="off" value="{{ old('patient_name') }}">
                        <input type="hidden" name="patient_id" id="patient_id_hidden" value="{{ old('patient_id') }}">
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
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

                    <div class="mb-3">
                        <label for="service_id" class="form-label">Dịch vụ</label>
                        <select name="service_id" id="service_id"
                            class="form-select @error('service_id') is-invalid @enderror">
                            <option value="">Chọn dịch vụ</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}"
                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Thời gian hẹn</label>
                        <input type="datetime-local" name="appointment_time" id="appointment_time"
                            value="{{ old('appointment_time') }}"
                            class="form-control @error('appointment_time') is-invalid @enderror">
                        @error('appointment_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">Chọn trạng thái</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                            </option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                            </option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do khám (nếu có)</label>
                        <input type="text" name="reason" id="reason" value="{{ old('reason') }}"
                            class="form-control @error('reason') is-invalid @enderror">
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Tạo lịch hẹn</button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/Appointment/create.js') }}"></script>
@endpush
