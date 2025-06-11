@extends('admin.dashboard')
@section('title', 'Chỉnh sửa Đơn thuốc')

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
                        <input type="datetime-local" name="prescribed_at" id="prescribed_at"
                            value="{{ old('prescribed_at', \Carbon\Carbon::parse($prescription->prescribed_at)->format('Y-m-d\TH:i')) }}"
                            class="form-control @error('prescribed_at') is-invalid @enderror">
                        @error('prescribed_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-semibold">Ghi chú</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $prescription->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3">Thuốc được kê</h5>

                    <div id="medicine-list">
                        @foreach ($prescription->items as $index => $item)
                            <div class="medicine-item border rounded p-3 mb-3 shadow-sm bg-light">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Tên thuốc</label>
                                        <select name="medicines[{{ $index }}][medicine_id]" class="form-select" required>
                                            <option value="">-- Chọn thuốc --</option>
                                            @foreach ($medicines as $med)
                                                <option value="{{ $med->id }}"
                                                    {{ $item->medicine_id == $med->id ? 'selected' : '' }}>
                                                    {{ $med->name }} ({{ $med->unit }})
                                                    - {{ $med->formatted_price }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Số lượng</label>
                                        <input type="number" name="medicines[{{ $index }}][quantity]"
                                            class="form-control" min="1" value="{{ $item->quantity }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Hướng dẫn sử dụng</label>
                                        <textarea name="medicines[{{ $index }}][usage_instructions]" rows="2"
                                            class="form-control">{{ $item->usage_instructions }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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

    <script>
        const medicineOptions = @json($medicines);
        let medicineIndex = {{ count($prescription->items ?? []) }};
    </script>
    <script src="{{ asset('js/Prescription/edit.js') }}"></script>
@endsection
