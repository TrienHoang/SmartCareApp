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
                                        <select name="medical_record_id" id="medical_record_id" class="form-control select2"
                                            >
                                            <option value="">-- Chọn hồ sơ bệnh án --</option>
                                            @foreach ($medicalRecords as $record)
                                                <option value="{{ $record->id }}"
                                                    {{ old('medical_record_id') == $record->id ? 'selected' : '' }}>
                                                    {{ $record->code }} - {{ $record->appointment->patient->full_name }}
                                                    ({{ $record->appointment->patient->phone }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prescribed_at">Ngày kê đơn <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="prescribed_at" id="prescribed_at"
                                            class="form-control"
                                            value="{{ old('prescribed_at', now()->format('Y-m-d\TH:i')) }}" >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Ghi chú</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Ghi chú về đơn thuốc...">{{ old('notes') }}</textarea>
                            </div>

                            <hr>
                            <h5>Danh sách thuốc</h5>
                            <div id="medicines-container">
                                <div class="medicine-item border p-3 mb-3" data-index="0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Thuốc <span class="text-danger">*</span></label>
                                                <select name="medicines[0][medicine_id]"
                                                    class="form-control medicine-select">
                                                    <option value="">-- Chọn thuốc --</option>
                                                    @foreach ($medicines as $medicine)
                                                        <option value="{{ $medicine->id }}"
                                                            data-unit="{{ $medicine->unit }}"
                                                            data-dosage="{{ $medicine->dosage }}">
                                                            {{ $medicine->name }} ({{ $medicine->unit }}) -
                                                            {{ $medicine->formatted_price }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Số lượng <span class="text-danger">*</span></label>
                                                <input type="number" name="medicines[0][quantity]" class="form-control"
                                                    min="1" >
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Hướng dẫn sử dụng</label>
                                                <input type="text" name="medicines[0][usage_instructions]"
                                                    class="form-control"
                                                    placeholder="Ví dụ: Uống 2 viên sau ăn, ngày 3 lần">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block remove-medicine"
                                                    disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            let medicineIndex = 1;

            // Add medicine
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

            // Remove medicine
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
