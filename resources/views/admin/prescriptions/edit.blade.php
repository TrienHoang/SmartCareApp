@extends('admin.dashboard')
@section('title', 'Chỉnh sửa Đơn thuốc')

@push('styles')
    <!-- Flatpickr CSS (dùng mặc định, KHÔNG dùng theme material_blue để tránh lệch) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        #prescribed_at {
            font-size: 16px;
            padding: 10px 12px;
            border-radius: 8px;
            background-color: #f9fafb;
            border: 1px solid #cbd5e0;
        }

        .flatpickr-calendar {
            font-family: 'Segoe UI', sans-serif;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            z-index: 9999;
        }

        .flatpickr-months {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 0 12px !important;
        }

        .flatpickr-month {
            flex: 1;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }

        .flatpickr-prev-month,
        .flatpickr-next-month {
            padding: 4px 8px;
            cursor: pointer;
        }

        .flatpickr-weekdays {
            background-color: #2563eb;
            color: white;
        }

        .flatpickr-day.selected,
        .flatpickr-day.today {
            background-color: #2563eb;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Prescriptions /</span> Chỉnh sửa Đơn thuốc
        </h4>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Chỉnh sửa Đơn thuốc</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.prescriptions.update', $prescription->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="medical_record_id" class="form-label fw-semibold">Hồ sơ bệnh án</label>
                        <select name="medical_record_id" id="medical_record_id" class="form-select" disabled>
                            @foreach ($medicalRecords as $record)
                                <option value="{{ $record->id }}"
                                    {{ $prescription->medical_record_id == $record->id ? 'selected' : '' }}>
                                    {{ $record->id }} - {{ $record->appointment->patient->full_name ?? 'Bệnh nhân' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="medical_record_id" value="{{ $prescription->medical_record_id }}">
                    </div>

                    <div class="mb-3">
                        <label for="prescribed_at" class="form-label fw-semibold">Ngày kê toa</label>
                        <input type="text" name="prescribed_at" id="prescribed_at"
                            class="form-control flatpickr @error('prescribed_at') is-invalid @enderror"
                            value="{{ old('prescribed_at', \Carbon\Carbon::parse($prescription->prescribed_at)->format('Y-m-d H:i')) }}"
                            placeholder="Chọn ngày và giờ">
                        @error('prescribed_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-semibold">Ghi chú</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $prescription->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3">Thuốc được kê</h5>

                    <div id="medicine-list">
                        @foreach ($prescription->items as $index => $item)
                            <div class="medicine-item border rounded p-3 mb-3 shadow-sm bg-light position-relative">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-medicine-btn" aria-label="Xóa"></button>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Tên thuốc</label>
                                        <select name="medicines[{{ $index }}][medicine_id]" class="form-select">
                                            <option value="">-- Chọn thuốc --</option>
                                            @foreach ($medicines as $med)
                                                <option value="{{ $med->id }}" {{ $item->medicine_id == $med->id ? 'selected' : '' }}>
                                                    {{ $med->name }} ({{ $med->unit }}) - {{ $med->formatted_price }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Số lượng</label>
                                        <input type="number" name="medicines[{{ $index }}][quantity]" class="form-control" value="{{ $item->quantity }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Hướng dẫn sử dụng</label>
                                        <textarea name="medicines[{{ $index }}][usage_instructions]" rows="2" class="form-control">{{ $item->usage_instructions }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <script>let medicineIndex = {{ count($prescription->items) }};</script>
                    </div>

                    <button type="button" onclick="addMedicine()" class="btn btn-outline-secondary mb-4">
                        + Thêm thuốc
                    </button>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-danger">
                            <i class="bx bx-arrow-back"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Cập nhật
                        </button>
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
        const medicineOptions = @json($medicines);
        flatpickr("#prescribed_at", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today",
            disableMobile: true,
            appendTo: document.body
        });

        
    </script>
    <script src="{{ asset('js/Prescription/edit.js') }}"></script>
@endpush
