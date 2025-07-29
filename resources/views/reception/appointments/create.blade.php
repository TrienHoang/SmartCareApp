@extends('reception.dashboard')
@section('title', 'Tạo lịch hẹn khám mới')

@push('styles')
    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

    {{-- jQuery UI CSS (Autocomplete) --}}
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush



@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Quản lý lịch hẹn /</span> Tạo lịch hẹn khám mới
        </h4>

        <div class="card">
            <div class="card-header">
                <h5>Tạo lịch hẹn</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('receptionist.appointments.store') }}" method="POST">
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

                        <!-- Dịch vụ -->
                        <div class="col-12 col-md-6">
                            <label for="service_id" class="form-label">Dịch vụ</label>
                            <select name="service_id" id="service_id" data-old="{{ old('service_id') }}"
                                class="form-select @error('service_id') is-invalid @enderror">
                                <option value="">-- Chọn dịch vụ --</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                                        {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} ({{ $service->department->name ?? 'Không rõ khoa' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bác sĩ -->
                        <div class="col-12 col-md-6">
                            <label for="doctor_id" class="form-label">Bác sĩ</label>
                            <select name="doctor_id" id="doctor_id"
                                class="form-select @error('doctor_id') is-invalid @enderror">
                                <option value="">-- Vui lòng chọn dịch vụ --</option>
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="service_price" class="form-label">Giá dịch vụ</label>
                            <input type="text" id="service_price" class="form-control" readonly>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="appointment_date" class="form-label">Ngày khám</label>
                            <input type="text" id="appointment_date" name="appointment_date" class="form-control"
                                placeholder="Chọn ngày khám" readonly>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="appointment_slot" class="form-label">Giờ khám</label>
                            <select id="appointment_slot" name="appointment_time" class="form-select">
                                <option value="">Chọn giờ</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="payment_method">Phương thức thanh toán</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="cash">Tiền mặt</option>
                                <option value="card">Thẻ</option>
                                <option value="bank">Chuyển khoản</option>
                            </select>
                            <div id="payment_note" class="text-muted small mt-1">
                                Thanh toán sẽ được xử lý ngay nếu là tiền mặt. Đối với thẻ/chuyển khoản, cần xác nhận thanh
                                toán sau.
                            </div>
                        </div>

                        <!-- Trạng thái -->
                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label">Trạng thái</label>
                            <div id="status_display" class="fw-bold">Đã xác nhận</div>
                            <input type="hidden" name="status" value="confirmed">
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
    {{-- jQuery (nếu chưa có, chỉ load 1 lần) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- jQuery UI (Autocomplete) --}}
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    {{-- Flatpickr Vietnamese --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/vi.js"></script>

    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Select2 Tiếng Việt (tuỳ chọn) --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/vi.js"></script>

    {{-- Feather Icons (nếu cần) --}}
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof feather !== 'undefined') feather.replace();
        });
    </script>

    {{-- Truyền URL cho JS --}}
    <script>
        window.doctorServicesUrl = '{{ url('receptionist/appointments/doctor') }}/:id/services';
        window.doctorWorkingDaysUrl = '{{ url('receptionist/appointments/doctor') }}/:id/working-days';
        window.serviceDoctorsUrl = '{{ url('receptionist/appointments/services') }}/:id/doctors';
    </script>

    {{-- JS Logic --}}
    <script src="{{ asset('js/Reception/create.js') }}"></script>

    {{-- Khởi tạo Select2 --}}
    <script>
        $(document).ready(function() {
            $('#doctor_id, #service_id, #status').select2({
                width: '100%',
                placeholder: 'Chọn mục...',
                allowClear: true,
                language: 'vi' // tuỳ chọn
            });
        });
    </script>
@endpush
