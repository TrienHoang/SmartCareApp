@extends('admin.dashboard')
@section('title', 'Chỉnh sửa Đơn thuốc')

@push('styles')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Root variables for consistent theming */
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #3730a3;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --border-radius: 12px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Main card styling */
        .prescription-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .prescription-card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }

        .prescription-card-header h5 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .prescription-card-body {
            padding: 2rem;
        }

        /* Form styling */
        .form-section {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--gray-200);
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .form-control, .form-select {
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: var(--gray-50);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background-color: white;
        }

        /* Alert styling */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border-left: 4px solid var(--warning-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-left: 4px solid var(--danger-color);
        }

        /* Medicine item styling */
        .medicine-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .medicine-item:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .medicine-item-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .medicine-item-title {
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .medicine-item-number {
            background: var(--primary-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .remove-medicine-btn {
            background: var(--danger-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .remove-medicine-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        /* Button styling */
        .btn-modern {
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-secondary-modern {
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
            color: var(--gray-700);
            border: 2px solid var(--gray-300);
        }

        .btn-secondary-modern:hover {
            background: var(--gray-200);
            transform: translateY(-2px);
            color: var(--gray-800);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-danger-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .add-medicine-btn {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1.5rem auto;
            box-shadow: var(--shadow-sm);
        }

        .add-medicine-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Flatpickr customization */
        .flatpickr-input {
            background-color: var(--gray-50) !important;
            border: 2px solid var(--gray-200) !important;
            border-radius: 8px !important;
            padding: 0.75rem !important;
            font-size: 0.95rem !important;
        }

        .flatpickr-calendar {
            border-radius: var(--border-radius) !important;
            box-shadow: var(--shadow-lg) !important;
            border: none !important;
        }

        .flatpickr-months {
            background: var(--primary-color) !important;
            color: white !important;
        }

        .flatpickr-weekdays {
            background: var(--primary-hover) !important;
            color: white !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.today {
            background: var(--primary-color) !important;
            color: white !important;
        }

        /* Select2 customization */
        .select2-container--default .select2-selection--single {
            border: 2px solid var(--gray-200) !important;
            border-radius: 8px !important;
            height: 48px !important;
            background-color: var(--gray-50) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 44px !important;
            padding-left: 12px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary-color) !important;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .prescription-card-body {
                padding: 1rem;
            }
            
            .form-section {
                padding: 1rem;
            }
            
            .medicine-item {
                padding: 1rem;
            }
        }

        /* Animation for new medicine items */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .medicine-item-new {
            animation: slideIn 0.3s ease-out;
        }

        /* Empty state styling */
        .empty-medicines {
            text-align: center;
            padding: 3rem;
            color: var(--gray-600);
        }

        .empty-medicines i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--gray-300);
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="py-3 breadcrumb-wrapper mb-0">
                <span class="text-muted fw-light">Prescriptions /</span> 
                <span class="text-primary">Chỉnh sửa Đơn thuốc</span>
            </h4>
            <div class="breadcrumb-actions">
                <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary-modern">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('warning') }}
            </div>
        @endif

        @if ($prescription->is_finalized)
            <div class="alert alert-danger">
                <i class="fas fa-lock"></i>
                Đơn thuốc này đã được hoàn tất và có thể không nên chỉnh sửa.
            </div>
        @endif

        <!-- Main Card -->
        <div class="prescription-card">
            <div class="prescription-card-header">
                <h5>
                    <i class="fas fa-prescription-bottle-alt"></i>
                    Chỉnh sửa Đơn thuốc #{{ $prescription->id }}
                </h5>
            </div>

            <div class="prescription-card-body">
                <form action="{{ route('admin.prescriptions.update', $prescription->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-info-circle"></i>
                            Thông tin cơ bản
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medical_record_id" class="form-label">
                                        <i class="fas fa-file-medical"></i>
                                        Hồ sơ bệnh án
                                    </label>
                                    <select name="medical_record_id" id="medical_record_id" class="form-select" disabled>
                                        @foreach ($medicalRecords as $record)
                                            <option value="{{ $record->id }}"
                                                {{ $prescription->medical_record_id == $record->id ? 'selected' : '' }}>
                                                #{{ $record->id }} - {{ $record->appointment->patient->full_name ?? 'Bệnh nhân' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="medical_record_id" value="{{ $prescription->medical_record_id }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prescribed_at" class="form-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        Ngày kê toa
                                    </label>
                                    <input type="text" name="prescribed_at" id="prescribed_at"
                                        class="form-control flatpickr @error('prescribed_at') is-invalid @enderror"
                                        value="{{ old('prescribed_at', \Carbon\Carbon::parse($prescription->prescribed_at)->format('Y-m-d H:i')) }}"
                                        placeholder="Chọn ngày và giờ">
                                    @error('prescribed_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note"></i>
                                Ghi chú
                            </label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Nhập ghi chú cho đơn thuốc (tùy chọn)">{{ old('notes', $prescription->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Medicines Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-pills"></i>
                            Danh sách thuốc được kê
                            <span class="badge bg-primary ms-2" id="medicine-count">{{ count($prescription->items) }}</span>
                        </div>

                        <div id="medicine-list">
                            @foreach ($prescription->items as $index => $item)
                                <div class="medicine-item" data-index="{{ $index }}">
                                    <div class="medicine-item-header">
                                        <div class="medicine-item-title">
                                            <div class="medicine-item-number">{{ $index + 1 }}</div>
                                            Thuốc {{ $index + 1 }}
                                        </div>
                                        <button type="button" class="remove-medicine-btn" aria-label="Xóa thuốc">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <label class="form-label">
                                                <i class="fas fa-prescription-bottle"></i>
                                                Tên thuốc
                                            </label>
                                            <select name="medicines[{{ $index }}][medicine_id]" class="form-select medicine-select">
                                                <option value="">-- Chọn thuốc --</option>
                                                @foreach ($medicines as $med)
                                                    <option value="{{ $med->id }}"
                                                        {{ $item->medicine_id == $med->id ? 'selected' : '' }}>
                                                        {{ $med->name }} ({{ $med->unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">
                                                <i class="fas fa-sort-numeric-up"></i>
                                                Số lượng
                                            </label>
                                            <input type="number" name="medicines[{{ $index }}][quantity]"
                                                class="form-control" value="{{ $item->quantity }}" min="1">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">
                                                <i class="fas fa-instructions"></i>
                                                Hướng dẫn sử dụng
                                            </label>
                                            <textarea name="medicines[{{ $index }}][usage_instructions]" rows="2" 
                                                class="form-control" placeholder="Ví dụ: Uống sau ăn, ngày 2 lần">{{ $item->usage_instructions }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center">
                            <button type="button" onclick="addMedicine()" class="add-medicine-btn">
                                <i class="fas fa-plus"></i>
                                Thêm thuốc mới
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-danger-modern">
                            <i class="fas fa-times"></i>
                            Hủy bỏ
                        </a>
                        <button type="submit" class="btn btn-primary-modern">
                            <i class="fas fa-save"></i>
                            Cập nhật đơn thuốc
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
        let medicineIndex = {{ count($prescription->items) }};

        // Initialize Flatpickr
        flatpickr("#prescribed_at", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000),
            disableMobile: true,
            appendTo: document.body,
            locale: {
                firstDayOfWeek: 1
            }
        });

        // Initialize Select2 for existing medicine selects
        $(document).ready(function() {
            $('.medicine-select').select2({
                placeholder: '-- Chọn thuốc --',
                allowClear: true
            });
        });
    </script>
    <script src="{{ asset('js/Prescription/edit.js') }}"></script>
@endpush