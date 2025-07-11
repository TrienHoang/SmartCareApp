@extends('admin.dashboard')

@section('content')
    <div class="container-fluid py-5">
        <div class="card shadow-lg border-0 rounded-xl overflow-hidden">
            <div
                class="card-header bg-primary-gradient text-white d-flex justify-content-between align-items-center p-4 rounded-top-xl">
                <h3 class="card-title mb-0 text-white font-weight-bold display-5">
                    <i class="fas fa-history mr-3 opacity-75"></i>Lịch sử thay đổi kế hoạch điều trị
                </h3>
                <div class="card-tools d-flex align-items-center">
                    <a href="{{ route('admin.treatment-plans.show', $plan->id) }}" class="btn btn-outline-light btn-sm ml-2">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại
                    </a>
                    {{-- <a href="{{ route('admin.treatment-plans.edit', $plan->id) }}" class="btn btn-light btn-sm ml-2">
                        <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                    </a> --}}
                </div>
            </div>

            <div class="card-body p-5">
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="info-box border-left-primary shadow-sm rounded-lg">
                            <span class="info-box-icon bg-info-light text-info">
                                <i class="fas fa-clipboard-list fa-2x"></i>
                            </span>
                            <div class="info-box-content py-2">
                                <span class="info-box-text text-muted small">Kế hoạch điều trị</span>
                                <span class="info-box-number text-dark h4 mb-2">{{ $plan->plan_title }}</span>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-info" style="width: 100%;"></div>
                                </div>
                                <span class="progress-description text-sm text-secondary mt-2">
                                    <i class="fas fa-user-md mr-1 text-info"></i>Bác sĩ:
                                    <strong class="text-primary">{{ $plan->doctor->user->full_name ?? 'N/A' }}</strong> |
                                    <i class="fas fa-user-injured mr-1 text-info"></i>Bệnh nhân:
                                    <strong class="text-primary">{{ $plan->patient->full_name ?? 'N/A' }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($histories->count() > 0)
                    <div class="timeline mt-5">
                        @foreach ($histories->sortByDesc('changed_at') as $history)
                            <div class="time-label">
                                <span class="bg-date-label shadow-sm">
                                    {{ \Carbon\Carbon::parse($history->changed_at)->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="timeline-entry">
                                <i class="timeline-icon fas fa-pencil-alt bg-timeline-icon shadow"></i>
                                <div class="timeline-item shadow-sm rounded-lg">
                                    <span class="time text-muted small">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($history->changed_at)->format('H:i') }}
                                    </span>
                                    <h3 class="timeline-header font-weight-normal">
                                        <strong>{{ $history->changedBy->name ?? 'Hệ thống' }}</strong>
                                        <span class="text-secondary ml-2 font-weight-light">đã thực hiện thay đổi</span>
                                    </h3>
                                    <div class="timeline-body pt-3 pb-3">
                                        <p class="mb-3 text-dark">
                                            <strong>Mô tả thay đổi:</strong> {{ $history->change_description }}
                                        </p>

                                        @if ($history->old_data && $history->new_data)
                                            @php
                                                $oldData = json_decode($history->old_data, true);
                                                $newData = json_decode($history->new_data, true);
                                                $changes = [];
                                                $ignoreFields = ['updated_at'];

                                                // Tạo ánh xạ item_id => name để dùng sau
                                                $itemNames = [];
                                                foreach ($newData['items'] ?? [] as $item) {
                                                    if (isset($item['id'], $item['title'])) {
                                                        $itemNames[$item['id']] = $item['title'];
                                                    }
                                                }

                                                // Flatten thay đổi
                                                $flattenChanges = function ($old, $new, $prefix = '') use (
                                                    &$flattenChanges,
                                                    &$changes,
                                                    $ignoreFields,
                                                ) {
                                                    foreach ($new as $key => $value) {
                                                        if (in_array($key, $ignoreFields)) {
                                                            continue;
                                                        }

                                                        $fullKey = $prefix ? "{$prefix}.{$key}" : $key;

                                                        // Xử lý items có id
                                                        if (
                                                            $prefix === 'items' &&
                                                            is_numeric($key) &&
                                                            is_array($value)
                                                        ) {
                                                            $itemId = $value['id'] ?? null;
                                                            if ($itemId) {
                                                                foreach ($value as $itemField => $itemValue) {
                                                                    if (
                                                                        $itemField === 'id' ||
                                                                        in_array($itemField, $ignoreFields)
                                                                    ) {
                                                                        continue;
                                                                    }

                                                                    $oldValue = $old[$key][$itemField] ?? null;
                                                                    if ($oldValue != $itemValue) {
                                                                        $changes["item#{$itemId}→{$itemField}"] = [
                                                                            'old' => $oldValue,
                                                                            'new' => $itemValue,
                                                                        ];
                                                                    }
                                                                }
                                                            }
                                                        } elseif (is_array($value)) {
                                                            $flattenChanges($old[$key] ?? [], $value, $fullKey);
                                                        } elseif (
                                                            array_key_exists($key, $old) &&
                                                            $old[$key] != $value
                                                        ) {
                                                            $changes[$fullKey] = ['old' => $old[$key], 'new' => $value];
                                                        }
                                                    }
                                                };

                                                $flattenChanges($oldData, $newData);

                                                $fieldNames = [
                                                    'plan_title' => 'Tiêu đề kế hoạch',
                                                    'description' => 'Mô tả',
                                                    'start_date' => 'Ngày bắt đầu',
                                                    'end_date' => 'Ngày kết thúc',
                                                    'status' => 'Trạng thái',
                                                    'doctor_id' => 'Bác sĩ',
                                                    'patient_id' => 'Bệnh nhân',
                                                    'items.status' => 'Trạng thái',
                                                    'items.notes' => 'Ghi chú',
                                                    'items.expected_end_date' => 'Ngày kết thúc dự kiến',
                                                ];
                                            @endphp

                                            @if (!empty($changes))
                                                <div class="row mt-4 border-top pt-4">
                                                    <div class="col-md-6 border-right pr-4">
                                                        <h5 class="text-danger mb-3 font-weight-bold">
                                                            <i class="fas fa-minus-circle mr-2"></i>Dữ liệu cũ:
                                                        </h5>
                                                        <ul class="list-unstyled custom-list-style">
                                                            @foreach ($changes as $field => $change)
                                                                @php
                                                                    $label = $fieldNames[$field] ?? null;

                                                                    if (
                                                                        !$label &&
                                                                        preg_match(
                                                                            '/^item#(\d+)→(.+)$/',
                                                                            $field,
                                                                            $matches,
                                                                        )
                                                                    ) {
                                                                        $itemId = $matches[1];
                                                                        $subField = $matches[2];
                                                                        $subLabel =
                                                                            $fieldNames["items.$subField"] ??
                                                                            ucfirst(str_replace('_', ' ', $subField));
                                                                        $stepName = $itemNames[$itemId] ?? "ID $itemId";
                                                                        $label = "$subLabel → $stepName";
                                                                    }

                                                                    if (!$label) {
                                                                        $label = ucfirst(
                                                                            str_replace(
                                                                                '_',
                                                                                ' ',
                                                                                str_replace('.', ' → ', $field),
                                                                            ),
                                                                        );
                                                                    }
                                                                @endphp
                                                                <li class="mb-2">
                                                                    <strong class="text-dark">{{ $label }}:</strong>
                                                                    <span class="text-muted text-break d-block mt-1">
                                                                        {{ is_array($change['old']) ? json_encode($change['old'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : ($change['old'] !== null ? $change['old'] : 'Trống') }}
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6 pl-4">
                                                        <h5 class="text-success mb-3 font-weight-bold">
                                                            <i class="fas fa-plus-circle mr-2"></i>Dữ liệu mới:
                                                        </h5>
                                                        <ul class="list-unstyled custom-list-style">
                                                            @foreach ($changes as $field => $change)
                                                                @php
                                                                    $label = $fieldNames[$field] ?? null;

                                                                    if (
                                                                        !$label &&
                                                                        preg_match(
                                                                            '/^item#(\d+)→(.+)$/',
                                                                            $field,
                                                                            $matches,
                                                                        )
                                                                    ) {
                                                                        $itemId = $matches[1];
                                                                        $subField = $matches[2];
                                                                        $subLabel =
                                                                            $fieldNames["items.$subField"] ??
                                                                            ucfirst(str_replace('_', ' ', $subField));
                                                                        $stepName = $itemNames[$itemId] ?? "ID $itemId";
                                                                        $label = "$subLabel → $stepName";
                                                                    }

                                                                    if (!$label) {
                                                                        $label = ucfirst(
                                                                            str_replace(
                                                                                '_',
                                                                                ' ',
                                                                                str_replace('.', ' → ', $field),
                                                                            ),
                                                                        );
                                                                    }
                                                                @endphp
                                                                <li class="mb-2">
                                                                    <strong class="text-dark">{{ $label }}:</strong>
                                                                    <span class="text-success text-break d-block mt-1">
                                                                        {{ is_array($change['new']) ? json_encode($change['new'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : ($change['new'] !== null ? $change['new'] : 'Trống') }}
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                    </div>
                                    <div class="timeline-footer bg-light-alpha border-top pt-3 pb-3 rounded-bottom-lg">
                                        <small class="text-secondary">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $history->changedBy->full_name ?? 'Hệ thống' }} -
                                            <i class="fas fa-calendar-alt ml-3 mr-1"></i>
                                            {{ \Carbon\Carbon::parse($history->changed_at)->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center mt-5 mb-3">
                            <i class="fas fa-check-circle bg-success p-3 rounded-circle text-white shadow-lg"></i>
                            <p class="text-muted mt-3 mb-0 font-weight-bold">Toàn bộ lịch sử thay đổi đã được hiển thị</p>
                        </div>
                    </div>
                @else
                    <div class="alert alert-light-info text-center py-5 rounded-xl shadow-lg">
                        <i class="fas fa-info-circle fa-4x mb-4 text-info opacity-75"></i>
                        <h4 class="alert-heading text-info font-weight-bold mb-3">Chưa có lịch sử thay đổi nào.</h4>
                        <p class="lead text-secondary mb-0">Kế hoạch điều trị này chưa được chỉnh sửa kể từ khi được tạo.
                        </p>
                        <hr class="my-4 border-info opacity-50">
                        <p class="mb-0 text-muted small">Hãy thực hiện chỉnh sửa để bắt đầu theo dõi lịch sử.</p>
                    </div>
                @endif
            </div>

            <div
                class="card-footer d-flex justify-content-between align-items-center p-4 bg-light-alpha border-top rounded-bottom-xl">
                <div class="text-muted small">
                    <i class="fas fa-chart-line mr-2"></i>
                    Hiển thị {{ $histories->count() }} lịch sử thay đổi
                </div>
                {{-- <div class="d-flex">
                    <a href="{{ route('admin.treatment-plans.show', $plan->id) }}"
                        class="btn btn-outline-secondary btn-sm ml-2">
                        <i class="fas fa-eye mr-2"></i> Xem chi tiết
                    </a>
                    <a href="{{ route('admin.treatment-plans.edit', $plan->id) }}" class="btn btn-primary btn-sm ml-2">
                        <i class="fas fa-edit mr-2"></i> Chỉnh sửa kế hoạch
                    </a>
                </div> --}}
            </div>
        </div>
    </div>
    @push('styles')
        {{-- Animate.css for entrance animations --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
        <style>
            :root {
                --primary-color: #007bff;
                --info-color: #17a2b8;
                --success-color: #28a745;
                --danger-color: #dc3545;
                --light-gray: #f8f9fa;
                --border-color: #e9ecef;
                --text-muted: #6c757d;
                --dark-text: #343a40;
                --card-bg: #ffffff;
                --shadow-light: rgba(0, 0, 0, 0.05);
                --shadow-medium: rgba(0, 0, 0, 0.1);
                --shadow-strong: rgba(0, 0, 0, 0.2);
            }

            body {
                background-color: var(--light-gray);
                font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                /* Modern, clean font stack */
                color: var(--dark-text);
            }

            /* Custom Gradients */
            .bg-primary-gradient {
                background: linear-gradient(135deg, var(--primary-color) 0%, #6f42c1 100%);
                /* Blue to Purple */
            }

            .bg-info-light {
                background-color: rgba(23, 162, 184, 0.1);
                /* Light info color for icon background */
            }

            .bg-date-label {
                background: linear-gradient(45deg, #20c997, #17a2b8);
                /* Teal to Cyan for date labels */
            }

            .bg-timeline-icon {
                background-color: var(--info-color);
                /* Info color for timeline icons */
            }

            .bg-light-alpha {
                /* Semi-transparent light background */
                background-color: rgba(248, 249, 250, 0.8);
            }

            /* Card Enhancements */
            .card {
                border-radius: 1.25rem;
                /* More rounded corners */
                box-shadow: 0 1rem 3rem var(--shadow-medium) !important;
                /* Deeper, softer shadow */
            }

            .card-header {
                border-bottom: none;
                padding: 1.5rem 2rem;
                /* More padding */
                border-top-left-radius: 1.25rem;
                border-top-right-radius: 1.25rem;
            }

            .card-title {
                font-size: 1.75rem;
                /* Larger header title */
                letter-spacing: -0.02em;
                /* Slightly tighter letter spacing */
            }

            .card-body {
                padding: 2.5rem;
                /* Generous padding */
            }

            .card-footer {
                padding: 1.25rem 2.5rem;
                border-top: 1px solid var(--border-color);
                /* Subtle border */
                border-bottom-left-radius: 1.25rem;
                border-bottom-right-radius: 1.25rem;
            }

            /* Info Box Refinement */
            .info-box {
                background: var(--card-bg);
                border-radius: 0.75rem;
                /* More rounded */
                box-shadow: 0 0.5rem 1.5rem var(--shadow-light);
                /* Refined shadow */
                min-height: 100px;
                display: flex;
                align-items: center;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                overflow: hidden;
                /* Ensure content stays within rounded corners */
            }

            .info-box:hover {
                transform: translateY(-5px);
                /* Lift on hover */
                box-shadow: 0 0.75rem 2rem var(--shadow-medium);
            }

            .info-box-icon {
                width: 90px;
                /* Larger icon area */
                height: 100%;
                /* Fill height */
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 2.5rem;
                border-top-left-radius: 0.75rem;
                border-bottom-left-radius: 0.75rem;
                border-right: 1px solid rgba(0, 0, 0, .05);
                /* Separator line */
            }

            .info-box-content {
                padding: 0 1.5rem;
            }

            .info-box-number {
                font-size: 1.8rem;
                /* Larger and bolder */
                font-weight: 700;
                line-height: 1.2;
            }

            .progress {
                height: 4px;
                /* Even thinner progress bar */
                background-color: var(--border-color);
                border-radius: 2px;
            }

            .progress-bar {
                background-color: var(--info-color);
            }

            .progress-description {
                font-size: 0.9rem;
            }

            /* Timeline Styling - Significant Overhaul */
            .timeline {
                position: relative;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .timeline:before {
                content: '';
                position: absolute;
                top: 0;
                bottom: 0;
                left: 20px;
                /* Aligned with icon center */
                width: 3px;
                /* Thinner line */
                background: var(--border-color);
                border-radius: 1.5px;
            }

            .timeline-entry {
                position: relative;
                margin-bottom: 3rem;
                /* More vertical spacing */
                min-height: 50px;
                /* Ensure space for icon */
            }

            .timeline-icon {
                width: 40px;
                /* Icon size */
                height: 40px;
                font-size: 18px;
                line-height: 40px;
                position: absolute;
                color: white;
                background: var(--info-color);
                border-radius: 50%;
                text-align: center;
                left: 0;
                /* Aligned to the very left for the line */
                top: 0;
                z-index: 2;
                /* Ensure icon is on top of line */
                box-shadow: 0 0.25rem 0.75rem var(--shadow-light);
                /* Icon shadow */
                transition: transform 0.3s ease;
            }

            .timeline-entry:hover .timeline-icon {
                transform: scale(1.1);
                /* Pop on hover */
            }

            .timeline-item {
                background: var(--card-bg);
                border-radius: 0.75rem;
                /* Rounded corners for timeline items */
                margin-left: 70px;
                /* Offset from the line/icon */
                padding: 0;
                position: relative;
                box-shadow: 0 0.25rem 1rem var(--shadow-light);
                /* Softer shadow */
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: 1px solid var(--border-color);
                /* Subtle border */
            }

            .timeline-item:hover {
                transform: translateY(-5px);
                /* Subtle lift on hover */
                box-shadow: 0 0.5rem 1.5rem var(--shadow-medium);
            }

            .timeline-item .time {
                color: var(--text-muted);
                float: right;
                padding: 1rem;
                font-size: 0.8rem;
            }

            .timeline-item .timeline-header {
                background-color: var(--light-gray);
                /* Light background for header */
                color: var(--dark-text);
                border-bottom: 1px solid var(--border-color);
                padding: 1.2rem 1.5rem;
                font-size: 1.1rem;
                line-height: 1.4;
                border-top-left-radius: 0.75rem;
                border-top-right-radius: 0.75rem;
                font-weight: 500;
            }

            .timeline-item .timeline-body,
            .timeline-item .timeline-footer {
                padding: 1.5rem;
                /* Consistent padding */
            }

            .timeline-item .timeline-footer {
                background-color: var(--bg-light-alpha);
                /* Light alpha background */
                border-top: 1px solid var(--border-color);
                border-bottom-left-radius: 0.75rem;
                border-bottom-right-radius: 0.75rem;
            }

            .time-label {
                position: relative;
                margin-bottom: 1.5rem;
                /* Space between date label and item */
                margin-left: 70px;
                /* Align with timeline items */
                text-align: left;
                /* Align text left */
            }

            .time-label span {
                font-weight: 600;
                color: white;
                border-radius: 0.5rem;
                /* More rounded labels */
                display: inline-block;
                padding: 0.4rem 1.2rem;
                /* Generous padding */
                font-size: 0.9rem;
                box-shadow: 0 0.25rem 0.75rem var(--shadow-light);
                /* Label shadow */
            }

            /* Data Change Section */
            .border-right {
                border-right: 1px solid var(--border-color) !important;
            }

            .custom-list-style li {
                position: relative;
                padding-left: 1.5rem;
                /* Indent list items */
            }

            .custom-list-style li:before {
                content: '•';
                /* Custom bullet point */
                position: absolute;
                left: 0;
                color: var(--primary-color);
                /* Bullet color */
                font-size: 1.2em;
                line-height: 1;
            }

            .text-break {
                word-break: break-word;
                /* Better word wrapping for long strings */
            }

            /* No History Alert */
            .alert-light-info {
                background-color: rgba(23, 162, 184, 0.08);
                /* Very light info background */
                border: 1px solid rgba(23, 162, 184, 0.2);
                /* Subtle info border */
                color: var(--info-color);
                border-radius: 1rem;
                /* More rounded */
            }

            .alert-heading {
                color: var(--info-color);
            }

            /* Button Animations */
            .btn-hover-scale {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .btn-hover-scale:hover {
                transform: translateY(-2px) scale(1.02);
                box-shadow: 0 0.25rem 0.75rem var(--shadow-light);
            }

            .btn-hover-scale-light {
                transition: all 0.2s ease;
            }

            .btn-hover-scale-light:hover {
                background-color: rgba(255, 255, 255, 0.1);
                /* More subtle hover for outline */
                transform: translateY(-2px) scale(1.02);
                box-shadow: 0 0.25rem 0.75rem var(--shadow-light);
            }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .card-body {
                    padding: 1.5rem;
                }

                .timeline-item {
                    margin-left: 50px;
                    /* Adjust for smaller screens */
                }

                .timeline-icon {
                    left: -5px;
                    /* Adjust icon position */
                }

                .time-label {
                    margin-left: 50px;
                }

                .border-right {
                    border-right: none !important;
                    border-bottom: 1px solid var(--border-color) !important;
                    padding-bottom: 1.5rem;
                    margin-bottom: 1.5rem;
                }

                .col-md-6.pl-4 {
                    padding-left: 15px !important;
                    /* Reset padding for mobile */
                }
            }
        </style>
    @endpush

    @push('scripts')
        {{-- Ensure jQuery and Popper.js are loaded for Bootstrap Tooltips --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Tooltips
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    @endpush
@endsection
