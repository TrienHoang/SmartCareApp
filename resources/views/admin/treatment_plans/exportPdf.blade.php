<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kế hoạch Điều trị #{{ $plan->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .header .plan-id {
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }

        /* Status Badge */
        .status-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 14px;
        }

        .status-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .status-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .status-secondary { background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }

        /* Progress Bar */
        .progress-section {
            margin-bottom: 30px;
            text-align: center;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-fill {
            height: 100%;
            color: white;
            text-align: center;
            line-height: 20px;
            font-weight: bold;
            font-size: 11px;
        }

        .progress-info { background-color: #17a2b8; }
        .progress-success { background-color: #28a745; }
        .progress-secondary { background-color: #6c757d; }

        /* Two Column Layout */
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .column {
            display: table-cell;
            vertical-align: top;
            padding: 0 10px;
        }

        .column-left {
            width: 35%;
        }

        .column-right {
            width: 65%;
        }

        /* Card Styles */
        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #fff;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 12px 15px;
            font-weight: bold;
            color: #495057;
        }

        .card-body {
            padding: 15px;
        }

        .info-item {
            margin-bottom: 12px;
            padding-left: 15px;
            border-left: 3px solid #007bff;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .info-value {
            color: #333;
            font-size: 12px;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
        }

        .table td {
            font-size: 11px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .text-center { text-align: center; }
        .text-primary { color: #007bff; }
        .text-success { color: #28a745; }
        .text-warning { color: #ffc107; }
        .text-danger { color: #dc3545; }
        .text-info { color: #17a2b8; }
        .text-muted { color: #6c757d; }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 12px;
        }

        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
            color: #6c757d;
            font-size: 10px;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* No Data Message */
        .no-data {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>KẾ HOẠCH ĐIỀU TRỊ</h1>
            <div class="plan-id">#{{ $plan->id }}</div>
        </div>

        <!-- Status -->
        <div class="status-section">
            @php
                $statusClass = 'status-secondary';
                $statusText = 'Không xác định';
                
                switch($plan->status) {
                    case 'dang_tien_hanh':
                        $statusClass = 'status-warning';
                        $statusText = 'Đang tiến hành';
                        break;
                    case 'hoan_thanh':
                        $statusClass = 'status-success';
                        $statusText = 'Hoàn thành';
                        break;
                    case 'tam_dung':
                        $statusClass = 'status-danger';
                        $statusText = 'Tạm dừng';
                        break;
                    case 'huy_bo':
                        $statusClass = 'status-secondary';
                        $statusText = 'Hủy bỏ';
                        break;
                }
            @endphp
            <div class="status-badge {{ $statusClass }}">
                {{ $statusText }}
            </div>
        </div>

        <!-- Progress -->
        <div class="progress-section">
            @php
                $startDate = $plan->start_date;
                $endDate = $plan->end_date;
                $currentDate = \Carbon\Carbon::now();
                
                $progress = 0;
                $totalDays = 0;
                $elapsedDays = 0;
                $remainingDays = 0;
                $progressClass = 'progress-secondary';
                $statusText = 'Chưa xác định';
                
                if ($startDate && $endDate && $startDate->lte($endDate)) {
                    $totalDays = $endDate->diffInDays($startDate) + 1;
                    if ($totalDays > 0) {
                        if ($currentDate->greaterThanOrEqualTo($endDate)) {
                            $progress = 100;
                            $elapsedDays = $totalDays;
                            $remainingDays = 0;
                            $progressClass = 'progress-success';
                            $statusText = 'Đã kết thúc';
                        } elseif ($currentDate->lessThanOrEqualTo($startDate)) {
                            $progress = 0;
                            $elapsedDays = 0;
                            $remainingDays = $startDate->diffInDays($currentDate) + 1;
                            $progressClass = 'progress-secondary';
                            $statusText = 'Chưa bắt đầu';
                        } else {
                            $elapsedDays = $currentDate->diffInDays($startDate);
                            $progress = ($elapsedDays / $totalDays) * 100;
                            $remainingDays = $endDate->diffInDays($currentDate);
                            $progressClass = 'progress-info';
                            $statusText = 'Đang tiến hành';
                        }
                    }
                    
                    if ($plan->status === 'hoan_thanh') {
                        $progress = 100;
                        $progressClass = 'progress-success';
                        $statusText = 'Đã hoàn thành';
                    }
                }
                $progress = round($progress);
            @endphp
            
            <div class="progress-bar">
                <div class="progress-fill {{ $progressClass }}" style="width: {{ $progress }}%;">
                    {{ $progress }}%
                </div>
            </div>
            <div style="margin-top: 10px; font-size: 11px; color: #666;">
                <strong>{{ $statusText }}</strong><br>
                @if ($startDate && $endDate)
                    {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                    @if ($remainingDays > 0 && $currentDate->lessThan($startDate))
                        <br>Còn {{ $remainingDays }} ngày để bắt đầu
                    @elseif($remainingDays > 0 && $currentDate->lessThanOrEqualTo($endDate) && $currentDate->greaterThanOrEqualTo($startDate) && $plan->status !== 'hoan_thanh')
                        <br>Còn {{ $remainingDays }} ngày
                    @endif
                @else
                    Chưa xác định thời gian
                @endif
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="two-column">
            <!-- Left Column -->
            <div class="column column-left">
                <!-- Doctor Info -->
                <div class="card">
                    <div class="card-header">
                        THÔNG TIN BÁC SĨ
                    </div>
                    <div class="card-body">
                        @if ($plan->doctor)
                            <div class="info-item">
                                <div class="info-label">Tên đầy đủ:</div>
                                <div class="info-value">{{ $plan->doctor->user->full_name ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Chuyên khoa:</div>
                                <div class="info-value">{{ $plan->doctor->doctor->specialization ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Số điện thoại:</div>
                                <div class="info-value">{{ $plan->doctor->user->phone ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Email:</div>
                                <div class="info-value">{{ $plan->doctor->user->email ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Địa chỉ:</div>
                                <div class="info-value">{{ $plan->doctor->user->address ?? 'N/A' }}</div>
                            </div>
                        @else
                            <div class="no-data">Không có thông tin bác sĩ</div>
                        @endif
                    </div>
                </div>

                <!-- Patient Info -->
                <div class="card">
                    <div class="card-header">
                        THÔNG TIN BỆNH NHÂN
                    </div>
                    <div class="card-body">
                        @if ($plan->patient)
                            <div class="info-item">
                                <div class="info-label">Tên đầy đủ:</div>
                                <div class="info-value">{{ $plan->patient->full_name ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Ngày sinh:</div>
                                <div class="info-value">{{ $plan->patient->date_of_birth ? $plan->patient->date_of_birth->format('d/m/Y') : 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Giới tính:</div>
                                <div class="info-value">{{ $plan->patient->gender ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Số điện thoại:</div>
                                <div class="info-value">{{ $plan->patient->phone ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Email:</div>
                                <div class="info-value">{{ $plan->patient->email ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Địa chỉ:</div>
                                <div class="info-value">{{ $plan->patient->address ?? 'N/A' }}</div>
                            </div>
                        @else
                            <div class="no-data">Không có thông tin bệnh nhân</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="column column-right">
                <!-- Plan Overview -->
                <div class="card">
                    <div class="card-header">
                        TỔNG QUAN KẾ HOẠCH
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-label">Tiêu đề:</div>
                            <div class="info-value">{{ $plan->plan_title ?? 'Chưa có tiêu đề' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tổng chi phí (ước tính):</div>
                            <div class="info-value text-success">{{ number_format($plan->total_estimated_cost, 0, ',', '.') }} VND</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Chẩn đoán:</div>
                            <div class="info-value">{{ $plan->diagnosis ?? 'Chưa có thông tin' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Mục tiêu:</div>
                            <div class="info-value">{{ $plan->goal ?? 'Chưa có thông tin' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Ghi chú:</div>
                            <div class="info-value">{{ $plan->notes ?? 'Không có ghi chú' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Ngày tạo:</div>
                            <div class="info-value">{{ $plan->created_at ? $plan->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Cập nhật lần cuối:</div>
                            <div class="info-value">{{ $plan->updated_at ? $plan->updated_at->format('d/m/Y H:i') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Treatment Steps -->
        @if ($plan->items && $plan->items->count() > 0)
            <div class="card">
                <div class="card-header">
                    CÁC BƯỚC TRONG KẾ HOẠCH ({{ $plan->items->count() }} bước)
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 8%;">STT</th>
                                <th style="width: 20%;">Tiêu đề</th>
                                <th style="width: 25%;">Mô tả</th>
                                <th style="width: 10%;">Tần suất</th>
                                <th style="width: 20%;">Thời gian</th>
                                <th style="width: 12%;">Trạng thái</th>
                                <th style="width: 15%;">Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plan->items as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td><strong>{{ $item->title ?? 'Không có' }}</strong></td>
                                    <td>{{ $item->description ?? 'Không có' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $item->frequency ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if ($item->expected_start_date)
                                            <div style="margin-bottom: 3px;">
                                                <strong>Bắt đầu:</strong> {{ $item->expected_start_date->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        @if ($item->expected_end_date)
                                            <div style="margin-bottom: 3px;">
                                                <strong>Kết thúc dự kiến:</strong> {{ $item->expected_end_date->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        @if ($item->actual_end_date)
                                            <div>
                                                <strong>Hoàn tất:</strong> {{ $item->actual_end_date->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        @if (!$item->expected_start_date && !$item->expected_end_date && !$item->actual_end_date)
                                            <span class="text-muted">Chưa có thông tin</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusClass = 'badge-secondary';
                                            $statusText = 'Không xác định';
                                            
                                            switch($item->status) {
                                                case 'chua_thuc_hien':
                                                    $statusClass = 'badge-secondary';
                                                    $statusText = 'Chưa thực hiện';
                                                    break;
                                                case 'dang_thuc_hien':
                                                    $statusClass = 'badge-warning';
                                                    $statusText = 'Đang thực hiện';
                                                    break;
                                                case 'hoan_thanh':
                                                    $statusClass = 'badge-success';
                                                    $statusText = 'Hoàn thành';
                                                    break;
                                                case 'tam_dung':
                                                    $statusClass = 'badge-danger';
                                                    $statusText = 'Tạm dừng';
                                                    break;
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>{{ $item->notes ?? 'Không có' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="no-data">
                        <strong>Kế hoạch này chưa có bước điều trị chi tiết</strong><br>
                        Vui lòng chỉnh sửa kế hoạch để thêm các bước điều trị cụ thể.
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>Kế hoạch điều trị được xuất vào ngày {{ now()->format('d/m/Y H:i') }}</div>
            <div>Hệ thống quản lý bệnh viện</div>
        </div>
    </div>
</body>
</html>