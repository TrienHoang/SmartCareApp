@extends('admin.dashboard')
@section('title', 'Chỉnh sửa Đơn thuốc')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Prescriptions /</span> Chỉnh sửa Đơn thuốc
        </h4>

        <div class="card">
            <div class="card-header">
                <h5>Chỉnh sửa Đơn thuốc</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.prescriptions.update', $prescription->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="medical_record_id" class="form-label">Hồ sơ bệnh án</label>
                        <select name="medical_record_id" id="medical_record_id"
                            class="form-select @error('medical_record_id') is-invalid @enderror" disabled>
                            @foreach ($medicalRecords as $record)
                                <option value="{{ $record->id }}"
                                    {{ $prescription->medical_record_id == $record->id ? 'selected' : '' }}>
                                    {{ $record->id }} - {{ $record->appointment->patient->full_name ?? 'Bệnh nhân' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="medical_record_id" value="{{ $prescription->medical_record_id }}">
                        @error('medical_record_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="prescribed_at" class="form-label">Ngày kê toa</label>
                        <input type="datetime-local" name="prescribed_at" id="prescribed_at"
                            value="{{ old('prescribed_at', \Carbon\Carbon::parse($prescription->prescribed_at)->format('Y-m-d\TH:i')) }}"
                            class="form-control @error('prescribed_at') is-invalid @enderror">
                        @error('prescribed_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $prescription->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5>Thuốc được kê</h5>
                    <div id="medicine-list">
                        @foreach ($prescription->items as $index => $item)
                            <div class="medicine-item border p-2 mb-2">
                                <select name="medicines[{{ $index }}][medicine_id]" class="form-select mb-2"
                                    required>
                                    <option value="">-- Chọn thuốc --</option>
                                    @foreach ($medicines as $med)
                                        <option value="{{ $med->id }}"
                                            {{ $item->medicine_id == $med->id ? 'selected' : '' }}>
                                            {{ $med->name }} ({{ $med->unit }})
                                        </option>
                                    @endforeach
                                </select>

                                <input type="number" name="medicines[{{ $index }}][quantity]"
                                    class="form-control mb-2" placeholder="Số lượng" min="1"
                                    value="{{ $item->quantity }}" required>

                                <textarea name="medicines[{{ $index }}][usage_instructions]" class="form-control"
                                    placeholder="Hướng dẫn sử dụng">{{ $item->usage_instructions }}</textarea>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" onclick="addMedicine()" class="btn btn-sm btn-secondary mb-3">+ Thêm
                        thuốc</button>

                    <br>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-danger">Hủy</a>
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
