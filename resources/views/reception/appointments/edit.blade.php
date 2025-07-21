@extends('reception.dashboard')

@section('title', 'Chỉnh sửa lịch hẹn')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Quản lý lịch hẹn /</span> Chỉnh sửa lịch hẹn
        </h4>

        <div class="card">
            <div class="card-header">
                <h5>Chỉnh sửa lịch hẹn</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('receptionist.appointments.update', $appointment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="status" value="{{ $appointment->status }}">

                    <div class="row g-3">
                        <!-- Bệnh nhân -->
                        <div class="col-12 col-md-6">
                            <label for="patient_name" class="form-label">Bệnh nhân</label>
                            <input type="text" id="patient_name" name="patient_name" class="form-control" readonly
                                value="{{ $appointment->patient->full_name }}">
                            <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                        </div>


                        <!-- Bác sĩ -->
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

                        <!-- Dịch vụ -->
                        <div class="col-12 col-md-6">
                            <label for="service_id" class="form-label">Dịch vụ</label>
                            <select name="service_id" id="service_id" data-old="{{ $appointment->service_id }}"
                                class="form-select @error('service_id') is-invalid @enderror">
                                <option value="">-- Vui lòng chọn bác sĩ --</option>
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="service_price" class="form-label">Giá dịch vụ</label>
                            <input type="text" id="service_price" class="form-control" readonly
                                value="{{ number_format($appointment->service->price) }} ₫">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="appointment_date" class="form-label">Ngày khám</label>
                            <input type="text" id="appointment_date" name="appointment_date" class="form-control"
                                value="{{ old('appointment_date', $appointment->appointment_time->format('Y-m-d')) }}"
                                placeholder="Chọn ngày khám" readonly>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="appointment_slot" class="form-label">Giờ khám</label>
                            <select id="appointment_slot" name="appointment_time" class="form-select"
                                data-old="{{ old('appointment_time', $appointment->appointment_time->format('Y-m-d H:i')) }}">
                                <option
                                    value="{{ old('appointment_time', $appointment->appointment_time->format('Y-m-d H:i')) }}"
                                    selected>
                                    {{ $appointment->appointment_time->format('H:i') }}
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="payment_method">Phương thức thanh toán</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="cash"
                                    {{ old('payment_method', $appointment->payment->payment_method) == 'cash' ? 'selected' : '' }}>
                                    Tiền mặt</option>
                                <option value="card"
                                    {{ old('payment_method', $appointment->payment->payment_method) == 'card' ? 'selected' : '' }}>
                                    Thẻ</option>
                                <option value="bank"
                                    {{ old('payment_method', $appointment->payment->payment_method) == 'bank' ? 'selected' : '' }}>
                                    Chuyển khoản</option>
                            </select>
                            <div id="payment_note" class="text-muted small mt-1">
                                Thanh toán sẽ được xử lý ngay nếu là tiền mặt. Đối với thẻ/chuyển khoản, cần xác nhận sau.
                            </div>
                        </div>

                        <!-- Trạng thái -->
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

                        <!-- Lý do khám -->
                        <div class="col-12">
                            <label for="reason" class="form-label">Lý do khám (nếu có)</label>
                            <input type="text" name="reason" id="reason"
                                class="form-control @error('reason') is-invalid @enderror"
                                value="{{ old('reason', $appointment->reason) }}">
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 d-flex flex-wrap justify-content-between mt-3">
                            <button type="submit" class="btn btn-primary mb-2">Cập nhật lịch hẹn</button>
                            <a href="{{ route('receptionist.appointments.index') }}"
                                class="btn btn-secondary mb-2">Hủy</a>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/vi.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/vi.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof feather !== 'undefined') feather.replace();
        });
    </script>

    <script>
        window.doctorServicesUrl = '{{ url('receptionist/appointments/doctor') }}/:id/services';
        window.doctorWorkingDaysUrl = '{{ url('receptionist/appointments/doctor') }}/:id/working-days';
    </script>

    <script src="{{ asset('js/Reception/edit.js') }}"></script>

    <script>
        window.currentAppointmentId = {{ $appointment->id }};
    </script>

    <script>
        $(document).ready(function() {
            $('#doctor_id, #service_id, #status').select2({
                width: '100%',
                placeholder: 'Chọn mục...',
                allowClear: true,
                language: 'vi'
            });
        });
    </script>
@endpush
