@extends('admin.dashboard')
@section('title', 'Chỉnh sửa lịch hẹn khám')

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
                toastr.error("{{ session('error') }}", "Lỗi");
            @endif
            <div class="card-body">
                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $appointment->status }}">
                    <div class="mb-3">
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

                    <div class="mb-3">
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

                    <div class="mb-3">
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

                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Thời gian hẹn</label>
                        <input type="datetime-local" name="appointment_time" id="appointment_time"
                            value="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d\TH:i') }}"
                            class="form-control @error('appointment_time') is-invalid @enderror">
                        @error('appointment_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        @php
                            $currentStatus = $appointment->status;
                        @endphp
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">

                            <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }} disabled>
                                Chờ xác nhận
                            </option>

                            <option value="confirmed" {{ $currentStatus === 'confirmed' ? 'selected' : '' }}
                                {{ !in_array($currentStatus, ['pending']) ? 'disabled' : '' }}>
                                Đã xác nhận
                            </option>

                            <option value="completed" {{ $currentStatus === 'completed' ? 'selected' : '' }}
                                {{ $currentStatus !== 'confirmed' ? 'disabled' : '' }}>
                                Hoàn thành
                            </option>

                            <option value="cancelled" {{ $currentStatus === 'cancelled' ? 'selected' : '' }}
                                {{ !in_array($currentStatus, ['pending', 'confirmed']) ? 'disabled' : '' }}>
                                Đã hủy
                            </option>

                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do khám (nếu có)</label>
                        <input type="text" name="reason" id="reason"
                            value="{{ old('reason', $appointment->reason) }}"
                            class="form-control @error('reason') is-invalid @enderror">
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
@endsection
