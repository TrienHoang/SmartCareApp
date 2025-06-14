@extends('admin.dashboard')
@section('title', 'Tạo đơn thuốc mới')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tạo đơn thuốc mới</h3>
                        <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary float-right">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                    <form action="{{ route('admin.prescriptions.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="medical_record_id">Hồ sơ bệnh án <span
                                                class="text-danger">*</span></label>
                                        <select name="medical_record_id" id="medical_record_id"
                                            class="form-control select2 @error('medical_record_id') is-invalid @enderror">
                                            <option value="">-- Chọn hồ sơ bệnh án --</option>
                                            @foreach ($medicalRecords as $record)
                                                <option value="{{ $record->id }}">
                                                    Hồ sơ: {{ $record->code }} <br>
                                                    Bệnh nhân: {{ $record->appointment->patient->full_name ?? 'Không có' }}
                                                    (ID: {{ $record->appointment->patient->id ?? 'N/A' }}) <br>
                                                    Bác sĩ:
                                                    {{ $record->appointment->doctor->user->full_name ?? 'Không có' }} (ID:
                                                    {{ $record->appointment->doctor->user->id ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('medical_record_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prescribed_at">Ngày kê đơn <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="prescribed_at" id="prescribed_at"
                                            class="form-control @error('prescribed_at') is-invalid @enderror"
                                            value="{{ old('prescribed_at', now()->format('Y-m-d\TH:i')) }}">
                                        @error('prescribed_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Ghi chú</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Ghi chú về đơn thuốc...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>
                            <h5>Danh sách thuốc</h5>
                            <div id="medicines-container">
                                @php
                                    $oldMedicines = old('medicines', [0 => []]);
                                @endphp

                                @foreach ($oldMedicines as $index => $item)
                                    <div class="medicine-item border p-3 mb-3" data-index="{{ $index }}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Thuốc <span class="text-danger">*</span></label>
                                                    <select name="medicines[{{ $index }}][medicine_id]"
                                                        class="form-control medicine-select @error("medicines.$index.medicine_id") is-invalid @enderror">
                                                        <option value="">-- Chọn thuốc --</option>
                                                        @foreach ($medicines as $medicine)
                                                            <option value="{{ $medicine->id }}"
                                                                {{ old("medicines.$index.medicine_id", $item['medicine_id'] ?? '') == $medicine->id ? 'selected' : '' }}>
                                                                {{ $medicine->name }} ({{ $medicine->unit }}) -
                                                                {{ $medicine->formatted_price }} – Còn:
                                                                {{ $medicine->stock }}
                                                                {{ $medicine->stock < 10 ? '⚠️ Cảnh báo: gần hết' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error("medicines.$index.medicine_id")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Số lượng <span class="text-danger">*</span></label>
                                                    <input type="number" name="medicines[{{ $index }}][quantity]"
                                                        class="form-control @error("medicines.$index.quantity") is-invalid @enderror"
                                                        min="1"
                                                        value="{{ old("medicines.$index.quantity", $item['quantity'] ?? '') }}">
                                                    @error("medicines.$index.quantity")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-5 d-flex align-items-end">
                                                <div class="form-group flex-grow-1 mb-0">
                                                    <label>Hướng dẫn sử dụng</label>
                                                    <div class="input-group">
                                                        <input type="text"
                                                            name="medicines[{{ $index }}][usage_instructions]"
                                                            class="form-control"
                                                            value="{{ old("medicines.$index.usage_instructions", $item['usage_instructions'] ?? '') }}"
                                                            placeholder="Ví dụ: Uống 2 viên sau ăn, ngày 3 lần">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-danger remove-medicine"
                                                                {{ $loop->first ? 'disabled' : '' }}>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="add-medicine" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm thuốc
                            </button>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo đơn thuốc
                            </button>
                            <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let medicineIndex = {{ count($oldMedicines) }};

            document.getElementById('add-medicine').addEventListener('click', function() {
                const container = document.getElementById('medicines-container');
                const newItem = document.querySelector('.medicine-item').cloneNode(true);

                newItem.setAttribute('data-index', medicineIndex);

                newItem.querySelectorAll('select, input').forEach(function(element) {
                    const name = element.getAttribute('name');
                    if (name) {
                        element.setAttribute('name', name.replace(/\[\d+\]/, '[' + medicineIndex +
                            ']'));
                        element.value = '';
                    }
                });

                newItem.querySelector('.remove-medicine').disabled = false;
                container.appendChild(newItem);
                medicineIndex++;
                updateRemoveButtons();
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-medicine')) {
                    e.target.closest('.medicine-item').remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const items = document.querySelectorAll('.medicine-item');
                items.forEach(function(item, index) {
                    const removeBtn = item.querySelector('.remove-medicine');
                    removeBtn.disabled = items.length === 1;
                });
            }
        });
    </script>
@endsection
