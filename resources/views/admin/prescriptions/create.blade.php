@extends('admin.dashboard')
@section('title', 'Tạo đơn thuốc mới')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow rounded-4">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                        <h4 class="mb-0 fw-bold"><i class="fas fa-file-medical me-2"></i> Tạo đơn thuốc mới</h4>
                        <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>

                    <form action="{{ route('admin.prescriptions.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger rounded-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="medical_record_display" class="form-label fw-semibold">Hồ sơ bệnh án <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="medical_record_display"
                                        class="form-control @error('medical_record_id') is-invalid @enderror"
                                        placeholder="Tìm bệnh nhân theo tên hoặc SĐT..."
                                        value="{{ old('medical_record_display') }}">
                                    <input type="hidden" name="medical_record_id" id="medical_record_id"
                                        value="{{ old('medical_record_id') }}">
                                    @error('medical_record_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    <label for="prescribed_at" class="form-label">Ngày kê toa</label>
                                    <input type="text" id="prescribed_at" name="prescribed_at"
                                        class="form-control flatpickr @error('prescribed_at') is-invalid @enderror"
                                        value="{{ old('prescribed_at') }}" placeholder="Chọn ngày và giờ">
                                    @error('prescribed_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="notes" class="form-label fw-semibold">Ghi chú</label>
                                    <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                        placeholder="Ghi chú về đơn thuốc...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="fw-bold"><i class="fas fa-capsules text-danger me-1"></i>Danh sách thuốc</h5>
                            <p class="text-muted small" id="no-medicine-msg">Chưa có thuốc nào được thêm.</p>

                            <div id="medicines-container" class="mt-2"></div>

                            <button type="button" id="add-medicine" class="btn btn-outline-success mt-2">
                                <i class="fas fa-plus me-1"></i> Thêm thuốc
                            </button>
                        </div>

                        <div class="card-footer bg-light d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Tạo đơn thuốc
                            </button>
                            <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Template thuốc --}}
    <div id="medicine-template" class="d-none">
        <div class="medicine-item border rounded p-3 mb-3 bg-light" data-index="__INDEX__">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Thuốc <span class="text-danger">*</span></label>
                    <input type="text" class="form-control medicine-autocomplete" placeholder="Tìm thuốc...">
                    <input type="hidden" name="medicines[__INDEX__][medicine_id]" class="medicine-hidden-id">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Số lượng</label>
                    <input type="number" name="medicines[__INDEX__][quantity]" class="form-control">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Hướng dẫn sử dụng</label>
                    <input type="text" name="medicines[__INDEX__][usage_instructions]" class="form-control"
                        placeholder="VD: Uống 2 viên sau ăn, ngày 3 lần">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger w-100 remove-medicine">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let medicineIndex = 0;

            // --- Autocomplete hồ sơ bệnh án ---
            const $recordInput = $('#medical_record_display');
            const $recordId = $('#medical_record_id');

            $recordInput.autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('admin.prescriptions.medical-records.search') }}",
                        dataType: 'json',
                        data: {
                            q: request.term
                        },
                        success: function(data) {
                            response(data.map(item => ({
                                label: item.text,
                                value: item.id
                            })));
                        }
                    });
                },
                minLength: 2,
                delay: 300,
                select: function(event, ui) {
                    $recordInput.val(ui.item.label);
                    $recordId.val(ui.item.value);
                    return false;
                }
            });

            if ($recordId.val()) {
                $.ajax({
                    url: "{{ route('admin.prescriptions.medical-records.search') }}",
                    data: {
                        q: ''
                    },
                    success: function(data) {
                        const match = data.find(item => item.id == $recordId.val());
                        if (match) {
                            $recordInput.val(match.text);
                        }
                    }
                });
            }

            // --- Autocomplete thuốc ---
            function initMedicineAutocomplete(container) {
                const $input = $(container).find('.medicine-autocomplete');
                const $hidden = $(container).find('.medicine-hidden-id');

                $input.autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "{{ route('admin.prescriptions.medicines.search') }}",
                            dataType: 'json',
                            data: {
                                q: request.term
                            },
                            success: function(data) {
                                response(data.map(item => ({
                                    label: item.text,
                                    value: item.id
                                })));
                            }
                        });
                    },
                    minLength: 1,
                    delay: 250,
                    select: function(event, ui) {
                        $input.val(ui.item.label);
                        $hidden.val(ui.item.value);
                        return false;
                    }
                });
            }

            function updateRemoveButtons() {
                const items = document.querySelectorAll('.medicine-item');
                document.getElementById('no-medicine-msg').style.display = items.length === 0 ? 'block' : 'none';
                items.forEach(item => {
                    item.querySelector('.remove-medicine').disabled = items.length === 1;
                });
            }

            function addMedicineItem() {
                const html = document.getElementById('medicine-template').innerHTML.replace(/__INDEX__/g,
                    medicineIndex);
                const div = document.createElement('div');
                div.innerHTML = html;
                const newItem = div.firstElementChild;
                document.getElementById('medicines-container').appendChild(newItem);
                initMedicineAutocomplete(newItem);
                updateRemoveButtons();
                medicineIndex++;
            }

            document.getElementById('add-medicine').addEventListener('click', addMedicineItem);

            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-medicine')) {
                    e.target.closest('.medicine-item').remove();
                    updateRemoveButtons();
                }
            });

            flatpickr("#prescribed_at", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today",
                locale: "vn",
                disableMobile: true
            });

            updateRemoveButtons();
        });
    </script>

@endsection