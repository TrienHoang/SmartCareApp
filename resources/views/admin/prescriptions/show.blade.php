@extends('admin.dashboard')
@section('title', 'Chi tiết đơn thuốc')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Chi tiết đơn thuốc #{{ $prescription->id }}</h2>
                        <small class="text-muted">Được tạo ngày {{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') }}</small>
                    </div>
                    <div class="btn-group">
                        @can('edit_prescriptions')
                            <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                        @endcan
                        <a href="{{ route('admin.prescriptions.print', $prescription->id) }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-print"></i> In đơn thuốc
                        </a>
                        <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="row mb-3">
            <div class="col-12">
                @php
                    $hasExpiredMedicine = $prescription->items->some(function($item) {
                        return $item->medicine->created_at ? $item->medicine->created_at->lt(\Carbon\Carbon::now()->subMonths(6)) : false;
                    });
                @endphp
                
                @if($hasExpiredMedicine)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Cảnh báo:</strong> Đơn thuốc này chứa thuốc đã hết hạn sử dụng!
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Tình trạng:</strong> Đơn thuốc hợp lệ, tất cả thuốc còn hạn sử dụng
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Patient Information -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin bệnh nhân</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 font-weight-bold">Họ tên:</div>
                            <div class="col-sm-8">{{ $prescription->medicalRecord->appointment->patient->full_name }}</div>
                        </div>
                        <hr class="my-2">
                        
                        <div class="row">
                            <div class="col-sm-4 font-weight-bold">Số điện thoại:</div>
                            <div class="col-sm-8">
                                <a href="tel:{{ $prescription->medicalRecord->appointment->patient->phone }}" class="text-decoration-none">
                                    {{ $prescription->medicalRecord->appointment->patient->phone }}
                                </a>
                            </div>
                        </div>
                        <hr class="my-2">
                        
                        <div class="row">
                            <div class="col-sm-4 font-weight-bold">Giới tính:</div>
                            <div class="col-sm-8">
                                <span class="badge badge-{{ $prescription->medicalRecord->appointment->patient->gender == 'Nam' ? 'primary' : 'pink' }}">
                                    {{ $prescription->medicalRecord->appointment->patient->gender ?? 'Không xác định' }}
                                </span>
                            </div>
                        </div>
                        <hr class="my-2">
                        
                        <div class="row">
                            <div class="col-sm-4 font-weight-bold">Ngày sinh:</div>
                            <div class="col-sm-8">
                                @if($prescription->medicalRecord->appointment->patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->format('d/m/Y') }}
                                    <small class="text-muted">
                                        ({{ \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->age }} tuổi)
                                    </small>
                                @else
                                    <span class="text-muted">Không xác định</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prescription Information -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-prescription-bottle-alt"></i> Thông tin đơn thuốc</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5 font-weight-bold">Bác sĩ kê đơn:</div>
                            <div class="col-sm-7">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center text-white mr-2">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</strong>
                                        <br><small class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        
                        <div class="row">
                            <div class="col-sm-5 font-weight-bold">Ngày kê đơn:</div>
                            <div class="col-sm-7">
                                <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                <td>{{ $prescription->formatted_date }}</td>
                            </div>
                        </div>
                        <hr class="my-2">
                        
                        <div class="row">
                            <div class="col-sm-5 font-weight-bold">Mã hồ sơ:</div>
                            <div class="col-sm-7">
                                <span class="badge badge-secondary">{{ $prescription->medicalRecord->code }}</span>
                            </div>
                        </div>
                        <hr class="my-2">
                        
                        <div class="row">
                            <div class="col-sm-5 font-weight-bold">Tổng số loại thuốc:</div>
                            <div class="col-sm-7">
                                <span class="badge badge-info">{{ $prescription->items->count() }} loại</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Diagnosis & Notes Section -->
        <div class="row">
            @if($prescription->medicalRecord->diagnosis)
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-diagnoses"></i> Chuẩn đoán</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border-left-warning">
                                {{ $prescription->medicalRecord->diagnosis }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($prescription->notes)
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Ghi chú của bác sĩ</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border-left-info">
                                {{ $prescription->notes }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Medicine List -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-pills"></i> Danh sách thuốc kê đơn</h5>
                        <span class="badge badge-light" style="color: #516377">{{ $prescription->items->count() }} loại thuốc</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%">STT</th>
                                        <th width="25%">Thông tin thuốc</th>
                                        <th width="10%">Đơn vị</th>
                                        <th width="10%">Số lượng</th>
                                        <th width="12%">Giá đơn vị</th>
                                        <th width="12%">Thành tiền</th>
                                        <th width="12%">Ngày SX</th>
                                        <th width="14%">Hướng dẫn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalAmount = 0; @endphp
                                    @foreach ($prescription->items as $index => $item)
                                        @php
                                            $expired = $item->medicine->created_at ? $item->medicine->created_at->lt(\Carbon\Carbon::now()->subMonths(6)) : false;
                                            $itemTotal = $item->quantity * $item->medicine->price;
                                            $totalAmount += $itemTotal;
                                        @endphp
                                        <tr class="{{ $expired ? 'table-danger' : '' }}">
                                            <td class="align-middle text-center">
                                                <span class="badge badge-secondary">{{ $index + 1 }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    <strong class="text-primary">{{ $item->medicine->name }}</strong>
                                                    @if ($item->medicine->dosage)
                                                        <br><small class="text-muted"><i class="fas fa-capsules"></i> {{ $item->medicine->dosage }}</small>
                                                    @endif
                                                    @if ($expired)
                                                        <br><span class="badge badge-danger">
                                                            <i class="fas fa-exclamation-triangle"></i> Hết hạn
                                                        </span>
                                                    @else
                                                        <br><span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Còn hạn
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-info">{{ $item->medicine->unit }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="font-weight-bold text-primary">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="align-middle text-right">
                                                {{ $item->medicine->formatted_price }}
                                            </td>
                                            <td class="align-middle text-right">
                                                <strong class="text-success">{{ number_format($itemTotal, 3, ',', '.') }} VNĐ</strong>
                                            </td>
                                            <td class="align-middle text-center">
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
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="font-weight-bold text-right">Tổng cộng:</td>
                                        <td class="text-center font-weight-bold text-primary">
                                            {{ $prescription->total_quantity }}
                                        </td>
                                        <td></td>
                                        <td class="text-right font-weight-bold text-success h5 mb-0">
                                            {{ number_format($totalAmount, 3, ',', '.') }} VNĐ
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit History -->
        @if ($prescription->histories && $prescription->histories->count())
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-history"></i> Lịch sử chỉnh sửa</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @foreach ($prescription->histories as $history)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>{{ $history->changed_at->format('d/m/Y H:i') }}</strong>
                                                    <br><small class="text-muted">{{ $history->user->full_name ?? 'Hệ thống' }}</small>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6>Ghi chú cũ:</h6>
                                                            <div class="alert alert-light">{{ $history->old_data['notes'] ?? 'Không có' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>Ghi chú mới:</h6>
                                                            <div class="alert alert-info">{{ $history->new_data['notes'] ?? 'Không có' }}</div>
                                                        </div>
                                                    </div>
                                                    <h6>Thuốc được kê:</h6>
                                                    <div class="list-group">
                                                        @foreach ($history->new_data['medicines'] as $med)
                                                            <div class="list-group-item">
                                                                <strong>{{ $med['medicine_name'] }}</strong>
                                                                <span class="badge badge-primary ml-2">SL: {{ $med['quantity'] }}</span>
                                                                @if($med['usage_instructions'])
                                                                    <br><small class="text-muted">HD: {{ $med['usage_instructions'] }}</small>
                                                                @endif
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
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Lưu ý quan trọng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-pills text-primary"></i> Về thuốc:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> Sử dụng thuốc đúng liều lượng và thời gian</li>
                                    <li><i class="fas fa-times text-danger"></i> Không tự ý thay đổi liều lượng</li>
                                    <li><i class="fas fa-thermometer-half text-info"></i> Bảo quản ở nơi khô ráo, mát mẻ</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-user-md text-success"></i> Khi có vấn đề:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-phone text-primary"></i> Liên hệ ngay với bác sĩ nếu có tác dụng phụ</li>
                                    <li><i class="fas fa-calendar-check text-info"></i> Tuân thủ lịch tái khám</li>
                                    <li><i class="fas fa-question-circle text-warning"></i> Hỏi dược sĩ nếu có thắc mắc</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-sm {
            width: 2rem;
            height: 2rem;
        }
        
        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }
        
        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }
        
        .timeline-marker {
            position: absolute;
            left: -2rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            top: 0.5rem;
        }
        
        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 1.5rem;
            width: 2px;
            height: calc(100% + 1rem);
            background-color: #dee2e6;
        }
        
        .badge-pink {
            color: #fff;
            background-color: #e91e63;
        }
        
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
            }
            
            .table {
                border: 1px solid #000 !important;
            }
            
            .table th,
            .table td {
                border: 1px solid #000 !important;
                padding: 8px !important;
            }
        }
    </style>
@endsection