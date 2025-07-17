@extends('admin.dashboard')
@section('title', 'Chi tiết đơn thuốc')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1 text-primary">Chi tiết đơn thuốc #{{ $prescription->id }}</h3>
                                <small class="text-muted">
                                    <i class="fas fa-clock mr-1"></i>
                                    Được tạo ngày
                                    {{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="btn-group">
                                @can('edit_prescriptions')
                                    <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}"
                                        class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i> Chỉnh sửa
                                    </a>
                                @endcan
                                <a href="{{ route('admin.prescriptions.print', $prescription->id) }}"
                                    class="btn btn-primary" target="_blank">
                                    <i class="fas fa-print"></i> In đơn thuốc
                                </a>
                                <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Alert -->
        <div class="row mb-4">
            <div class="col-12">
                @php
                    $hasExpiredMedicine = $prescription->items->some(function ($item) {
                        return $item->medicine->created_at
                            ? $item->medicine->created_at->lt(\Carbon\Carbon::now()->subMonths(6))
                            : false;
                    });
                @endphp

                @if ($hasExpiredMedicine)
                    <div class="alert alert-danger border-left-danger">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                            <div>
                                <strong>Cảnh báo!</strong>
                                <p class="mb-0">Đơn thuốc này chứa thuốc đã hết hạn sử dụng. Vui lòng kiểm tra lại.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-success border-left-success">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x mr-3"></i>
                            <div>
                                <strong>Đơn thuốc hợp lệ</strong>
                                <p class="mb-0">Tất cả thuốc trong đơn đều còn hạn sử dụng.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Information Cards -->
        <div class="row mb-4">
            <!-- Patient Information -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-injured mr-2"></i>
                            Thông tin bệnh nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="patient-info">
                            <div class="info-item">
                                <span class="info-label">Họ và tên:</span>
                                <span
                                    class="info-value">{{ $prescription->medicalRecord->appointment->patient->full_name }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Số điện thoại:</span>
                                <span class="info-value">
                                    <a href="tel:{{ $prescription->medicalRecord->appointment->patient->phone }}"
                                        class="text-primary">
                                        {{ $prescription->medicalRecord->appointment->patient->phone }}
                                    </a>
                                </span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Giới tính:</span>
                                <span class="info-value">
                                    <span
                                        class="badge badge-{{ $prescription->medicalRecord->appointment->patient->gender == 'Nam' ? 'primary' : 'info' }}">
                                        <i
                                            class="fas fa-{{ $prescription->medicalRecord->appointment->patient->gender == 'Nam' ? 'mars' : 'venus' }} mr-1"></i>
                                        {{ $prescription->medicalRecord->appointment->patient->gender ?? 'Không xác định' }}
                                    </span>
                                </span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Ngày sinh:</span>
                                <span class="info-value">
                                    @if ($prescription->medicalRecord->appointment->patient->date_of_birth)
                                        {{ \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->format('d/m/Y') }}
                                        <span class="badge badge-light ml-2">
                                            {{ \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->age }}
                                            tuổi
                                        </span>
                                    @else
                                        <span class="text-muted">Chưa cập nhật</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prescription Information -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-prescription-bottle-alt mr-2"></i>
                            Thông tin đơn thuốc
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="prescription-info">
                            <div class="info-item">
                                <span class="info-label">Bác sĩ kê đơn:</span>
                                <span class="info-value">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="text-right mr-3">
                                            <strong>{{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</strong>
                                            <br><small
                                                class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</small>
                                        </div>
                                    </div>
                                </span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Ngày kê đơn:</span>
                                <span class="info-value">
                                    <i class="fas fa-calendar-alt text-primary mr-1"></i>
                                    {{ $prescription->formatted_date }}
                                </span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Mã hồ sơ:</span>
                                <span class="info-value">
                                    <span class="badge badge-secondary">{{ $prescription->medicalRecord->code }}</span>
                                </span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Tổng số loại thuốc:</span>
                                <span class="info-value">
                                    <span class="badge badge-primary">{{ $prescription->items->count() }} loại</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Diagnosis & Notes Section -->
        <div class="row mb-4">
            @if ($prescription->medicalRecord->diagnosis)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-stethoscope mr-2"></i>
                                Chuẩn đoán
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="diagnosis-content">
                                <p class="mb-0">{{ $prescription->medicalRecord->diagnosis }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($prescription->notes)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-sticky-note mr-2"></i>
                                Ghi chú của bác sĩ
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="notes-content">
                                <p class="mb-0">{{ $prescription->notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Medicine List -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-pills mr-2"></i>
                                Danh sách thuốc kê đơn
                            </h5>
                            <span class="badge badge-light text-primary">{{ $prescription->items->count() }} loại
                                thuốc</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" width="5%">STT</th>
                                        <th width="25%">Thông tin thuốc</th>
                                        <th class="text-center" width="8%">Đơn vị</th>
                                        <th class="text-center" width="8%">Số lượng</th>
                                        <th class="text-right" width="12%">Giá đơn vị</th>
                                        <th class="text-right" width="12%">Thành tiền</th>
                                        <th class="text-center" width="12%">Ngày SX</th>
                                        <th width="18%">Hướng dẫn sử dụng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalAmount = 0; @endphp
                                    @foreach ($prescription->items as $index => $item)
                                        @php
                                            $expired = $item->medicine->created_at
                                                ? $item->medicine->created_at->lt(\Carbon\Carbon::now()->subMonths(6))
                                                : false;
                                            $itemTotal = $item->quantity * $item->medicine->price;
                                            $totalAmount += $itemTotal;
                                        @endphp
                                        <tr class="{{ $expired ? 'table-warning' : '' }}">
                                            <td class="text-center align-middle">
                                                <span class="badge badge-light">{{ $index + 1 }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="medicine-info">
                                                    <strong class="text-dark">{{ $item->medicine->name }}</strong>
                                                    @if ($item->medicine->dosage)
                                                        <br><small class="text-muted">
                                                            <i class="fas fa-capsules"></i> {{ $item->medicine->dosage }}
                                                        </small>
                                                    @endif
                                                    <br>
                                                    @if ($expired)
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-exclamation-triangle"></i> Hết hạn
                                                        </span>
                                                    @else
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Còn hạn
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-primary">{{ $item->medicine->unit }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-outline-primary">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-right align-middle">
                                                <span class="text-muted">{{ $item->medicine->formatted_price }}</span>
                                            </td>
                                            <td class="text-right align-middle">
                                                <strong class="text-success">{{ number_format($itemTotal, 3, ',', '.') }}
                                                    VNĐ</strong>
                                            </td>
                                            <td class="text-center align-middle">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar"></i>
                                                    {{ $item->medicine->created_at ? $item->medicine->created_at->format('d/m/Y') : 'N/A' }}
                                                </small>
                                            </td>
                                            <td class="align-middle">
                                                <small class="text-dark">
                                                    {{ $item->usage_instructions ?: 'Theo chỉ dẫn của bác sĩ' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-0">Tổng số lượng: <span
                                        class="text-primary">{{ $prescription->total_quantity }}</span></h6>
                            </div>
                            <div class="col-md-6 text-right">
                                <h5 class="mb-0 text-success">
                                    <strong>Tổng tiền: {{ number_format($totalAmount, 3, ',', '.') }} VNĐ</strong>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit History -->
        @if ($prescription->histories && $prescription->histories->count())
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-history mr-2"></i>
                                Lịch sử chỉnh sửa
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @foreach ($prescription->histories as $history)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="timeline-info">
                                                        <strong>{{ $history->changed_at->format('d/m/Y H:i') }}</strong>
                                                        <br><small
                                                            class="text-muted">{{ $history->user->full_name ?? 'Hệ thống' }}</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6>Ghi chú cũ:</h6>
                                                            <div class="alert alert-light">
                                                                {{ $history->old_data['notes'] ?? 'Không có' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>Ghi chú mới:</h6>
                                                            <div class="alert alert-primary">
                                                                {{ $history->new_data['notes'] ?? 'Không có' }}</div>
                                                        </div>
                                                    </div>
                                                    <h6>Thuốc được kê:</h6>
                                                    <div class="list-group">
                                                        @foreach ($history->new_data['medicines'] as $med)
                                                            <div class="list-group-item">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <strong>{{ $med['medicine_name'] }}</strong>
                                                                        @if ($med['usage_instructions'])
                                                                            <br><small
                                                                                class="text-muted">{{ $med['usage_instructions'] }}</small>
                                                                        @endif
                                                                    </div>
                                                                    <span class="badge badge-primary">SL:
                                                                        {{ $med['quantity'] }}</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Important Notes -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 border-left-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Lưu ý quan trọng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">
                                    <i class="fas fa-pills mr-2"></i>
                                    Hướng dẫn sử dụng thuốc
                                </h6>
                                <ul class="list-unstyled ml-3">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success mr-2"></i>
                                        Sử dụng thuốc đúng liều lượng và thời gian
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-times text-danger mr-2"></i>
                                        Không tự ý thay đổi liều lượng
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-thermometer-half text-info mr-2"></i>
                                        Bảo quản ở nơi khô ráo, mát mẻ
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">
                                    <i class="fas fa-user-md mr-2"></i>
                                    Khi có vấn đề
                                </h6>
                                <ul class="list-unstyled ml-3">
                                    <li class="mb-2">
                                        <i class="fas fa-phone text-success mr-2"></i>
                                        Liên hệ ngay với bác sĩ nếu có tác dụng phụ
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-calendar-check text-info mr-2"></i>
                                        Tuân thủ lịch tái khám
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-question-circle text-warning mr-2"></i>
                                        Hỏi dược sĩ nếu có thắc mắc
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom Blue Theme Styles */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 123, 255, 0.1) !important;
        }

        .card-header {
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .doctor-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .info-item {
            padding: 12px 0;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
        }

        .info-value {
            flex: 1;
            text-align: right;
        }

        .badge-light {
            background-color: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .medicine-info {
            line-height: 1.4;
        }

        .diagnosis-content,
        .notes-content {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #007bff;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            top: 8px;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 20px;
            width: 2px;
            height: calc(100% + 10px);
            background: #dee2e6;
        }

        .timeline-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            border-left: 3px solid #007bff;
        }

        .badge-outline-primary {
            background: transparent;
            color: #007bff;
            border: 1px solid #007bff;
        }

        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }

        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .thead-light th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            font-weight: 600;
            color: #495057;
        }

        .alert {
            border: none;
            border-radius: 8px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-primary {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* Print Styles */
        @media print {

            .card-header .btn-group,
            .card-footer,
            .btn,
            .sidebar,
            .navbar,
            .timeline {
                display: none !important;
            }

            .card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
                margin-bottom: 20px !important;
            }

            .table {
                border: 1px solid #000 !important;
                font-size: 12px !important;
            }

            .table th,
            .table td {
                border: 1px solid #000 !important;
                padding: 6px !important;
            }

            .card-header {
                background: #f8f9fa !important;
                color: #000 !important;
            }
        }
    </style>
@endsection
