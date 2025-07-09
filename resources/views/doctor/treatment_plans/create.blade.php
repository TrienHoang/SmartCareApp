@extends('doctor.dashboard')
@section('title', 'Lập kế hoạch điều trị')

@section('content')
    <div class="content-wrapper">
        <!-- Enhanced Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary mr-3">
                                <i class="menu-icon tf-icons bx bx-clipboard text-white"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 text-primary font-weight-bold">Lập kế hoạch điều trị</h2>
                                <p class="text-muted mb-0">Tạo kế hoạch điều trị cho bệnh nhân</p>
                            </div>
                        </div>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0">
                                    <li class="">
                                        <a href="{{ route('doctor.treatment-plans.index') }}" class="text-decoration-none">
                                            <i class="feather icon-home mr-1"></i>Trang chủ >
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-primary font-weight-semibold">Lập kế hoạch</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Enhanced Alert Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-alert-circle mr-2"></i>
                        <strong>Có lỗi xảy ra!</strong>
                    </div>
                    <ul class="mb-0 mt-2 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-x-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Enhanced Main Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-edit-3 mr-2"></i>
                        <h4 class="card-title mb-0 text-white font-weight-bold">Thông tin Kế hoạch Điều trị</h4>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('doctor.treatment-plans.store') }}" method="POST" class="needs-validation"
                        novalidate>
                        @csrf

                        <!-- Patient Field -->
                        <div class="form-group mb-4">
                            <label for="patient_id" class="form-label font-weight-semibold d-flex align-items-center">
                                <i class="feather icon-user mr-2 text-primary"></i>
                                Bệnh nhân <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="input-group">
                                <select name="patient_id" id="patient_id" class="form-control select2 border-left-0"
                                    required>
                                    <option value="">Tìm kiếm bệnh nhân...</option>
                                    @php
                                    $selectedPatientId = old('patient_id', $treatmentPlan->patient_id ?? null);
                                @endphp
                                
                                @if($selectedPatientId)
                                    @php
                                        $selectedPatient = \App\Models\User::select('id', 'full_name', 'email')->find($selectedPatientId);
                                    @endphp
                                
                                    @if($selectedPatient)
                                        <option value="{{ $selectedPatient->id }}" selected>
                                            {{ $selectedPatient->full_name }} ({{ $selectedPatient->email }})
                                        </option>
                                    @endif
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
                                    name="plan_title" value="{{ old('plan_title', $treatmentPlan->plan_title ?? '') }}"
                                    placeholder="Nhập tiêu đề kế hoạch..." required>
                            </div>
                            <small class="form-text text-muted mt-1">
                                <i class="feather icon-info mr-1"></i>Tiêu đề ngắn gọn, mô tả rõ ràng
                            </small>
                        </div>

                        <!-- Total Estimated Cost Field -->
                        <div class="form-group mb-4">
                            <label for="total_estimated_cost"
                                class="form-label font-weight-semibold d-flex align-items-center">
                                <i class="feather icon-dollar-sign mr-2 text-primary"></i>
                                Chi phí ước tính <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control form-control-lg border-left-0"
                                    id="total_estimated_cost" name="total_estimated_cost"
                                    value="{{ old('total_estimated_cost', $treatmentPlan->total_estimated_cost ?? '') }}"
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
                                <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Nhập ghi chú...">{{ old('notes', $treatmentPlan->notes ?? '') }}</textarea>
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
                                <textarea class="form-control" id="diagnosis" name="diagnosis" rows="4" placeholder="Nhập chẩn đoán...">{{ old('diagnosis', $treatmentPlan->diagnosis ?? '') }}</textarea>
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
                                <textarea class="form-control" id="goal" name="goal" rows="4" placeholder="Nhập mục tiêu...">{{ old('goal', $treatmentPlan->goal ?? '') }}</textarea>
                            </div>
                            <small class="form-text text-muted mt-1">
                                <i class="feather icon-edit-2 mr-1"></i>Mục tiêu điều trị mong muốn
                            </small>
                        </div>

                        <!-- Start Date Field -->
                        <div class="form-group mb-4">
                            <label for="start_date" class="form-label font-weight-semibold d-flex align-items-center">
                                <i class="feather icon-calendar mr-2 text-primary"></i>
                                Ngày bắt đầu <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control pickatime-format border-left-0" id="start_date"
                                    name="start_date" placeholder="Chọn ngày bắt đầu..."
                                    value="{{ old('start_date', isset($treatmentPlan->start_date) ? $treatmentPlan->start_date->format('Y-m-d') : '') }}"
                                    required>
                            </div>
                            @error('start_date')
                                <span class="text-danger small mt-1"><i
                                        class="feather icon-alert-circle mr-1"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- End Date Field -->
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
                                <span class="text-danger small mt-1"><i
                                        class="feather icon-alert-circle mr-1"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Status Field (Hidden with Default Value) -->
                        <input type="hidden" name="status" value="draft">

                        <!-- Treatment Items Section -->
                        <div class="recipient-section bg-light p-4 rounded mb-4">
                            <h5 class="mb-3 text-primary font-weight-bold">
                                <i class="feather icon-list mr-2"></i>Các Bước Điều trị
                            </h5>
                            <div id="treatment-items-container"></div>
                            <button type="button" class="btn btn-primary waves-effect waves-light mt-3"
                                id="add-item-btn">
                                <i class="feather icon-plus mr-1"></i>Thêm Bước Điều trị
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-actions d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="text-muted">
                                <i class="feather icon-info mr-1"></i>
                                <small>Các trường có dấu <span class="text-danger">*</span> là bắt buộc</small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('doctor.treatment-plans.index') }}"
                                    class="btn btn-outline-secondary waves-effect mr-2">
                                    <i class="feather icon-x mr-1"></i>Hủy bỏ
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light px-4">
                                    <i class="feather icon-save mr-2"></i>Lưu kế hoạch
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <template id="treatment-item-template">
        <div class="treatment-item-card card mb-3 p-3 border">
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
                <input type="text" name="items[index][expected_start_date]" id="item_index_expected_start_date"
                    class="form-control pickatime-format">
            </div>
            <div class="form-group mb-2">
                <label for="item_index_expected_end_date">Ngày kết thúc dự kiến:</label>
                <input type="text" name="items[index][expected_end_date]" id="item_index_expected_end_date"
                    class="form-control pickatime-format">
            </div>
            <div class="form-group mb-2">
                <label for="item_index_frequency">Tần suất:</label>
                <input type="text" name="items[index][frequency]" id="item_index_frequency" class="form-control"
                    placeholder="Ví dụ: 2 lần/ngày, 1 lần/tuần">
            </div>
            <div class="form-group mb-2">
                <label for="item_index_notes">Ghi chú cho bước:</label>
                <textarea name="items[index][notes]" id="item_index_notes" class="form-control" rows="2"></textarea>
            </div>
        </div>
    </template>
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

        .custom-control-input:checked~.custom-control-label::before {
            background-color: #667eea;
            border-color: #667eea;
        }

        .recipient-section,
        .scheduling-section {
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
            });

            let itemIndex = 0;

            // Hàm thêm bước điều trị
            function addTreatmentItem(itemData = {}) {
                console.log('Adding treatment item, index:', itemIndex); // Debug
                const container = document.getElementById('treatment-items-container');
                const template = document.getElementById('treatment-item-template');
                const clone = template.content.cloneNode(true);

                const html = clone.firstElementChild.outerHTML
                    .replace(/\[index\]/g, '[' + itemIndex + ']')
                    .replace(/index_/g, 'item_' + itemIndex + '_');

                const newDiv = document.createElement('div');
                newDiv.innerHTML = html;
                newDiv.classList.add('treatment-item-card', 'card', 'mb-3', 'p-3');

                if (Object.keys(itemData).length > 0) {
                    newDiv.querySelector(`[name="items[${itemIndex}][title]"]`).value = itemData.title || '';
                    newDiv.querySelector(`[name="items[${itemIndex}][description]"]`).value = itemData
                        .description || '';
                    newDiv.querySelector(`[name="items[${itemIndex}][expected_start_date]"]`).value = itemData
                        .expected_start_date ? new Date(itemData.expected_start_date).toISOString().split('T')[0] :
                        '';
                    newDiv.querySelector(`[name="items[${itemIndex}][expected_end_date]"]`).value = itemData
                        .expected_end_date ? new Date(itemData.expected_end_date).toISOString().split('T')[0] : '';
                    newDiv.querySelector(`[name="items[${itemIndex}][frequency]"]`).value = itemData.frequency ||
                    '';
                    newDiv.querySelector(`[name="items[${itemIndex}][notes]"]`).value = itemData.notes || '';
                }

                container.appendChild(newDiv);

                newDiv.querySelector('.remove-item-btn').addEventListener('click', function() {
                    newDiv.remove();
                });

                // Khởi tạo Flatpickr cho ngày trong item
                const startDateInput = newDiv.querySelector(`[name="items[${itemIndex}][expected_start_date]"]`);
                const endDateInput = newDiv.querySelector(`[name="items[${itemIndex}][expected_end_date]"]`);
                $(startDateInput).flatpickr({
                    enableTime: false,
                    dateFormat: 'Y-m-d',
                    minDate: 'today',
                    onChange: function(selectedDates, dateStr) {
                        $(endDateInput).flatpickr().set('minDate', dateStr);
                    }
                });
                $(endDateInput).flatpickr({
                    enableTime: false,
                    dateFormat: 'Y-m-d'
                });

                itemIndex++;
            }

            // Gắn sự kiện cho nút thêm, xóa sự kiện cũ trước
            $('#add-item-btn').off('click').on('click', function() {
                addTreatmentItem();
            });

            // Thêm các bước điều trị cũ nếu có
            @if (old('items'))
                @foreach (old('items') as $oldItem)
                    addTreatmentItem(@json($oldItem));
                @endforeach
            @endif

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

            // Validation phía client trước khi submit
            $('form').on('submit', function(e) {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi thời gian!',
                        text: 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
                        confirmButtonColor: '#667eea'
                    });
                    $('#end_date').focus();
                }
            });

            // Smooth scrolling cho form validation
            $('.form-control').on('invalid', function() {
                this.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            });
        });
    </script>
@endpush
