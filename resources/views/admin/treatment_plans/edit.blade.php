@extends('admin.dashboard')

@section('title', 'Chỉnh sửa Kế hoạch Điều trị')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                {{-- Header section (Copied from Index for consistency) --}}
                <div class="header-section mb-4 animate__animated animate__fadeInDown">
                    <div class="header-content">
                        <div class="icon-circle bg-primary-light">
                            <i class="fas fa-edit text-primary"></i> {{-- Changed icon to fas fa-edit --}}
                        </div>
                        <div class="text-content">
                            <h2 class="main-title text-primary">Chỉnh sửa Kế hoạch Điều trị</h2>
                            <p class="sub-title text-muted">Cập nhật thông tin chi tiết của kế hoạch điều trị</p>
                        </div>
                    </div>
                    {{-- Breadcrumb --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0">
                            <li class="">
                                <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                    Trang chủ >
                                </a>
                            </li>
                            <li class="">
                                <a class="text-decoration-none" href="{{ route('admin.treatment-plans.index') }}">Kế hoạch
                                    Điều trị >
                                </a>
                            </li>
                            <li class="font-weight-semibold" aria-current="page">
                                <a href="" class="text-decoration-none">Chỉnh sửa</a>
                            </li>
                        </ol>
                    </nav>

                </div>

                <div class="card shadow-lg border-0 rounded-xl animate__animated animate__fadeInUp animate__delay-0.2s">
                    {{-- Card Header: Blue bar like List Header in Index --}}
                    <div class="list-header-bar d-flex justify-content-between align-items-center p-3 rounded-top-xl">
                        <h4 class="list-title mb-0 text-white">Chỉnh sửa Kế hoạch:
                            {{ $plan->plan_title ?? 'Không có tiêu đề' }}</h4>
                        <a href="{{ route('admin.treatment-plans.show', $plan->id) }}"
                            class="btn btn-add-user-style btn-sm"> {{-- Reusing btn-add-user-style as a back button --}}
                            <i class="fas fa-arrow-left mr-2"></i> Quay lại chi tiết
                        </a>
                    </div>

                    <form action="{{ route('admin.treatment-plans.update', $plan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger-custom animate__animated animate__shakeX" role="alert">
                                    <h5 class="alert-heading-custom"><i class="icon fas fa-exclamation-triangle mr-2"></i>
                                        Lỗi nhập liệu!</h5>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close custom-close-alert" data-dismiss="alert"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 animate__animated animate__fadeInLeft">
                                    <div class="form-group mb-4">
                                        <label for="plan_title" class="form-label-custom"><i
                                                class="fas fa-clipboard-check mr-2"></i>TIÊU ĐỀ KẾ HOẠCH <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control form-control-filter @error('plan_title') is-invalid @enderror"
                                            id="plan_title" name="plan_title"
                                            value="{{ old('plan_title', $plan->plan_title) }}"
                                            placeholder="Nhập tiêu đề kế hoạch điều trị..." required>
                                        @error('plan_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 animate__animated animate__fadeInRight">
                                    <div class="form-group mb-4">
                                        <label for="status" class="form-label-custom"><i
                                                class="fas fa-chart-bar mr-2"></i>TRẠNG THÁI <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control form-control-filter custom-select-arrow @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                            <option value="">-- Chọn trạng thái --</option>
                                            <option value="chua_tien_hanh"
                                                {{ old('status', $plan->status) == 'chua_tien_hanh' ? 'selected' : '' }}>
                                                Chưa tiến hành
                                            </option>
                                            <option value="dang_tien_hanh"
                                                {{ old('status', $plan->status) == 'dang_tien_hanh' ? 'selected' : '' }}>
                                                Đang tiến hành
                                            </option>
                                            <option value="hoan_thanh"
                                                {{ old('status', $plan->status) == 'hoan_thanh' ? 'selected' : '' }}>
                                                Hoàn thành
                                            </option>
                                            <option value="tam_dung"
                                                {{ old('status', $plan->status) == 'tam_dung' ? 'selected' : '' }}>
                                                Tạm dừng
                                            </option>
                                            <option value="huy_bo"
                                                {{ old('status', $plan->status) == 'huy_bo' ? 'selected' : '' }}>
                                                Hủy bỏ
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 animate__animated animate__fadeInLeft animate__delay-0.1s">
                                    <div class="form-group mb-4">
                                        <label for="start_date" class="form-label-custom"><i
                                                class="fas fa-calendar-alt mr-2"></i>NGÀY BẮT ĐẦU</label>
                                        <input type="date"
                                            class="form-control form-control-filter @error('start_date') is-invalid @enderror"
                                            id="start_date" name="start_date"
                                            value="{{ old('start_date', $plan->start_date ? (is_string($plan->start_date) ? $plan->start_date : $plan->start_date->format('Y-m-d')) : '') }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 animate__animated animate__fadeInRight animate__delay-0.1s">
                                    <div class="form-group mb-4">
                                        <label for="end_date" class="form-label-custom"><i
                                                class="fas fa-calendar-check mr-2"></i>NGÀY KẾT THÚC</label>
                                        <input type="date"
                                            class="form-control form-control-filter @error('end_date') is-invalid @enderror"
                                            id="end_date" name="end_date"
                                            value="{{ old('end_date', $plan->end_date ? (is_string($plan->end_date) ? $plan->end_date : $plan->end_date->format('Y-m-d')) : '') }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4 animate__animated animate__fadeInUp animate__delay-0.2s">
                                <label for="diagnosis" class="form-label-custom"><i class="fas fa-stethoscope mr-2"></i>CHẨN
                                    ĐOÁN</label>
                                <textarea class="form-control form-control-filter rounded-lg @error('diagnosis') is-invalid @enderror" id="diagnosis"
                                    name="diagnosis" rows="4" placeholder="Nhập chẩn đoán chi tiết...">{{ old('diagnosis', $plan->diagnosis) }}</textarea>
                                @error('diagnosis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4 animate__animated animate__fadeInUp animate__delay-0.3s">
                                <label for="goal" class="form-label-custom"><i class="fas fa-bullseye mr-2"></i>MỤC
                                    TIÊU ĐIỀU TRỊ</label>
                                <textarea class="form-control form-control-filter rounded-lg @error('goal') is-invalid @enderror" id="goal"
                                    name="goal" rows="4" placeholder="Nhập mục tiêu điều trị rõ ràng...">{{ old('goal', $plan->goal) }}</textarea>
                                @error('goal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-5 animate__animated animate__fadeInUp animate__delay-0.4s">
                                <label for="notes" class="form-label-custom"><i
                                        class="fas fa-sticky-note mr-2"></i>GHI CHÚ</label>
                                <textarea class="form-control form-control-filter rounded-lg @error('notes') is-invalid @enderror" id="notes"
                                    name="notes" rows="5" placeholder="Nhập các ghi chú hoặc thông tin bổ sung...">{{ old('notes', $plan->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($plan->items && $plan->items->count() > 0)
                                <div class="form-group animate__animated animate__fadeInUp animate__delay-0.5s">
                                    <label class="form-label-custom">
                                        <i class="fas fa-list-ul mr-2"></i>DANH SÁCH BƯỚC ĐIỀU TRỊ
                                    </label>

                                    {{-- 🔽 Scrollable area --}}
                                    <div class="table-responsive rounded-lg border shadow-sm"
                                        style="max-height: 420px; overflow-y: auto;">
                                        <table class="table table-hover align-items-center mb-0 custom-table">
                                            <thead class="bg-light text-xs text-uppercase sticky-top">
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Tiêu đề</th>
                                                    <th>Mô tả</th>
                                                    <th class="text-center">Tần suất</th>
                                                    <th class="text-center">Bắt đầu</th>
                                                    <th class="text-center">Kết thúc dự kiến</th>
                                                    <th class="text-center">Hoàn tất</th>
                                                    <th class="text-center">Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($plan->items as $index => $item)
                                                    <tr class="animate__animated animate__fadeInUp animate__faster"
                                                        style="animation-delay: {{ $index * 0.05 }}s;">
                                                        <td class="text-center text-secondary font-weight-bold text-xs">
                                                            {{ $index + 1 }}</td>

                                                        {{-- Tiêu đề --}}
                                                        <td class="text-sm font-weight-bold">
                                                            {{ $item->title ?? '-' }}
                                                        </td>

                                                        {{-- Mô tả --}}
                                                        <td class="text-xs text-muted">
                                                            {{ $item->description ?? '-' }}
                                                        </td>

                                                        {{-- Tần suất --}}
                                                        <td class="text-center text-xs">
                                                            {{ $item->frequency ?? '-' }}
                                                        </td>

                                                        {{-- Ngày bắt đầu --}}
                                                        <td class="text-center text-xs">
                                                            {{ $item->expected_start_date ? $item->expected_start_date->format('d/m/Y') : '-' }}
                                                            <input type="hidden"
                                                                name="items[{{ $item->id }}][expected_start_date]"
                                                                value="{{ $item->expected_start_date }}">
                                                        </td>

                                                        {{-- Ngày kết thúc dự kiến --}}
                                                        <td class="text-center text-xs">
                                                            {{ $item->expected_end_date ? $item->expected_end_date->format('d/m/Y') : '-' }}
                                                            <input type="hidden"
                                                                name="items[{{ $item->id }}][expected_end_date]"
                                                                value="{{ $item->expected_end_date }}">
                                                        </td>

                                                        {{-- Ngày hoàn tất --}}
                                                        <td class="text-center text-xs">
                                                            @if ($item->actual_end_date)
                                                                <span
                                                                    class="badge bg-success text-white">{{ $item->actual_end_date->format('d/m/Y') }}</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>

                                                        {{-- Trạng thái --}}
                                                        <td class="text-center">
                                                            <input type="hidden" name="items[{{ $item->id }}][id]"
                                                                value="{{ $item->id }}">
                                                            <select name="items[{{ $item->id }}][status]"
                                                                class="form-select form-select-sm w-auto mx-auto"
                                                                {{ $item->status === 'hoan_thanh' ? 'disabled' : '' }}>
                                                                <option value="chua_thuc_hien"
                                                                    {{ $item->status == 'chua_thuc_hien' ? 'selected' : '' }}>
                                                                    Chưa thực hiện</option>
                                                                <option value="dang_thuc_hien"
                                                                    {{ $item->status == 'dang_thuc_hien' ? 'selected' : '' }}>
                                                                    Đang thực hiện</option>
                                                                <option value="hoan_thanh"
                                                                    {{ $item->status == 'hoan_thanh' ? 'selected' : '' }}>
                                                                    Hoàn thành</option>
                                                                <option value="tam_dung"
                                                                    {{ $item->status == 'tam_dung' ? 'selected' : '' }}>Tạm
                                                                    dừng</option>
                                                            </select>
                                                            @if ($item->status === 'hoan_thanh')
                                                                <input type="hidden"
                                                                    name="items[{{ $item->id }}][status]"
                                                                    value="hoan_thanh">
                                                            @endif

                                                            @error("items.{$item->id}.status")
                                                                <div class="text-danger text-xs mt-1">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <small class="text-muted mt-3 d-block text-sm">
                                        <i class="fas fa-info-circle mr-1 text-primary"></i>
                                        Bạn chỉ có thể thay đổi <strong>trạng thái</strong>. Các trường khác chỉ hiển thị.
                                    </small>
                                </div>
                            @endif




                        </div>

                        {{-- Card Footer: Action Buttons --}}
                        <div
                            class="card-footer d-flex justify-content-end p-3 border-top bg-light-alpha rounded-bottom-xl">
                            <button type="submit"
                                class="btn btn-primary-filter btn-icon-text me-2 animate__animated animate__pulse animate__infinite"
                                style="--animate-duration: 2s;">
                                <i class="fas fa-save mr-2"></i> Cập nhật Kế hoạch
                            </button>
                            <a href="{{ route('admin.treatment-plans.show', $plan->id) }}"
                                class="btn btn-outline-secondary-filter btn-icon-text">
                                <i class="fas fa-times mr-2"></i> Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Toastr messages --}}
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}');
        </script>
    @endif
@endsection

@push('styles')
    {{-- Animate.css for entrance animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* Re-use variables from Index for consistency */
        :root {
            --dark-blue: #2c3e50;
            --light-blue: #3498db;
            --gradient-1-start: #3498db;
            --gradient-1-end: #6dd5ed;
            --gradient-2-start: #2ecc71;
            --gradient-2-end: #7ed321;
            --gradient-3-start: #f39c12;
            --gradient-3-end: #f8e71c;
            --gradient-4-start: #e74c3c;
            --gradient-4-end: #f55c5c;

            --bg-color-main: #f4f7f6;
            --card-bg: #ffffff;
            --border-color: #e9ecef;
            --text-muted: #888;
            --text-secondary-light: #ced4da;

            --shadow-light: rgba(0, 0, 0, 0.05);
            --shadow-medium: rgba(0, 0, 0, 0.1);
            --shadow-strong: rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--bg-color-main);
            color: var(--dark-blue);
        }

        /* Header Section (Copied from Index) */
        .header-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .header-content {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .icon-circle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 1rem;
            flex-shrink: 0;
            background-color: #d1ecf1;
            /* Light info background */
        }

        .icon-circle .fas {
            font-size: 1.8rem;
            color: var(--light-blue);
        }

        .text-content .main-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 0.25rem;
        }

        .text-content .sub-title {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        /* Breadcrumb Styling (Copied from Index) */
        .breadcrumb {
            background-color: transparent !important;
            padding: 0 !important;
            font-size: 0.85rem;
        }

        .breadcrumb-item a {
            color: var(--dark-blue) !important;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .breadcrumb-item a:hover {
            opacity: 1;
        }

        .breadcrumb-item.active {
            color: var(--dark-blue) !important;
            font-weight: 600;
        }

        /* Main Content Card */
        .card {
            border-radius: 1.25rem;
            box-shadow: 0 0.5rem 1.5rem var(--shadow-medium);
            overflow: hidden;
            background-color: var(--card-bg);
        }

        /* List Header Bar (Copied from Index) */
        .list-header-bar {
            background-color: #6200EE;
            /* Deep purple, matching image */
            padding: 1rem 2rem;
            border-top-left-radius: 1.25rem;
            border-top-right-radius: 1.25rem;
        }

        .list-header-bar .list-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
        }

        .btn-add-user-style {
            background-color: white;
            color: #6200EE;
            /* Deep purple text */
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 2rem;
            /* Pill shape */
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .btn-add-user-style:hover {
            background-color: #f0f0f0;
            color: #4a00af;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Form Styling (Adapted from Index filters) */
        .form-label-custom {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            display: block;
            text-transform: uppercase;
        }

        .form-control-filter {
            border-radius: 0.5rem;
            /* Slightly rounded corners */
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: var(--dark-blue);
            height: auto;
        }

        .form-control-filter:focus {
            border-color: var(--light-blue);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        /* Custom arrow for select (Copied from Index) */
        .custom-select-arrow {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 0.65em auto;
            padding-right: 2.5rem;
        }

        /* Error Alert Styling */
        .alert-danger-custom {
            background-color: #f8d7da;
            /* Light red */
            border-color: #f5c6cb;
            /* Slightly darker red border */
            color: #721c24;
            /* Dark red text */
            border-radius: 0.75rem;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .alert-heading-custom {
            color: #721c24;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
        }

        .alert-danger-custom ul {
            list-style-type: disc;
            margin-left: 1.5rem;
        }

        .alert-danger-custom .custom-close-alert {
            position: absolute;
            top: 0.75rem;
            right: 1.25rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #721c24;
            opacity: 0.5;
            text-shadow: none;
        }

        .alert-danger-custom .custom-close-alert:hover {
            opacity: 0.75;
        }

        .invalid-feedback {
            font-size: 0.875em;
        }

        /* Table Styling for Plan Items (Copied from Index) */
        .table-responsive {
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            margin-top: 1.5rem;
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table thead th {
            background-color: #f0f2f5;
            color: var(--dark-blue);
            font-weight: 700;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .custom-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        .custom-table tbody tr:last-child {
            border-bottom: none;
        }

        .custom-table tbody tr:hover {
            background-color: #fbfbfb;
        }

        .custom-table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            font-size: 0.85rem;
            color: var(--dark-blue);
        }

        /* Badge Styling (Copied from Index, added primary/info) */
        .badge-table {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.5em 1em;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
        }

        .badge-primary-light {
            background-color: #e6f2ff;
            color: #007bff;
        }

        .badge-info-light {
            background-color: #d1ecf1;
            color: #17a2b8;
        }

        .badge-success-light {
            background-color: #d4edda;
            color: #28a745;
        }

        .badge-warning-light {
            background-color: #fff3cd;
            color: #ffc107;
        }

        .badge-danger-light {
            background-color: #f8d7da;
            color: #dc3545;
        }

        .badge-secondary-light {
            background-color: #e2e3e5;
            color: #6c757d;
        }

        .rounded-pill-custom {
            border-radius: 50rem !important;
        }

        /* Action Buttons (Copied from Index filters) */
        .btn-primary-filter,
        .btn-outline-secondary-filter {
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .btn-primary-filter {
            background-color: var(--light-blue);
            border-color: var(--light-blue);
            color: white;
        }

        .btn-primary-filter:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-1px);
        }

        .btn-outline-secondary-filter {
            border-color: var(--text-muted);
            color: var(--text-muted);
        }

        .btn-outline-secondary-filter:hover {
            background-color: var(--text-muted);
            color: white;
            transform: translateY(-1px);
        }

        .btn-icon-text .fas {
            margin-right: 0.5rem;
        }

        .bg-light-alpha {
            background-color: rgba(248, 249, 250, 0.8);
        }

        /* Responsive Adjustments (Copied from Index) */
        @media (max-width: 992px) {
            .header-section {
                align-items: center;
                text-align: center;
            }

            .header-content {
                flex-direction: column;
                margin-bottom: 1rem;
            }

            .icon-circle {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .text-content {
                text-align: center;
            }

            .breadcrumb {
                justify-content: center;
            }

            .list-header-bar {
                flex-direction: column;
                align-items: flex-start;
                padding: 1rem;
            }

            .list-header-bar .list-title {
                margin-bottom: 1rem;
            }

            .btn-add-user-style {
                width: 100%;
            }

            .custom-table thead {
                display: none;
            }

            .custom-table tbody,
            .custom-table tr,
            .custom-table td {
                display: block;
                width: 100%;
            }

            .custom-table tr {
                margin-bottom: 1rem;
                border: 1px solid var(--border-color);
                border-radius: 0.75rem;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            }

            .custom-table td {
                text-align: right !important;
                padding-left: 50% !important;
                position: relative;
                border: none !important;
            }

            .custom-table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: calc(50% - 20px);
                text-align: left;
                font-weight: bold;
                color: var(--dark-blue);
                font-size: 0.8rem;
                text-transform: uppercase;
            }

            .custom-table td:first-child {
                text-align: center !important;
            }

            .custom-table td:last-child {
                border-top: 1px solid var(--border-color);
                text-align: center !important;
                padding-top: 1rem;
            }
        }
    </style>
@endpush

@push('scripts')
    {{-- Ensure jQuery and Popper.js are loaded for Bootstrap Tooltips and dismissible alerts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Validation for end_date must be after start_date
            $('#start_date, #end_date').change(function() {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                if (startDate && endDate && startDate > endDate) {
                    toastr.error('Ngày kết thúc phải sau hoặc bằng ngày bắt đầu!');
                    $('#end_date').val('');
                }
            });

            // Auto resize textarea
            $('textarea').each(function() {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            }).on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Add data-label for responsive table headers in existing items table
            $('.custom-table thead th').each(function(i) {
                var label = $(this).text();
                $('.custom-table tbody tr').find('td:eq(' + i + ')').attr('data-label', label);
            });
        });
    </script>
@endpush
