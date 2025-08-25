@extends('doctor.dashboard')
@section('title', 'Chỉnh sửa Kế hoạch Điều trị')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header với breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('doctor.treatment-plans.index') }}">Kế hoạch điều
                                trị</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa: {{ $treatmentPlan->plan_title }}</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Chỉnh sửa Kế hoạch Điều trị: {{ $treatmentPlan->plan_title }}
                    </h1>
                    <div class="text-muted">
                        <small><i class="fas fa-info-circle me-1"></i>Các trường có dấu * là bắt buộc</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <strong>Vui lòng kiểm tra lại:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Form -->
        <form action="{{ route('doctor.treatment-plans.update', $treatmentPlan->id) }}" method="POST"
            id="treatment-plan-form">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column - Main Form -->
                <div class="col-lg-8">
                    <!-- Patient & Basic Info Section -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white mb-3">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-user-injured me-2"></i>
                                Thông tin cơ bản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="patient_id" class="form-label fw-bold">
                                        <i class="fas fa-user me-1"></i>
                                        Bệnh nhân <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="patient_id" name="patient_id" disabled>
                                        <option value="{{ $treatmentPlan->patient->id }}" selected>
                                            {{ $treatmentPlan->patient->full_name }} ({{ $treatmentPlan->patient->email }})
                                        </option>
                                    </select>
                                    <input type="hidden" name="patient_id" value="{{ $treatmentPlan->patient->id }}">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Không thể thay đổi bệnh nhân của kế hoạch này
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="plan_title" class="form-label fw-bold">
                                        <i class="fas fa-file-medical me-1"></i>
                                        Tiêu đề Kế hoạch <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="plan_title" name="plan_title"
                                        value="{{ old('plan_title', $treatmentPlan->plan_title) }}" required
                                        placeholder="Ví dụ: Điều trị nha chu giai đoạn 1">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label fw-bold">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Ngày bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ old('start_date', $treatmentPlan->start_date ? $treatmentPlan->start_date->format('Y-m-d') : '') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label fw-bold">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        Ngày kết thúc (ước tính)
                                    </label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ old('end_date', $treatmentPlan->end_date ? $treatmentPlan->end_date->format('Y-m-d') : '') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="diagnosis" class="form-label fw-bold">
                                    <i class="fas fa-stethoscope me-1"></i>
                                    Chẩn đoán
                                </label>
                                <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" placeholder="Mô tả chẩn đoán chi tiết...">{{ old('diagnosis', $treatmentPlan->diagnosis) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="goal" class="form-label fw-bold">
                                    <i class="fas fa-bullseye me-1"></i>
                                    Mục tiêu Điều trị
                                </label>
                                <textarea class="form-control" id="goal" name="goal" rows="3" placeholder="Mô tả mục tiêu điều trị...">{{ old('goal', $treatmentPlan->goal) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label fw-bold">
                                    <i class="fas fa-sticky-note me-1"></i>
                                    Ghi chú
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Ghi chú thêm...">{{ old('notes', $treatmentPlan->notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Steps Section -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">
                                    <i class="fas fa-list-ol me-2"></i>
                                    Các Bước Điều Trị
                                </h5>
                                <button type="button" class="btn btn-light btn-sm" id="add-item">
                                    <i class="fas fa-plus me-1"></i>
                                    Thêm Bước
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="treatment-items-container">
                                @foreach (old('items', $treatmentPlan->items) as $index => $item)
                                    @php
                                        $item = is_array($item) ? (object) $item : $item;
                                        $existingAppointment = null;
                                        $itemNeedsAppointment = old("items.$index.needs_appointment", 0);

                                        // Xử lý khi $item là mảng hoặc stdClass từ old('items')
                                        if (is_array($item) || $item instanceof \stdClass) {
                                            if (isset($item->appointment_id) || old("items.$index.appointment_id")) {
                                                $existingAppointment = (object) [
                                                    'id' => old(
                                                        "items.$index.appointment_id",
                                                        $item->appointment_id ?? null,
                                                    ),
                                                    'appointment_time' => old(
                                                        "items.$index.appointment_time",
                                                        $item->appointment_time ?? null,
                                                    ),
                                                    'reason' => old("items.$index.reason", $item->reason ?? null),
                                                ];
                                                $itemNeedsAppointment = old("items.$index.needs_appointment", 1);
                                            }
                                        } elseif (
                                            $item instanceof \App\Models\TreatmentPlanItem &&
                                            $item->relationLoaded('appointments') &&
                                            $item->appointments->isNotEmpty()
                                        ) {
                                            // Xử lý khi $item là model Eloquent từ cơ sở dữ liệu
                                            $existingAppointment = $item->appointments->first();
                                            $itemNeedsAppointment = 1;
                                        }

                                        $itemType = old(
                                            "items.$index.item_type",
                                            $item->service_id ? 'service' : 'custom',
                                        );
                                    @endphp
                                    @include('doctor.treatment_plans._item_template', [
                                        'index' => $index,
                                        'item' => $item,
                                        'services' => $services,
                                        'isOld' => old('items') ? true : false,
                                        'itemNeedsAppointment' => $itemNeedsAppointment,
                                        'itemType' => $itemType,
                                        'existingAppointment' => $existingAppointment,
                                    ])
                                @endforeach
                            </div>

                            <div id="empty-state"
                                class="text-center py-5 {{ $treatmentPlan->items->count() > 0 ? 'd-none' : '' }}">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có bước điều trị nào</h5>
                                <p class="text-muted">Nhấn "Thêm Bước" để bắt đầu chỉnh sửa kế hoạch điều trị</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Summary & Actions -->
                <div class="col-lg-4">
                    <!-- Cost Summary -->
                    <div class="card shadow-sm mb-4 sticky-top" style="top: 20px;">
                        <div class="card-header bg-warning text-dark mb-3">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-calculator me-2"></i>
                                Tổng Quan Chi Phí
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="total_estimated_cost" class="form-label fw-bold">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    Tổng chi phí ước tính
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" id="total_estimated_cost"
                                        name="total_estimated_cost"
                                        value="{{ old('total_estimated_cost', $treatmentPlan->total_estimated_cost) }}"
                                        placeholder="0.00">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Sẽ tự động tính từ các dịch vụ đã chọn
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status"
                                        value="active"
                                        {{ old('status', $treatmentPlan->status) == 'active' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="status">
                                        <i class="fas fa-power-off me-1"></i>
                                        Kích hoạt kế hoạch
                                    </label>
                                </div>
                                <div class="form-text">
                                    Kế hoạch sẽ có hiệu lực ngay sau khi lưu
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white mb-3">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-bolt me-2"></i>
                                Thao Tác Nhanh
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="save-draft">
                                    <i class="fas fa-save me-1"></i>
                                    Lưu Nháp
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" id="preview-plan">
                                    <i class="fas fa-eye me-1"></i>
                                    Xem Trước
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" id="reset-form">
                                    <i class="fas fa-undo me-1"></i>
                                    Đặt Lại
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('doctor.treatment-plans.index') }}"
                                        class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Quay Lại
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>
                                        Cập Nhật Kế Hoạch Điều Trị
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Template for treatment items -->
    <template id="treatment-item-template">
        @include('doctor.treatment_plans._item_template', [
            'index' => '__INDEX__',
            'item' => (object) [],
            'services' => $services,
            'itemType' => 'service',
            'itemNeedsAppointment' => 0,
            'existingAppointment' => null,
        ])
    </template>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Đang tải...</span>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Modern Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            border-bottom: none;
            padding: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form Improvements */
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e3e6f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .form-label {
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }

        /* Treatment Item Cards */
        .item-card {
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #fff 0%, #f8f9fc 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            padding: 0px 15px
        }

        .item-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(45deg, #4e73df, #1cc88a);
        }

        .item-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .item-card .card-header {
            background: linear-gradient(135deg, #f8f9fc 0%, #eaecf4 100%);
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-number {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        /* Button Styles */
        .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }

        .btn-outline-danger {
            border-color: #e74a3b;
            color: #e74a3b;
        }

        .btn-outline-danger:hover {
            background: #e74a3b;
            border-color: #e74a3b;
        }

        /* Loading Overlay */
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e3e6f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            height: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .btn-lg {
                padding: 0.75rem 1.5rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .item-card {
            animation: fadeInUp 0.3s ease;
        }

        /* Switch Styles */
        .form-check-input:checked {
            background-color: #1cc88a;
            border-color: #1cc88a;
        }

        .form-switch {
            padding-left: 2.5em !important ;
        }

        /* Empty State */
        #empty-state {
            border: 2px dashed #e3e6f0;
            border-radius: 12px;
            margin: 2rem 0;
        }

        /* Sticky sidebar */
        .sticky-top {
            position: sticky;
            top: 20px;
            z-index: 1020;
        }

        /* Service selection and custom step fields */
        .service-selection,
        .custom-step-fields {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed #e3e6f0;
        }

        /* Radio button improvements */
        .form-check-input[type="radio"] {
            border-radius: 50%;
        }

        .form-check-input[type="radio"]:checked {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1rem;
        }

        .breadcrumb-item a {
            color: #5a5c69;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: #4e73df;
        }

        /* Alert improvements */
        .alert {
            border-radius: 12px;
            border: none;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fff5f5 0%, #ffeaea 100%);
            color: #e74a3b;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let itemIndex = {{ old('items') ? count(old('items')) : $treatmentPlan->items->count() }};

            // Show loading overlay
            function showLoading() {
                $('#loading-overlay').removeClass('d-none');
            }

            // Hide loading overlay
            function hideLoading() {
                $('#loading-overlay').addClass('d-none');
            }

            // Initialize Select2 for patient (disabled)
            $('#patient_id').select2({
                placeholder: "Tìm kiếm bệnh nhân theo tên hoặc email...",
                minimumInputLength: 2,
                allowClear: false,
                disabled: true
            });

            // Update empty state
            function updateEmptyState() {
                const container = $('#treatment-items-container');
                const emptyState = $('#empty-state');

                if (container.children('.item-card').length === 0) {
                    emptyState.removeClass('d-none');
                } else {
                    emptyState.addClass('d-none');
                }
            }

            // Update item numbers and re-index inputs
            function updateItemNumbers() {
                $('#treatment-items-container .item-card').each(function(idx) {
                    const oldIndex = $(this).attr('data-index');
                    $(this).attr('data-index', idx);
                    $(this).find('.item-number').text(idx + 1);

                    // Update names and IDs of inputs
                    $(this).find('[name^="items[' + oldIndex + ']"]').each(function() {
                        const oldName = $(this).attr('name');
                        const newName = oldName.replace('items[' + oldIndex + ']', 'items[' + idx +
                            ']');
                        $(this).attr('name', newName);

                        const oldId = $(this).attr('id');
                        if (oldId) {
                            const newId = oldId.replace(oldIndex, idx);
                            $(this).attr('id', newId);
                        }
                    });

                    // Update 'for' attributes of labels
                    $(this).find('label[for^="items_' + oldIndex + '_"]').each(function() {
                        const oldFor = $(this).attr('for');
                        const newFor = oldFor.replace(oldIndex, idx);
                        $(this).attr('for', newFor);
                    });

                    // Update IDs and names for radio buttons
                    $(this).find('.item-type-radio').each(function() {
                        const oldId = $(this).attr('id');
                        const newId = oldId.replace(oldIndex, idx);
                        $(this).attr('id', newId);
                        $(this).attr('name', `items[${idx}][item_type]`);
                    });
                });

                updateEmptyState();
                updateTotalCost();
            }

            // Add new treatment item
            $('#add-item').on('click', function() {
                const template = $('#treatment-item-template').html();
                const newItemHtml = template.replace(/__INDEX__/g, itemIndex).replace(/__ITEM_NUMBER__/g,
                    itemIndex + 1);
                $('#treatment-items-container').append(newItemHtml);
                itemIndex++;
                updateItemNumbers();

                // Animate the new item
                $('#treatment-items-container .item-card').last().hide().fadeIn(300);

                // Initialize radio and checkbox for new item
                $('#treatment-items-container .item-card').last().find('.item-type-radio:checked').trigger(
                    'change');
                $('#treatment-items-container .item-card').last().find('.needs-appointment-checkbox')
                    .trigger('change');
            });

            // Handle remove item button
            $(document).on('click', '.remove-item', function() {
                const itemCard = $(this).closest('.item-card');
                itemCard.fadeOut(300, function() {
                    $(this).remove();
                    updateItemNumbers();
                });
            });

            // Handle "Needs Appointment" checkbox
            $(document).on('change', '.needs-appointment-checkbox', function() {
                const appointmentFields = $(this).closest('.item-card').find('.appointment-fields');
                if ($(this).is(':checked')) {
                    appointmentFields.slideDown();
                    appointmentFields.find('input[type="datetime-local"]').prop('required', true);
                } else {
                    appointmentFields.slideUp();
                    appointmentFields.find('input, textarea').prop('required', false).val('');
                }
            });

            $(document).on('change', '[name$="[appointment_time]"]', function() {
                const time = $(this).val();
                const itemCard = $(this).closest('.item-card');
                const appointmentInput = $(this);
                const feedback = itemCard.find('.appointment-time-feedback');

                // Remove previous feedback
                feedback.remove();
                appointmentInput.removeClass('is-invalid is-valid');

                if (time) {
                    $.ajax({
                        url: "{{ route('doctor.check-appointment') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            appointment_time: time,
                            doctor_id: "{{ Auth::user()->doctor->id }}",
                            appointment_id: itemCard.find('[name$="[appointment_id]"]').val()
                        },
                        success: function(response) {
                            if (response.booked) {
                                appointmentInput.addClass('is-invalid');
                                appointmentInput.after(
                                    `<div class="invalid-feedback appointment-time-feedback">${response.message}</div>`
                                );
                            } else {
                                appointmentInput.addClass('is-valid');
                                appointmentInput.after(
                                    `<div class="valid-feedback appointment-time-feedback">${response.message}</div>`
                                );
                                setTimeout(() => {
                                    appointmentInput.removeClass('is-valid');
                                    itemCard.find('.valid-feedback').remove();
                                }, 3000);
                            }
                        },
                        error: function(xhr) {
                            appointmentInput.addClass('is-invalid');
                            appointmentInput.after(
                                `<div class="invalid-feedback appointment-time-feedback">Lỗi khi kiểm tra lịch hẹn: ${xhr.responseJSON?.message || 'Không xác định'}</div>`
                            );
                        }
                    });
                }
            });

            $('#treatment-plan-form').on('submit', function(e) {
                e.preventDefault();
                showLoading();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function() {
                        window.location = "{{ route('doctor.treatment-plans.index') }}";
                    },
                    error: function(xhr) {
                        hideLoading();
                        let errors = xhr.responseJSON.errors;
                        let errorHtml =
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                        errorHtml +=
                            '<i class="fas fa-exclamation-triangle me-2"></i><strong>Lỗi:</strong><ul>';
                        $.each(errors, function(key, value) {
                            errorHtml += `<li>${value[0]}</li>`;
                        });
                        errorHtml +=
                            '</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                        $('.container-fluid').prepend(errorHtml);
                        $('body').scrollTop(0);
                    }
                });
            });

            // Handle Service/Custom step radio buttons
            $(document).on('change', '.item-type-radio', function() {
                const itemCard = $(this).closest('.item-card');
                const serviceSelection = itemCard.find('.service-selection');
                const customStepFields = itemCard.find('.custom-step-fields');
                const serviceSelect = itemCard.find('.service-select');
                const customTitle = itemCard.find('[name$="[title]"]');
                const customDescription = itemCard.find('[name$="[description]"]');

                if ($(this).val() === 'service') {
                    serviceSelection.slideDown();
                    customStepFields.slideUp();
                    serviceSelect.prop('required', true);
                    customTitle.prop('required', false).val('');
                    customDescription.prop('required', false).val('');
                } else {
                    serviceSelection.slideUp();
                    customStepFields.slideDown();
                    serviceSelect.prop('required', false).val('');
                    customTitle.prop('required', true);
                    customDescription.prop('required', true);
                }
                updateTotalCost();
            });

            // Auto-fill title and description when service is selected
            $(document).on('change', '.service-select', function() {
                const selectedOption = $(this).find('option:selected');
                const itemCard = $(this).closest('.item-card');
                const customTitle = itemCard.find('[name$="[title]"]');
                const customDescription = itemCard.find('[name$="[description]"]');

                if (selectedOption.val()) {
                    customTitle.val(selectedOption.text().split(' (')[0]);
                    customDescription.val(selectedOption.data('description'));
                } else {
                    customTitle.val('');
                    customDescription.val('');
                }
                updateTotalCost();
            });

            // Update total estimated cost
            function updateTotalCost() {
                let total = 0;
                $('.service-select').each(function() {
                    const price = parseFloat($(this).find('option:selected').data('price')) || 0;
                    total += price;
                });
                $('#total_estimated_cost').val(total.toFixed(2));
            }

            // Save as draft
            $('#save-draft').on('click', function() {
                showLoading();
                const form = $('#treatment-plan-form');
                form.append('<input type="hidden" name="status" value="draft">');

                // Remove required attributes for draft
                $('.needs-appointment-checkbox:checked').each(function() {
                    $(this).closest('.item-card').find(
                        '.appointment-fields input, .appointment-fields textarea').prop(
                        'required', false);
                });

                form.submit();
            });

            // Preview plan
            $('#preview-plan').on('click', function() {
                const formData = $('#treatment-plan-form').serializeArray();
                let previewHtml = `
                    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="previewModalLabel">
                                        <i class="fas fa-eye me-2"></i>
                                        Xem trước Kế hoạch Điều trị
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h5>Thông tin cơ bản</h5>
                                    <p><strong>Tiêu đề:</strong> ${formData.find(item => item.name === 'plan_title')?.value || ''}</p>
                                    <p><strong>Bệnh nhân:</strong> ${$('#patient_id option:selected').text() || 'Chưa chọn'}</p>
                                    <p><strong>Ngày bắt đầu:</strong> ${formData.find(item => item.name === 'start_date')?.value || ''}</p>
                                    <p><strong>Ngày kết thúc:</strong> ${formData.find(item => item.name === 'end_date')?.value || ''}</p>
                                    <p><strong>Chẩn đoán:</strong> ${formData.find(item => item.name === 'diagnosis')?.value || ''}</p>
                                    <p><strong>Mục tiêu:</strong> ${formData.find(item => item.name === 'goal')?.value || ''}</p>
                                    <p><strong>Ghi chú:</strong> ${formData.find(item => item.name === 'notes')?.value || ''}</p>
                                    <p><strong>Tổng chi phí:</strong> ${formData.find(item => item.name === 'total_estimated_cost')?.value || 0} VNĐ</p>
                                    <p><strong>Trạng thái:</strong> ${formData.find(item => item.name === 'status')?.value === 'active' ? 'Kích hoạt' : 'Nháp'}</p>

                                    <h5 class="mt-4">Các bước điều trị</h5>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tiêu đề</th>
                                                <th>Loại</th>
                                                <th>Ngày bắt đầu</th>
                                                <th>Ngày kết thúc</th>
                                                <th>Lịch hẹn</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                `;

                $('.item-card').each(function(idx) {
                    const serviceId = $(this).find('.service-select').val();
                    const title = serviceId ?
                        $(this).find('.service-select option:selected').text().split(' (')[0] :
                        $(this).find('[name$="[title]"]').val();
                    const type = $(this).find('.item-type-radio:checked').val() === 'service' ?
                        'Dịch vụ' : 'Tùy chỉnh';
                    const startDate = $(this).find('[name$="[expected_start_date]"]').val();
                    const endDate = $(this).find('[name$="[expected_end_date]"]').val();
                    const appointment = $(this).find('.needs-appointment-checkbox').is(':checked') ?
                        $(this).find('[name$="[appointment_time]"]').val() || 'Chưa đặt' :
                        'Không cần';

                    previewHtml += `
                        <tr>
                            <td>${idx + 1}</td>
                            <td>${title || 'Chưa nhập'}</td>
                            <td>${type}</td>
                            <td>${startDate || 'Chưa nhập'}</td>
                            <td>${endDate || 'Chưa nhập'}</td>
                            <td>${appointment}</td>
                        </tr>
                    `;
                });

                previewHtml += `
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('body').append(previewHtml);
                $('#previewModal').modal('show');
                $('#previewModal').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            });

            // Reset form
            $('#reset-form').on('click', function() {
                if (confirm('Bạn có chắc muốn đặt lại form? Tất cả thay đổi sẽ bị mất.')) {
                    const form = document.getElementById('treatment-plan-form');
                    form.reset();
                    $('#treatment-items-container').empty();
                    $('#total_estimated_cost').val('{{ $treatmentPlan->total_estimated_cost }}');
                    itemIndex = 0;
                    updateEmptyState();
                }
            });

            // Form validation
            $('#treatment-plan-form').on('submit', function(e) {
                showLoading();

                let isValid = true;
                const requiredFields = $(this).find('[required]');

                requiredFields.each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validate dates
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                if (endDate && new Date(endDate) < new Date(startDate)) {
                    isValid = false;
                    $('#end_date').addClass('is-invalid');
                    $('#end_date').after(
                        '<div class="invalid-feedback">Ngày kết thúc phải sau ngày bắt đầu.</div>');
                }

                $('.item-card').each(function() {
                    const expectedStartDate = $(this).find('[name$="[expected_start_date]"]').val();
                    const expectedEndDate = $(this).find('[name$="[expected_end_date]"]').val();
                    if (expectedEndDate && new Date(expectedEndDate) < new Date(
                            expectedStartDate)) {
                        isValid = false;
                        $(this).find('[name$="[expected_end_date]"]').addClass('is-invalid');
                        $(this).find('[name$="[expected_end_date]"]').after(
                            '<div class="invalid-feedback">Ngày kết thúc dự kiến phải sau ngày bắt đầu dự kiến.</div>'
                        );
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    hideLoading();

                    const errorAlert = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Vui lòng điền đầy đủ và kiểm tra các trường bắt buộc.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                    $('body').scrollTop(0);
                    $('.container-fluid').prepend(errorAlert);

                    setTimeout(function() {
                        $('.alert').fadeOut();
                    }, 5000);
                }
            });

            // Initialize total cost calculation and item states
            $(document).on('change', '.service-select, .item-type-radio, .remove-item', updateTotalCost);
            $('#treatment-items-container .item-card').each(function() {
                $(this).find('.item-type-radio:checked').trigger('change');
                $(this).find('.needs-appointment-checkbox').trigger('change');
                $(this).find('.service-select').trigger('change'); // Trigger to fill title/description
            });
            updateItemNumbers(); // Initialize item numbers on page load
            updateTotalCost();
            updateEmptyState();
        });
    </script>
@endpush
