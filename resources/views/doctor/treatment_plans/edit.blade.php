@extends('doctor.dashboard') {{-- Đảm bảo layout này có @stack('scripts') --}}
@section('title', 'Lập kế hoạch điều trị')

@section('content')
    <div class="container">
        <h1>Chỉnh sửa Kế hoạch Điều trị: {{ $treatmentPlan->plan_title }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('doctor.treatment-plans.update', $treatmentPlan) }}" method="POST">
            @csrf
            @method('PUT') {{-- Bắt buộc phải có để Laravel nhận diện là PUT/PATCH request --}}

            <!-- Patient Field with Select2 -->
            <div class="form-group mb-4">
                <label for="patient_id" class="form-label font-weight-semibold d-flex align-items-center">
                    <i class="feather icon-user mr-2 text-primary"></i>
                    Bệnh nhân <span class="text-danger ml-1">*</span>
                </label>
                <div class="input-group">
                    <select name="patient_id" id="patient_id" class="form-control select2 border-left-0" required>
                        <option value="">Tìm kiếm bệnh nhân...</option>
                        @if(old('patient_id', $treatmentPlan->patient_id) && $patients)
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ (old('patient_id', $treatmentPlan->patient_id) == $patient->id) ? 'selected' : '' }}>
                                    {{ $patient->full_name }} ({{ $patient->email }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <small class="form-text text-muted mt-1">
                    <i class="feather icon-info mr-1"></i>Gõ tên hoặc email để tìm kiếm bệnh nhân
                </small>
            </div>

            <!-- Plan Title Field -->
            <div class="form-group mb-4">
                <label for="plan_title" class="form-label font-weight-semibold d-flex align-items-center">
                    <i class="feather icon-type mr-2 text-primary"></i>
                    Tiêu đề kế hoạch <span class="text-danger ml-1">*</span>
                </label>
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg border-left-0" id="plan_title"
                           name="plan_title" value="{{ old('plan_title', $treatmentPlan->plan_title) }}"
                           placeholder="Nhập tiêu đề kế hoạch..." required>
                </div>
                <small class="form-text text-muted mt-1">
                    <i class="feather icon-info mr-1"></i>Tiêu đề ngắn gọn, mô tả rõ ràng
                </small>
            </div>

            <!-- Total Estimated Cost Field -->
            <div class="form-group mb-4">
                <label for="total_estimated_cost" class="form-label font-weight-semibold d-flex align-items-center">
                    <i class="feather icon-dollar-sign mr-2 text-primary"></i>
                    Chi phí ước tính <span class="text-danger ml-1">*</span>
                </label>
                <div class="input-group">
                    <input type="number" step="0.01" class="form-control form-control-lg border-left-0"
                           id="total_estimated_cost" name="total_estimated_cost"
                           value="{{ old('total_estimated_cost', $treatmentPlan->total_estimated_cost) }}"
                           placeholder="Nhập chi phí ước tính..." required>
                </div>
                <small class="form-text text-muted mt-1">
                    <i class="feather icon-help-circle mr-1"></i>Nhập số tiền dự kiến (đơn vị: VND)
                </small>
            </div>

            <!-- Notes Field -->
            <div class="form-group mb-4">
                <label for="notes" class="form-label font-weight-semibold d-flex align-items-center">
                    <i class="feather icon-edit mr-2 text-primary"></i>
                    Ghi chú
                </label>
                <div class="content-editor-wrapper">
                    <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Nhập ghi chú...">{{ old('notes', $treatmentPlan->notes) }}</textarea>
                </div>
                <small class="form-text text-muted mt-1">
                    <i class="feather icon-edit-2 mr-1"></i>Ghi chú bổ sung nếu cần
                </small>
            </div>

            <!-- Diagnosis Field -->
            <div class="form-group mb-4">
                <label for="diagnosis" class="form-label font-weight-semibold d-flex align-items-center">
                    <i class="feather icon-clipboard mr-2 text-primary"></i>
                    Chẩn đoán
                </label>
                <div class="content-editor-wrapper">
                    <textarea class="form-control" id="diagnosis" name="diagnosis" rows="4" placeholder="Nhập chẩn đoán...">{{ old('diagnosis', $treatmentPlan->diagnosis) }}</textarea>
                </div>
                <small class="form-text text-muted mt-1">
                    <i class="feather icon-edit-2 mr-1"></i>Chi tiết chẩn đoán nếu có
                </small>
            </div>

            <!-- Goal Field -->
            <div class="form-group mb-4">
                <label for="goal" class="form-label font-weight-semibold d-flex align-items-center">
                    <i class="feather icon-target mr-2 text-primary"></i>
                    Mục tiêu
                </label>
                <div class="content-editor-wrapper">
                    <textarea class="form-control" id="goal" name="goal" rows="4" placeholder="Nhập mục tiêu...">{{ old('goal', $treatmentPlan->goal) }}</textarea>
                </div>
                <small class="form-text text-muted mt-1">
                    <i class="feather icon-edit-2 mr-1"></i>Mục tiêu điều trị mong muốn
                </small>
            </div>

            <!-- Start Date Field with Flatpickr -->
            <div class="form-group mb-4">
                <label for="start_date" class="form-label font-weight-semibold d-flex align-items-center">
                    <i class="feather icon-calendar mr-2 text-primary"></i>
                    Ngày bắt đầu <span class="text-danger ml-1">*</span>
                </label>
                <div class="input-group">
                    <input type="text" class="form-control pickatime-format border-left-0" id="start_date"
                           name="start_date" placeholder="Chọn ngày bắt đầu..."
                           value="{{ old('start_date', isset($treatmentPlan->start_date) ? $treatmentPlan->start_date->format('Y-m-d') : '') }}" required>
                </div>
                @error('start_date')
                    <span class="text-danger small mt-1"><i class="feather icon-alert-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>

            <!-- End Date Field with Flatpickr -->
            <div class="form-group mb-4">
                <label for="end_date" class="form-label font-weight-semibold d-flex align-items-center">
                    <i class="feather icon-calendar mr-2 text-primary"></i>
                    Ngày kết thúc
                </label>
                <div class="input-group">
                    <input type="text" class="form-control pickatime-format border-left-0" id="end_date"
                           name="end_date" placeholder="Chọn ngày kết thúc..."
                           value="{{ old('end_date', isset($treatmentPlan->end_date) ? $treatmentPlan->end_date->format('Y-m-d') : '') }}">
                </div>
                @error('end_date')
                    <span class="text-danger small mt-1"><i class="feather icon-alert-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>

            <!-- Status Field (Hidden with Default Value) -->
            <input type="hidden" name="status" value="{{ old('status', $treatmentPlan->status) }}">

            <hr>

            <!-- Treatment Items Section -->
            <h2>Các Bước Điều trị</h2>
            <div id="treatment-items-container">
                @foreach ($treatmentPlan->items as $item)
                    <div class="treatment-item-card card mb-3 p-3 border">
                        <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">
                        <button type="button" class="btn btn-sm btn-danger float-end remove-item-btn">Xóa</button>
                        <h5 class="card-title">Bước Điều trị: {{ $item->title }}</h5>

                        <div class="form-group mb-2">
                            <label for="item_{{ $item->id }}_title">Tên/Mô tả bước:</label>
                            <input type="text" name="items[{{ $item->id }}][title]" id="item_{{ $item->id }}_title" class="form-control" value="{{ old("items.$item->id.title", $item->title) }}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="item_{{ $item->id }}_description">Mô tả chi tiết:</label>
                            <textarea name="items[{{ $item->id }}][description]" id="item_{{ $item->id }}_description" class="form-control" rows="2">{{ old("items.$item->id.description", $item->description) }}</textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="item_{{ $item->id }}_expected_start_date">Ngày bắt đầu dự kiến:</label>
                            <input type="text" name="items[{{ $item->id }}][expected_start_date]" id="item_{{ $item->id }}_expected_start_date" class="form-control pickatime-format" value="{{ old("items.$item->id.expected_start_date", $item->expected_start_date) }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="item_{{ $item->id }}_expected_end_date">Ngày kết thúc dự kiến:</label>
                            <input type="text" name="items[{{ $item->id }}][expected_end_date]" id="item_{{ $item->id }}_expected_end_date" class="form-control pickatime-format" value="{{ old("items.$item->id.expected_end_date", $item->expected_end_date) }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="item_{{ $item->id }}_frequency">Tần suất:</label>
                            <input type="text" name="items[{{ $item->id }}][frequency]" id="item_{{ $item->id }}_frequency" class="form-control" value="{{ old("items.$item->id.frequency", $item->frequency) }}" placeholder="Ví dụ: 2 lần/ngày, 1 lần/tuần">
                        </div>
                        <div class="form-group mb-2">
                            <label for="item_{{ $item->id }}_notes">Ghi chú cho bước:</label>
                            <textarea name="items[{{ $item->id }}][notes]" id="item_{{ $item->id }}_notes" class="form-control" rows="2">{{ old("items.$item->id.notes", $item->notes) }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-secondary mt-3" id="add-item-btn">Thêm Bước Điều trị Mới</button>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Cập nhật Kế hoạch</button>
                <a href="{{ route('doctor.treatment-plans.show', $treatmentPlan) }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <!-- Custom Styles -->
    <style>
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-circle i {
            margin: 0 auto;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #667eea;
            border-color: #667eea;
        }
        .recipient-section, .scheduling-section {
            border-left: 4px solid #667eea;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .card {
            transition: all 0.3s ease;
        }
        .input-group-text {
            border-right: none;
        }
        .form-control.border-left-0 {
            border-left: none;
        }
        .content-editor-wrapper {
            border: 2px dashed #e3ebf0;
            border-radius: 8px;
            padding: 10px;
            transition: border-color 0.3s ease;
        }
        .content-editor-wrapper:hover {
            border-color: #667eea;
        }
        .alert {
            border-radius: 10px;
        }
        .breadcrumb-item.active {
            color: #667eea !important;
        }
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
                gap: 1rem;
            }
            .btn-group {
                width: 100%;
                display: flex;
            }
            .btn-group .btn {
                flex: 1;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo Select2 cho patient_id
            $('#patient_id').select2({
                placeholder: "Tìm kiếm bệnh nhân theo tên hoặc email",
                allowClear: true,
                ajax: {
                    url: "{{ route('doctor.treatment-plans.searchPatient') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        console.log('Search term:', params.term);
                        return {
                            query: params.term
                        };
                    },
                    processResults: function(data) {
                        console.log('Ajax response:', data);
                        return {
                            results: data.map(function(patient) {
                                return {
                                    id: patient.id,
                                    text: patient.full_name + ' (' + patient.email + ')'
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            }).val({{ $treatmentPlan->patient_id }}).trigger('change'); // Đặt giá trị mặc định từ DB

            let itemIndex = {{ count($treatmentPlan->items) > 0 ? $treatmentPlan->items->max('id') + 1 : 0 }}; // Bắt đầu index từ ID lớn nhất + 1 hoặc 0

            // Hàm thêm một item mới vào form
            function addTreatmentItem(itemData = {}) {
                console.log('Adding treatment item, index:', itemIndex); // Debug
                const container = $('#treatment-items-container');
                const template = document.getElementById('treatment-item-template');
                const clone = template.content.cloneNode(true);

                const html = clone.firstElementChild.outerHTML
                    .replace(/\[index\]/g, '[' + itemIndex + ']')
                    .replace(/index_/g, 'item_' + itemIndex + '_');

                const newDiv = $(html);
                newDiv.addClass('treatment-item-card card mb-3 p-3');

                if (Object.keys(itemData).length > 0) {
                    newDiv.find(`[name="items[${itemIndex}][id]"]`).val(itemData.id || '');
                    newDiv.find(`[name="items[${itemIndex}][title]"]`).val(itemData.title || '');
                    newDiv.find(`[name="items[${itemIndex}][description]"]`).val(itemData.description || '');
                    newDiv.find(`[name="items[${itemIndex}][expected_start_date]"]`).val(itemData.expected_start_date || '');
                    newDiv.find(`[name="items[${itemIndex}][expected_end_date]"]`).val(itemData.expected_end_date || '');
                    newDiv.find(`[name="items[${itemIndex}][frequency]"]`).val(itemData.frequency || '');
                    newDiv.find(`[name="items[${itemIndex}][notes]"]`).val(itemData.notes || '');
                }

                container.append(newDiv);

                // Khởi tạo Flatpickr cho ngày trong item mới
                newDiv.find('[name^="items\\[' + itemIndex + '\\]\\[expected_start_date\\]"]').flatpickr({
                    enableTime: false,
                    dateFormat: 'Y-m-d',
                    minDate: 'today',
                    onChange: function(selectedDates, dateStr) {
                        newDiv.find('[name^="items\\[' + itemIndex + '\\]\\[expected_end_date\\]"]').flatpickr().set('minDate', dateStr);
                    }
                });
                newDiv.find('[name^="items\\[' + itemIndex + '\\]\\[expected_end_date\\]"]').flatpickr({
                    enableTime: false,
                    dateFormat: 'Y-m-d'
                });

                // Tăng index cho item tiếp theo
                itemIndex++;
            }

            // Gắn sự kiện cho nút thêm, xóa sự kiện cũ trước
            $('#add-item-btn').off('click').on('click', function() {
                addTreatmentItem();
            });

            // Phục hồi các item từ old input khi có lỗi validation
            @if(old('items'))
                // Đầu tiên xóa các item đã render từ $treatmentPlan->items để tránh trùng lặp
                $('#treatment-items-container .treatment-item-card').remove();

                // Đặt lại itemIndex về 0 và thêm lại từ old data
                itemIndex = 0;
                @foreach(old('items') as $oldItem)
                    addTreatmentItem(@json($oldItem));
                @endforeach
            @endif

            // Sử dụng delegate event cho nút xóa, áp dụng cho cả item cũ và mới
            $('#treatment-items-container').on('click', '.remove-item-btn', function() {
                $(this).closest('.treatment-item-card').remove();
            });

            // Khởi tạo Flatpickr cho start_date và end_date
            $('#start_date').flatpickr({
                enableTime: false,
                dateFormat: 'Y-m-d',
                minDate: 'today',
                onChange: function(selectedDates, dateStr) {
                    $('#end_date').flatpickr().set('minDate', dateStr);
                }
            });
            $('#end_date').flatpickr({
                enableTime: false,
                dateFormat: 'Y-m-d'
            });

            // Khởi tạo Flatpickr cho các ngày trong item đã có
            $('.treatment-item-card [name^="items\\["][name$="\\]\\[expected_start_date\\]"]').each(function() {
                const $startInput = $(this);
                const $endInput = $startInput.closest('.treatment-item-card').find('[name^="items\\["][name$="\\]\\[expected_end_date\\]"]');
                $startInput.flatpickr({
                    enableTime: false,
                    dateFormat: 'Y-m-d',
                    minDate: 'today',
                    onChange: function(selectedDates, dateStr) {
                        $endInput.flatpickr().set('minDate', dateStr);
                    }
                });
                $endInput.flatpickr({
                    enableTime: false,
                    dateFormat: 'Y-m-d'
                });
            });
        });
    </script>

    {{-- Template cho một mục điều trị mới (được thêm bằng JS). Đặt TRONG @push('scripts') --}}
    @once
        <template id="treatment-item-template">
            <div class="treatment-item-card card mb-3 p-3 border">
                <div class="card-body">
                    <input type="hidden" name="items[index][id]" value=""> <!-- ID rỗng cho item mới -->
                    <button type="button" class="btn btn-sm btn-danger float-end remove-item-btn">Xóa</button>
                    <h5 class="card-title">Bước Điều trị Mới</h5>

                    <div class="form-group mb-2">
                        <label for="item_index_title">Tên/Mô tả bước:</label>
                        <input type="text" name="items[index][title]" id="item_index_title" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="item_index_description">Mô tả chi tiết:</label>
                        <textarea name="items[index][description]" id="item_index_description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="item_index_expected_start_date">Ngày bắt đầu dự kiến:</label>
                        <input type="text" name="items[index][expected_start_date]" id="item_index_expected_start_date" class="form-control pickatime-format">
                    </div>
                    <div class="form-group mb-2">
                        <label for="item_index_expected_end_date">Ngày kết thúc dự kiến:</label>
                        <input type="text" name="items[index][expected_end_date]" id="item_index_expected_end_date" class="form-control pickatime-format">
                    </div>
                    <div class="form-group mb-2">
                        <label for="item_index_frequency">Tần suất:</label>
                        <input type="text" name="items[index][frequency]" id="item_index_frequency" class="form-control" placeholder="Ví dụ: 2 lần/ngày, 1 lần/tuần">
                    </div>
                    <div class="form-group mb-2">
                        <label for="item_index_notes">Ghi chú cho bước:</label>
                        <textarea name="items[index][notes]" id="item_index_notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </template>
    @endonce
@endpush    