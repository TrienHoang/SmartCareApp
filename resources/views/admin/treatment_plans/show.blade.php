@extends('admin.dashboard') {{-- Đảm bảo đây là layout chính xác của bạn --}}
@section('title', 'Chi tiết Kế hoạch Điều trị')
@section('content')
    <div class="container-fluid py-4">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3 animate__animated animate__fadeInDown">
                    <div class="mb-3 mb-lg-0 ">
                        <h2 class="mb-1 text-primary font-weight-bold ">
                            <i class="fas fa-clipboard-list mr-2 opacity-75"></i>Chi tiết Kế hoạch Điều trị
                        </h2>
                        <span class="text-muted font-weight-light">#{{ $plan->plan_title }}</span>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0">
                                <li class="">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                        Trang chủ >
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-primary font-weight-semibold">
                                    Chi tiết
                                </li>
                            </ol>
                        </nav>

                    </div>


                    <div class="card-tools d-flex flex-wrap align-items-center gap-2">
                        <a href="{{ route('admin.treatment-plans.exportPdf', $plan->id) }}"
                            class="btn btn-danger btn-sm ml-2">
                            <i class="fas fa-file-pdf mr-1"></i> Xuất PDF
                        </a>
                        <a href="{{ route('admin.treatment-plans.exportExcel', $plan->id) }}"
                            class="btn btn-success btn-sm ml-2">
                            <i class="fas fa-file-excel mr-1"></i> Xuất Excel
                        </a>
                        {{-- <a href="{{ route('admin.treatment-plans.edit', $plan->id) }}"
                            class="btn btn-primary btn-sm btn-hover-scale-light" data-toggle="tooltip"
                            title="Chỉnh sửa kế hoạch điều trị">
                            <i class="fas fa-edit mr-1"></i> Chỉnh sửa
                        </a> --}}
                        <a href="{{ route('admin.treatment-plans.history', $plan->id) }}" class="btn btn-info btn-sm"
                            data-toggle="tooltip" title="Xem lịch sử thay đổi">
                            <i class="fas fa-history mr-1"></i> Lịch sử
                        </a>
                        <a href="{{ route('admin.treatment-plans.index') }}" class="btn btn-secondary btn-sm"
                            data-toggle="tooltip" title="Quay lại danh sách kế hoạch">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Alert --}}
        @if ($plan->status === 'hoan_thanh')
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm animate__animated animate__fadeInUp"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle mr-2 fa-lg"></i>
                            <strong class="mr-1">Thông báo:</strong> Kế hoạch điều trị này đã <strong>hoàn thành</strong>.
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="row">
            {{-- Left Column: Status & Progress, Doctor Info, Patient Info --}}
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="h-100 d-flex flex-column">
                    {{-- Status & Progress Card --}}
                    <div class="card shadow border-0 rounded-lg mb-4 animate__animated animate__fadeInLeft">
                        <div class="card-header bg-gradient-primary text-white border-0 d-flex align-items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            <h6 class="card-title mb-0 font-weight-bold">Trạng thái & Tiến độ</h6>
                        </div>
                        <div class="card-body text-center py-4">
                            <div class="mb-3">
                                @switch($plan->status)
                                    @case('dang_tien_hanh')
                                        <span
                                            class="badge badge-warning badge-lg px-3 py-2 animate__animated animate__pulse animate__infinite">
                                            <i class="fas fa-spinner fa-spin mr-2"></i> Đang tiến hành
                                        </span>
                                    @break

                                    @case('chua_tien_hanh')
                                        <span class="badge badge-secondary badge-lg px-3 py-2 ">
                                            <i class="fas fa-spin mr-2"></i> Chưa tiến hành
                                        </span>
                                    @break

                                    @case('hoan_thanh')
                                        <span class="badge badge-success badge-lg px-3 py-2 animate__animated animate__bounceIn">
                                            <i class="fas fa-check-circle mr-2"></i> Hoàn thành
                                        </span>
                                    @break

                                    @case('tam_dung')
                                        <span class="badge badge-danger badge-lg px-3 py-2 animate__animated animate__shakeX">
                                            <i class="fas fa-pause-circle mr-2"></i> Tạm dừng
                                        </span>
                                    @break

                                    @case('huy_bo')
                                        <span class="badge badge-danger badge-lg px-3 py-2">
                                            <i class="fas fa-times-circle mr-2"></i> Hủy bỏ
                                        </span>
                                    @break

                                    @default
                                        <span class="badge badge-secondary badge-lg px-3 py-2">{{ $plan->status }}</span>
                                @endswitch
                            </div>

                            {{-- Progress Calculation --}}
                            @php
                                $startDate = $plan->start_date;
                                $endDate = $plan->end_date;
                                $currentDate = \Carbon\Carbon::now();

                                $progress = 0;
                                $totalDays = 0;
                                $elapsedDays = 0;
                                $remainingDays = 0;
                                $progressClass = 'bg-secondary';
                                $statusText = 'Chưa xác định';

                                if ($startDate && $endDate && $startDate->lte($endDate)) {
                                    $totalDays = $endDate->diffInDays($startDate) + 1;
                                    if ($totalDays > 0) {
                                        if ($currentDate->greaterThanOrEqualTo($endDate)) {
                                            $progress = 100;
                                            $elapsedDays = $totalDays;
                                            $remainingDays = 0;
                                            $progressClass = 'bg-success';
                                            $statusText = 'Đã kết thúc';
                                        } elseif ($currentDate->lessThanOrEqualTo($startDate)) {
                                            $progress = 0;
                                            $elapsedDays = 0;
                                            $remainingDays = $startDate->diffInDays($currentDate) + 1;
                                            $progressClass = 'bg-secondary';
                                            $statusText = 'Chưa bắt đầu';
                                        } else {
                                            $elapsedDays = $currentDate->diffInDays($startDate);
                                            $progress = ($elapsedDays / $totalDays) * 100;
                                            $remainingDays = $endDate->diffInDays($currentDate);
                                            $progressClass = 'bg-info';
                                            $statusText = 'Đang tiến hành';
                                        }
                                    }

                                    if ($plan->status === 'hoan_thanh') {
                                        $progress = 100;
                                        $progressClass = 'bg-success';
                                        $statusText = 'Đã hoàn thành';
                                    }
                                }
                                $progress = round($progress);
                            @endphp

                            <div class="progress mb-3" style="height: 20px; border-radius: 10px;">
                                <div class="progress-bar {{ $progressClass }} progress-bar-animated" role="progressbar"
                                    style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <span class="font-weight-bold">{{ $progress }}%</span>
                                </div>
                            </div>

                            <div class="text-muted">
                                <small class="d-block">
                                    @if ($startDate && $endDate)
                                        <strong>{{ $statusText }}</strong><br>
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                                        @if ($remainingDays > 0 && $currentDate->lessThan($startDate))
                                            <br><span class="text-info">Còn {{ $remainingDays }} ngày để bắt đầu</span>
                                        @elseif(
                                            $remainingDays > 0 &&
                                                $currentDate->lessThanOrEqualTo($endDate) &&
                                                $currentDate->greaterThanOrEqualTo($startDate) &&
                                                $plan->status !== 'hoan_thanh')
                                            <br><span class="text-warning">Còn {{ $remainingDays }} ngày</span>
                                        @endif
                                    @else
                                        <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                                        Chưa xác định thời gian
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Doctor Info Card --}}
                    <div
                        class="card shadow border-0 rounded-lg mb-4 animate__animated animate__fadeInLeft animate__delay-0.2s">
                        <div class="card-header bg-gradient-info text-white border-0 d-flex align-items-center">
                            <i class="fas fa-user-md mr-2"></i>
                            <h6 class="card-title mb-0 font-weight-bold">Thông tin Bác sĩ</h6>
                        </div>
                        <div class="card-body p-3">
                            @if ($plan->doctor)
                                <div class="mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-user text-info mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Tên đầy đủ:</strong>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('admin.users.show', $plan->doctor->id) }}"
                                            class="text-primary font-weight-semibold text-decoration-none">
                                            {{ $plan->doctor->full_name ?? 'N/A' }}
                                        </a>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-stethoscope text-info mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Chuyên khoa:</strong>
                                    </div>
                                    <div class="ml-4">{{ $plan->doctor->doctor->specialization ?? 'N/A' }}</div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-phone text-info mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Số điện thoại:</strong>
                                    </div>
                                    <div class="ml-4">{{ $plan->doctor->phone ?? 'N/A' }}</div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-envelope text-info mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Email:</strong>
                                    </div>
                                    <div class="ml-4">{{ $plan->doctor->email ?? 'N/A' }}</div>
                                </div>

                                <div class="mb-0">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-map-marker-alt text-info mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Địa chỉ:</strong>
                                    </div>
                                    <div class="ml-4">{{ $plan->doctor->address ?? 'N/A' }}</div>
                                </div>
                            @else
                                <div class="alert alert-warning text-center py-3 mb-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <span class="small">Không có thông tin bác sĩ</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Patient Info Card --}}
                    <div
                        class="card shadow border-0 rounded-lg mb-4 animate__animated animate__fadeInLeft animate__delay-0.4s flex-fill">
                        <div class="card-header bg-gradient-success text-white border-0 d-flex align-items-center">
                            <i class="fas fa-user-injured mr-2"></i>
                            <h6 class="card-title mb-0 font-weight-bold">Thông tin Bệnh nhân</h6>
                        </div>
                        <div class="card-body p-3">
                            @if ($plan->patient)
                                <div class="mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-user text-success mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Tên đầy đủ:</strong>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('admin.users.show', $plan->patient->id) }}"
                                            class="text-success font-weight-semibold text-decoration-none">
                                            {{ $plan->patient->full_name ?? 'N/A' }}
                                        </a>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-calendar-alt text-success mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Ngày sinh:</strong>
                                    </div>
                                    <div class="ml-4">
                                        {{ $plan->patient->date_of_birth ? $plan->patient->date_of_birth->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-{{ $plan->patient->gender == 'Nam' ? 'male' : ($plan->patient->gender == 'Nữ' ? 'female' : 'genderless') }} text-success mr-2"
                                            style="width: 16px;"></i>
                                        <strong class="text-muted small">Giới tính:</strong>
                                    </div>
                                    <div class="ml-4">{{ $plan->patient->gender ?? 'N/A' }}</div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-phone text-success mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Số điện thoại:</strong>
                                    </div>
                                    <div class="ml-4">{{ $plan->patient->phone ?? 'N/A' }}</div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-envelope text-success mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Email:</strong>
                                    </div>
                                    <div class="ml-4">{{ $plan->patient->email ?? 'N/A' }}</div>
                                </div>

                                <div class="mb-0">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-map-marker-alt text-success mr-2" style="width: 16px;"></i>
                                        <strong class="text-muted small">Địa chỉ:</strong>
                                    </div>
                                    <div class="ml-4">{{ $plan->patient->address ?? 'N/A' }}</div>
                                </div>
                            @else
                                <div class="alert alert-warning text-center py-3 mb-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <span class="small">Không có thông tin bệnh nhân</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Plan Details & Treatment Steps --}}
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="h-100 d-flex flex-column">
                    {{-- Plan Overview Card --}}
                    <div class="card shadow border-0 rounded-lg mb-4 animate__animated animate__fadeInRight">
                        <div class="card-header bg-gradient-dark text-white border-0 d-flex align-items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <h6 class="card-title mb-0 font-weight-bold">Tổng quan Kế hoạch</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="border-left border-primary pl-3">
                                        <h6 class="text-muted mb-1 small">TIÊU ĐỀ</h6>
                                        <p class="mb-0 font-weight-bold text-dark">
                                            {{ $plan->plan_title ?? 'Chưa có tiêu đề' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="border-left border-success pl-3">
                                        <h6 class="text-muted mb-1 small">TỔNG CHI PHÍ (ƯỚC TÍNH)</h6>
                                        <p class="mb-0 font-weight-bold text-success">
                                            {{ number_format($plan->total_estimated_cost, 0, ',', '.') }} VND</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="border-left border-info pl-3">
                                        <h6 class="text-muted mb-1 small">CHẨN ĐOÁN</h6>
                                        <p class="mb-0">{{ $plan->diagnosis ?? 'Chưa có thông tin' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="border-left border-warning pl-3">
                                        <h6 class="text-muted mb-1 small">MỤC TIÊU</h6>
                                        <p class="mb-0">{{ $plan->goal ?? 'Chưa có thông tin' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="border-left border-secondary pl-3">
                                        <h6 class="text-muted mb-1 small">GHI CHÚ</h6>
                                        <p class="mb-0">{{ $plan->notes ?? 'Không có ghi chú' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="border-left border-dark pl-3">
                                        <h6 class="text-muted mb-1 small">NGÀY TẠO</h6>
                                        <p class="mb-0">
                                            {{ $plan->created_at ? $plan->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border-left border-dark pl-3">
                                        <h6 class="text-muted mb-1 small">CẬP NHẬT LẦN CUỐI</h6>
                                        <p class="mb-0">
                                            {{ $plan->updated_at ? $plan->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Treatment Steps Card --}}
                    @if ($plan->items && $plan->items->count() > 0)
                        <div
                            class="card shadow border-0 rounded-lg mb-4 animate__animated animate__fadeInRight animate__delay-0.2s flex-fill">
                            <div
                                class="card-header bg-gradient-secondary text-white border-0 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tasks mr-2"></i>
                                    <h6 class="card-title mb-0 font-weight-bold">Các bước trong kế hoạch</h6>
                                </div>
                                <span class="badge badge-primary">{{ $plan->items->count() }} bước</span>
                            </div>

                            {{-- Scroll Mode --}}
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 560px; overflow-y: auto;">
                                    <table class="table table-hover mb-0 table-striped table-sm small">
                                        <thead class="bg-light sticky-top">
                                            <tr>
                                                <th class="border-0 text-center" style="width: 60px;">STT</th>
                                                <th class="border-0" style="width: 20%;">Tiêu đề</th>
                                                <th class="border-0" style="width: 20%;">Mô tả</th>
                                                <th class="border-0 text-center" style="width: 15%;">Tần suất</th>
                                                <th class="border-0" style="width: 30%;">Thời gian</th>
                                                <th class="border-0 text-center" style="width: 15%;">Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($plan->items as $index => $item)
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-dark">{{ $index + 1 }}</span>
                                                    </td>

                                                    {{-- Tiêu đề --}}
                                                    <td class="align-middle text-primary font-weight-bold">
                                                        {{ $item->title ?? 'Không có' }}
                                                    </td>

                                                    {{-- Mô tả --}}
                                                    <td class="align-middle">
                                                        <div class="text-wrap" style="max-width: 250px;">
                                                            {{ Str::limit($item->description ?? 'Không có', limit: 50) }}
                                                        </div>
                                                    </td>

                                                    {{-- Tần suất --}}
                                                    <td class="align-middle text-center">
                                                        <span class="badge badge-info text-uppercase">
                                                            {{ $item->frequency ?? '-' }}
                                                        </span>
                                                    </td>

                                                    {{-- Thời gian --}}
                                                    <td class="align-middle">
                                                        <div class="small text-muted">
                                                            @if ($item->expected_start_date)
                                                                <div class="mb-1">
                                                                    <i class="far fa-play-circle text-primary me-1"></i>
                                                                    <strong>Bắt đầu:</strong>
                                                                    <span class="text-dark">
                                                                        {{ $item->expected_start_date->format('d/m/Y') }}
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            @if ($item->expected_end_date)
                                                                <div class="mb-1">
                                                                    <i class="far fa-flag text-warning me-1"></i>
                                                                    <strong>Kết thúc dự kiến:</strong>
                                                                    <span class="text-dark">
                                                                        {{ $item->expected_end_date->format('d/m/Y') }}
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            @if ($item->actual_end_date)
                                                                <div class="mb-0">
                                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                                    <strong>Hoàn tất:</strong>
                                                                    <span class="text-dark">
                                                                        {{ $item->actual_end_date->format('d/m/Y') }}
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            @if (!$item->expected_start_date && !$item->expected_end_date && !$item->actual_end_date)
                                                                <span class="text-muted fst-italic">Chưa có thông
                                                                    tin</span>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    {{-- Trạng thái --}}
                                                    <td class="text-center align-middle">
                                                        @php
                                                            $itemStatusText = 'Không xác định';
                                                            $itemStatusClass = '';
                                                            $itemStatusIcon = 'question';

                                                            switch ($item->status) {
                                                                case 'chua_thuc_hien':
                                                                    $itemStatusText = 'Chưa thực hiện';
                                                                    $itemStatusClass = 'secondary';
                                                                    $itemStatusIcon = 'clock';
                                                                    break;
                                                                case 'dang_thuc_hien':
                                                                    $itemStatusText = 'Đang thực hiện';
                                                                    $itemStatusClass = 'warning';
                                                                    $itemStatusIcon = 'spinner';
                                                                    break;
                                                                case 'hoan_thanh':
                                                                    $itemStatusText = 'Hoàn thành';
                                                                    $itemStatusClass = 'success';
                                                                    $itemStatusIcon = 'check';
                                                                    break;
                                                                case 'tam_dung':
                                                                    $itemStatusText = 'Tạm dừng';
                                                                    $itemStatusClass = 'danger';
                                                                    $itemStatusIcon = 'pause';
                                                                    break;
                                                            }
                                                        @endphp

                                                        <span class="badge badge-{{ $itemStatusClass }}">
                                                            <i class="fas fa-{{ $itemStatusIcon }}"></i>
                                                            {{ $itemStatusText }}
                                                        </span>
                                                    </td>

                                                    {{-- Ghi chú --}}
                                                    {{-- <td class="align-middle">                                                      
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-wrap m-3" style="max-width: 200px;">
                                    <a href="#" class="btn btn-outline-info" data-toggle="modal"
                                        data-target="#modal-plan-{{ $plan->id }}">
                                        Xem chi tiết
                                    </a>

                                </div>
                                @if ($plan->items->count() > 5)
                                    <div class="text-center py-2 bg-light border-top">
                                        <small class="text-muted">
                                            <i class="fas fa-arrows-alt-v mr-1"></i>
                                            Cuộn để xem thêm ({{ $plan->items->count() }} bước)
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div
                            class="card shadow border-0 rounded-lg animate__animated animate__fadeInRight animate__delay-0.2s flex-fill">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-exclamation-circle fa-4x mb-3 text-info opacity-50"></i>
                                <h5 class="text-info font-weight-bold mb-2">Kế hoạch này chưa có bước điều trị chi tiết
                                </h5>
                                {{-- <p class="text-muted mb-3">Vui lòng chỉnh sửa kế hoạch để thêm các bước điều trị cụ thể.
                                </p>
                                <a href="{{ route('admin.treatment-plans.edit', $plan->id) }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Thêm bước điều trị
                                </a> --}}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal: Chi tiết kế hoạch -->
    <div class="modal fade" id="modal-plan-{{ $plan->id }}" tabindex="-1" role="dialog"
        aria-labelledby="modalLabel-{{ $plan->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold" id="modalLabel-{{ $plan->id }}">
                        Chi tiết các bước điều trị kế hoạch: {{ $plan->plan_title }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body small">
                    {{-- Thông tin kế hoạch --}}
                    {{-- <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tiêu đề:</strong> {{ $plan->title }}</p>
                        <p><strong>Mô tả:</strong> {{ $plan->description ?? 'Không có' }}</p>
                        <p><strong>Ngày bắt đầu:</strong> {{ optional($plan->start_date)->format('d/m/Y') }}</p>
                        <p><strong>Ngày kết thúc:</strong> {{ optional($plan->end_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Bác sĩ:</strong> {{ $plan->doctor->full_name ?? 'Không có' }}</p>
                        <p><strong>Bệnh nhân:</strong> {{ $plan->patient->full_name ?? 'Không có' }}</p>
                        <p><strong>Trạng thái:</strong> {{ ucfirst(str_replace('_', ' ', $plan->status)) }}</p>
                    </div>
                </div> --}}

                    <hr>

                    {{-- Danh sách các bước --}}
                    {{-- <h6 class="font-weight-bold text-primary">Danh sách các bước điều trị:</h6> --}}

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tiêu đề</th>
                                    <th>Mô tả</th>
                                    <th>Ngày bắt đầu dự kiến</th>
                                    <th>Ngày kết thúc dự kiến</th>
                                    <th>Ngày hoàn tất thực tế</th>
                                    <th>Tần suất</th>
                                    <th>Trạng thái</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plan->items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->title ?? 'Không có' }}</td>
                                        <td>{{ $item->description ?? 'Không có' }}</td>
                                        <td>{{ optional($item->expected_start_date)->format('d/m/Y H:i') ?? 'Chưa có' }}
                                        </td>
                                        <td>{{ optional($item->expected_end_date)->format('d/m/Y H:i') ?? 'Chưa có' }}</td>
                                        <td>{{ optional($item->actual_end_date)->format('d/m/Y H:i') ?? 'Chưa hoàn tất' }}
                                        </td>
                                        <td>{{ $item->frequency ?? '-' }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                                        <td>{{ $item->notes ?? 'Không có' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>


    @if (session('success'))
        <script>
            if (typeof toastr !== 'undefined') {
                toastr.success('{{ session('success') }}');
            } else {
                console.log('Success: {{ session('success') }}');
            }
        </script>
    @endif
    <!-- Bootstrap 4 -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> --}}

    {{-- JavaScript for Table Modes --}}
    <script>
        // Treatment plan items data
        const treatmentItems = @json($plan->items ?? []);
        let currentPage = 1;
        let itemsPerPage = 5;

        // Toggle between scroll and pagination modes
        function toggleScrollMode() {
            document.getElementById('scrollMode').style.display = 'block';
            document.getElementById('paginationMode').style.display = 'none';
        }

        function togglePaginationMode() {
            document.getElementById('scrollMode').style.display = 'none';
            document.getElementById('paginationMode').style.display = 'block';
            initializePagination();
        }

        // Initialize pagination
        function initializePagination() {
            itemsPerPage = parseInt(document.getElementById('itemsPerPage').value);
            currentPage = 1;
            renderPaginatedTable();
            updatePaginationControls();
        }

        // Render paginated table
        function renderPaginatedTable() {
            const tbody = document.getElementById('paginatedTableBody');
            tbody.innerHTML = '';

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, treatmentItems.length);

            if (treatmentItems.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Không có bước điều trị nào</p>
                        </td>
                    </tr>
                `;
                return;
            }

            for (let i = startIndex; i < endIndex; i++) {
                const item = treatmentItems[i];
                const row = createTableRow(item, i + 1);
                tbody.appendChild(row);
            }

            // Update showing info
            document.getElementById('showingFrom').textContent = startIndex + 1;
            document.getElementById('showingTo').textContent = endIndex;
            document.getElementById('totalItems').textContent = treatmentItems.length;
        }

        // Create table row
        function createTableRow(item, index) {
            const tr = document.createElement('tr');

            // Status mapping
            const statusMap = {
                'hoan_thanh': {
                    text: 'Hoàn thành',
                    class: 'success',
                    icon: 'check'
                },
                'dang_thuc_hien': {
                    text: 'Đang thực hiện',
                    class: 'warning',
                    icon: 'hourglass-half'
                },
                'chua_thuc_hien': {
                    text: 'Chưa thực hiện',
                    class: 'secondary',
                    icon: 'hourglass-start'
                },
                'default': {
                    text: 'Không xác định',
                    class: 'light',
                    icon: 'question'
                }
            };

            const status = statusMap[item.status] || statusMap['default'];

            // Format dates
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                return new Date(dateString).toLocaleDateString('vi-VN');
            };

            tr.innerHTML = `
                <td class="text-center align-middle">
                    <span class="badge badge-light">${index}</span>
                </td>
                <td class="align-middle">
                    <div class="text-wrap" style="max-width: 250px;">
                        ${item.description || 'N/A'}
                    </div>
                </td>
                <td class="align-middle">
                    <small>
                        <div class="mb-1">
                            <strong class="text-info">Bắt đầu:</strong><br>
                            ${formatDate(item.expected_start_date)}
                        </div>
                        <div class="mb-1">
                            <strong class="text-warning">Kết thúc dự kiến:</strong><br>
                            ${formatDate(item.expected_end_date)}
                        </div>
                        ${item.actual_end_date ? `
                                                                                <div>
                                                                                    <strong class="text-success">Hoàn tất:</strong><br>
                                                                                    ${formatDate(item.actual_end_date)}
                                                                                </div>
                                                                            ` : ''}
                    </small>
                </td>
                <td class="text-center align-middle">
                    <span class="badge badge-${status.class} px-2 py-1">
                        <i class="fas fa-${status.icon} mr-1"></i>
                        ${status.text}
                    </span>
                </td>
                <td class="align-middle">
                    <div class="text-wrap" style="max-width: 200px;">
                        ${item.notes || 'Không có'}
                    </div>
                </td>
            `;

            return tr;
        }

        // Update pagination controls
        function updatePaginationControls() {
            const totalPages = Math.ceil(treatmentItems.length / itemsPerPage);

            document.getElementById('pageInfo').textContent = `${currentPage} / ${totalPages}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages || totalPages === 0;
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Items per page change
            document.getElementById('itemsPerPage').addEventListener('change', function() {
                initializePagination();
            });

            // Previous page
            document.getElementById('prevPage').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    renderPaginatedTable();
                    updatePaginationControls();
                }
            });

            // Next page
            document.getElementById('nextPage').addEventListener('click', function() {
                const totalPages = Math.ceil(treatmentItems.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderPaginatedTable();
                    updatePaginationControls();
                }
            });
        });

        // Export function
        function exportSteps() {
            if (treatmentItems.length === 0) {
                alert('Không có dữ liệu để xuất');
                return;
            }

            let csvContent = "STT,Mô tả,Ngày bắt đầu,Ngày kết thúc dự kiến,Ngày hoàn tất,Trạng thái,Ghi chú\n";

            treatmentItems.forEach((item, index) => {
                const statusMap = {
                    'hoan_thanh': 'Hoàn thành',
                    'dang_thuc_hien': 'Đang thực hiện',
                    'chua_thuc_hien': 'Chưa thực hiện'
                };

                const row = [
                    index + 1,
                    `"${(item.description || 'N/A').replace(/"/g, '""')}"`,
                    item.expected_start_date || 'N/A',
                    item.expected_end_date || 'N/A',
                    item.actual_end_date || 'N/A',
                    statusMap[item.status] || 'Không xác định',
                    `"${(item.notes || 'Không có').replace(/"/g, '""')}"`
                ].join(',');

                csvContent += row + "\n";
            });

            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `ke-hoach-dieu-tri-${new Date().getTime()}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Smooth scroll enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.querySelector('#scrollMode .table-responsive');
            if (scrollContainer) {
                scrollContainer.style.scrollBehavior = 'smooth';

                // Add scroll shadow effect
                scrollContainer.addEventListener('scroll', function() {
                    if (this.scrollTop > 0) {
                        this.classList.add('shadow-inner');
                    } else {
                        this.classList.remove('shadow-inner');
                    }
                });
            }
        });
    </script>

    {{-- Custom CSS for enhanced scrolling --}}
    <style>
        /* Custom scrollbar for webkit browsers */
        .table-responsive::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Sticky header enhancement */
        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Hover effects */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, .075);
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        /* Button hover effects */
        .btn-hover-scale-light:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }

        /* Dropdown animation */
        .dropdown-menu {
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive table enhancements */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }

            .badge {
                font-size: 0.7rem;
            }
        }
    </style>
@endsection
@push('styles')
    {{-- Font Awesome for Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40pgeEFj9bu6EGkIKpP+M2+t+QJtX/Q7rY4N/2m0Q/h9N5o5K5c3g3f2q6f6t6e2d1c1a1f1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z+A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Animate.css for entrance animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        :root {
            --primary-color: #007bff;
            /* Blue */
            --primary-dark: #0056b3;
            /* Darker blue */
            --secondary-color: #6c757d;
            /* Gray */
            --success-color: #28a745;
            /* Green */
            --info-color: #17a2b8;
            /* Cyan */
            --warning-color: #ffc107;
            /* Yellow */
            --danger-color: #dc3545;
            /* Red */
            --light-gray: #f8f9fa;
            /* Light background */
            --border-color: #e9ecef;
            /* Light border */
            --text-muted: #6c757d;
            --dark-text: #343a40;
            /* Dark text */
            --card-bg: #ffffff;
            /* White card background */
            --shadow-light: rgba(0, 0, 0, 0.05);
            --shadow-medium: rgba(0, 0, 0, 0.15);
            /* Slightly stronger shadow */
            --shadow-strong: rgba(0, 0, 0, 0.25);
        }

        body {
            background-color: var(--light-gray);
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--dark-text);
        }

        /* Custom Gradients and Backgrounds */
        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, #6f42c1 100%);
            /* Blue to Purple */
        }

        .bg-light-alpha {
            background-color: rgba(248, 249, 250, 0.8);
        }

        .bg-table-header {
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        /* Card Enhancements */
        .card {
            border-radius: 1rem;
            /* Slightly less rounded than before for cleaner look */
            box-shadow: 0 0.5rem 1.5rem var(--shadow-medium) !important;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.8rem 2rem var(--shadow-strong) !important;
        }

        .card-header {
            border-bottom: none;
            padding: 1.5rem 2rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            display: flex;
            align-items: center;
        }

        .card-title {
            font-size: 1.5rem;
            /* Adjusted for better hierarchy */
            letter-spacing: -0.01em;
            font-weight: 700;
        }

        .card-body {
            padding: 2rem;
            /* Adjusted padding */
        }

        .card-footer {
            padding: 1.25rem 2rem;
            border-top: 1px solid var(--border-color);
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }

        /* Info Box Refinement (used for Status & Progress) */
        .info-box {
            background: var(--card-bg);
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1.5rem var(--shadow-light);
            min-height: 100px;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.75rem 2rem var(--shadow-medium);
        }

        .info-box-icon {
            width: 90px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5rem;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
            border-right: 1px solid rgba(0, 0, 0, .05);
        }

        .info-box-content {
            padding: 0 1.5rem;
        }

        .info-box-number {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Detail Item Styling (for Plan details, Doctor, Patient) */
        .detail-item {
            padding: 0.75rem 0;
            /* Consistent padding */
            border-bottom: 1px dashed var(--border-color);
            /* Dashed separator */
        }

        .detail-item:last-child {
            border-bottom: none;
            /* No border for the last item */
        }

        .detail-item .col-sm-3,
        .detail-item .col-md-4 {
            /* Adjusted for flexibility */
            font-weight: 600;
            color: var(--secondary-color);
            /* Muted text for labels */
        }

        .detail-item .col-sm-9,
        .detail-item .col-md-8 {
            /* Adjusted for flexibility */
            color: var(--dark-text);
            word-wrap: break-word;
            /* Ensure long text wraps */
        }

        .detail-item .fas {
            margin-right: 0.5rem;
            color: var(--primary-color);
            /* Default icon color */
        }

        .card-body p strong .fas {
            color: inherit;
            /* Inherit color from parent for doctor/patient details */
        }

        .hover-underline:hover {
            text-decoration: underline;
        }


        /* Badge Styling */
        .badge-lg {
            font-size: 1.05em;
            padding: 0.6em 1.2em;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Progress Bar Styling */
        .progress {
            background-color: var(--border-color);
            height: 15px;
            /* Increased height */
            border-radius: 8px;
            /* More rounded */
            overflow: hidden;
        }

        .progress-bar {
            transition: width 0.6s ease-in-out;
            /* Slower transition */
            font-weight: bold;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            /* Ensure rounded corners on bar */
        }

        /* Table Styling */
        .table-responsive {
            border-radius: 0 0 1rem 1rem;
            /* Apply border-radius to responsive table container, matching card */
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .table th,
        .table td {
            padding: 1rem 1.5rem;
            /* Increased padding */
            vertical-align: middle;
            border-top: 1px solid var(--border-color);
        }

        .table thead th {
            border-bottom: none;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 0.9em;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.03);
            /* Lighter stripe */
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.07);
            /* Clearer hover effect */
        }


        /* Button Animations */
        .btn-hover-scale {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-hover-scale:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 0.25rem 0.75rem var(--shadow-light);
        }






        /* Alert Styling */
        .alert-light-info {
            background-color: rgba(23, 162, 184, 0.08);
            border: 1px solid rgba(23, 162, 184, 0.2);
            color: var(--info-color);
            border-radius: 1rem;
        }

        .alert-light-secondary {
            background-color: rgba(108, 117, 125, 0.08);
            border: 1px solid rgba(108, 117, 125, 0.2);
            color: var(--secondary-color);
            border-radius: 1rem;
        }

        .alert-heading {
            color: inherit;
            /* Inherit from parent alert */
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {

            /* For large devices and down */
            .col-lg-5,
            .col-lg-7 {
                max-width: 100%;
                flex: 0 0 100%;
            }
        }

        @media (max-width: 767.98px) {

            /* For medium devices and down */
            .card-body {
                padding: 1.5rem;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 1rem;
            }

            .card-tools {
                margin-top: 1rem;
                width: 100%;
                justify-content: flex-start !important;
                flex-wrap: wrap;
                /* Allow buttons to wrap */
            }

            .card-tools .btn {
                margin-left: 0 !important;
                margin-right: 0.5rem;
                margin-bottom: 0.5rem;
            }

            .detail-item .col-sm-3,
            .detail-item .col-sm-9 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
@endpush

@push('scripts')
    {{-- Ensure jQuery and Popper.js are loaded for Bootstrap Tooltips and other functionality --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    {{-- Toastr for notifications if you use it --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            // Initialize Tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
