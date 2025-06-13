@extends('admin.dashboard')
@section('title', 'Chi tiết đơn thuốc')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chi tiết đơn thuốc #{{ $prescription->id }}</h3>
                        <div class="float-right">
                            @can('edit_prescriptions')
                                <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </a>
                            @endcan
                            <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Thông tin bệnh nhân</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td width="30%"><strong>Họ tên:</strong></td>
                                        <td>{{ $prescription->medicalRecord->appointment->patient->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>SĐT:</strong></td>
                                        <td>{{ $prescription->medicalRecord->appointment->patient->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Giới tính:</strong></td>
                                        <td>{{ $prescription->medicalRecord->appointment->patient->gender ?? 'Không xác định' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ngày sinh:</strong></td>
                                        <td>{{ $prescription->medicalRecord->appointment->patient->date_of_birth ? \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->format('d/m/Y') : 'Không xác định' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Thông tin đơn thuốc</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td width="30%"><strong>Bác sĩ kê đơn:</strong></td>
                                        <td>{{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Chuyên môn:</strong></td>
                                        <td>{{ $prescription->medicalRecord->appointment->doctor->specialization }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ngày kê đơn:</strong></td>
                                        <td>{{ $prescription->formatted_date }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Hồ sơ bệnh án:</strong></td>
                                        <td>{{ $prescription->medicalRecord->code }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($prescription->notes)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>Chuẩn đoán</h5>
                                    <div class="alert alert-info">
                                        {{ $prescription->medicalRecord->diagnosis }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>Ghi chú</h5>
                                    <div class="alert alert-info">
                                        {{ $prescription->notes }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Danh sách thuốc</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="5%">STT</th>
                                                <th width="25%">Tên thuốc</th>
                                                <th width="15%">Đơn vị</th>
                                                <th width="10%">Số lượng</th>
                                                <th width="15%">Giá đơn vị</th>
                                                <th width="15%">Ngày sản xuất</th>
                                                <th width="30%">Hướng dẫn sử dụng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($prescription->items as $index => $item)
                                                @php
                                                    $expired = $item->medicine->created_at->lt(
                                                        \Carbon\Carbon::now()->subMonths(6),
                                                    );
                                                @endphp
                                                <tr class="{{ $expired ? 'table-danger' : '' }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $item->medicine->name }}</strong>
                                                        @if ($item->medicine->dosage)
                                                            <br><small
                                                                class="text-muted">{{ $item->medicine->dosage }}</small>
                                                        @endif
                                                        @if ($expired)
                                                            <br><span class="text-danger font-weight-bold">Thuốc đã hết
                                                                hạn</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->medicine->unit }}</td>
                                                    <td>
                                                        <span>{{ $item->quantity }}</span>
                                                    </td>
                                                    <td>{{ $item->medicine->formatted_price }}</td>
                                                    <td>{{ $item->medicine->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        {{ $item->usage_instructions ?: 'Theo chỉ dẫn của bác sĩ' }}
                                                    </td>
                                                </tr>
                                            @endforeach


                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-light">
                                                <td colspan="3"><strong>Tổng cộng</strong></td>
                                                <td><strong>{{ $prescription->total_quantity }}</strong></td>
                                                <td colspan="3">
                                                    <strong>{{ number_format($prescription->items->sum(function ($item) {return $item->quantity * $item->medicine->price;}),3,',','.') }}
                                                        VNĐ</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Lưu ý quan trọng:</h6>
                                    <ul class="mb-0">
                                        <li>Sử dụng thuốc đúng liều lượng và thời gian theo chỉ dẫn</li>
                                        <li>Không tự ý thay đổi liều lượng hoặc ngừng thuốc</li>
                                        <li>Nếu có tác dụng phụ, liên hệ ngay với bác sĩ</li>
                                        <li>Bảo quản thuốc ở nơi khô ráo, thoáng mát</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.prescriptions.print', $prescription->id) }}" class="btn btn-primary"
                            target="_blank"><i class="fas fa-print"></i> In đơn thuốc </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .card-header,
            .card-footer,
            .btn,
            .sidebar,
            .navbar {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endsection
