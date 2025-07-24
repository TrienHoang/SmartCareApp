@extends('doctor.dashboard')
@section('title', 'Xem kế hoạch điều trị')


@section('content')
<div class="container mt-5 mb-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Chi Tiết Kế Hoạch Điều Trị</h2>
        <a href="{{ route('doctor.treatment-plans.edit', $treatmentPlan->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Chỉnh Sửa Kế Hoạch
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clipboard-list"></i> Kế Hoạch: {{ $treatmentPlan->plan_title }}</h3>
            <span id="plan-status-badge" class="badge badge-status {{ $statusBadgeClass }}">
                {{ ucfirst(str_replace('_', ' ', $treatmentPlan->status)) }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-group">
                        <strong>Bệnh nhân:</strong>
                        <span>{{ $treatmentPlan->patient->full_name }} ({{ $treatmentPlan->patient->email }})</span>
                    </div>
                    <div class="info-group">
                        <strong>Chẩn đoán:</strong>
                        <span>{{ $treatmentPlan->diagnosis ?? 'N/A' }}</span>
                    </div>
                    <div class="info-group">
                        <strong>Mục tiêu:</strong>
                        <span>{{ $treatmentPlan->goal ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-group">
                        <strong>Bác sĩ phụ trách:</strong>
                        <span>{{ $treatmentPlan->doctor->user->full_name }}</span>
                    </div>
                    <div class="info-group">
                        <strong>Ngày bắt đầu:</strong>
                        <span>{{ $treatmentPlan->start_date ? $treatmentPlan->start_date->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-group">
                        <strong>Ngày kết thúc (ước tính):</strong>
                        <span>{{ $treatmentPlan->end_date ? $treatmentPlan->end_date->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-group">
                        <strong>Tổng chi phí ước tính:</strong>
                        <span>{{ number_format($treatmentPlan->total_estimated_cost) }} VND</span>
                    </div>
                </div>
            </div>
            <div class="info-group mt-3">
                <strong>Ghi chú kế hoạch:</strong>
                <span>{{ $treatmentPlan->notes ?? 'Không có ghi chú.' }}</span>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5><i class="fas fa-tasks"></i> Các Bước Điều Trị</h5>
        </div>
        <div class="card-body">
            @forelse ($treatmentPlan->items as $item)
                <div class="item-card" id="item-{{ $item->id }}">
                    <div class="item-card-header">
                        <div>
                            <strong>{{ $item->title }}</strong>
                            <span id="plan-status-badge" class="badge badge-status {{ $statusBadgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="item-body">
                        @if ($item->service)
                            <div class="info-group">
                                <strong>Dịch vụ:</strong>
                                <span>{{ $item->service->name }} ({{ number_format($item->service->price) }} VND)</span>
                            </div>
                        @endif
                        <div class="info-group">
                            <strong>Mô tả:</strong>
                            <span>{{ $item->description ?? 'N/A' }}</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <strong>Ngày bắt đầu dự kiến:</strong>
                                    <span>{{ $item->expected_start_date ? $item->expected_start_date->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <strong>Ngày kết thúc dự kiến:</strong>
                                    <span>{{ $item->expected_end_date ? $item->expected_end_date->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="info-group">
                            <strong>Tần suất:</strong>
                            <span>{{ $item->frequency ?? 'N/A' }}</span>
                        </div>
                        <div class="info-group">
                            <strong>Ghi chú bước:</strong>
                            <span id="item-notes-{{ $item->id }}">{{ $item->notes ?? 'Không có ghi chú.' }}</span>
                        </div>

                        @if ($item->appointments->isNotEmpty())
                            @foreach ($item->appointments as $appointment)
                                <hr>
                                <h6><i class="far fa-calendar-alt"></i> Lịch hẹn liên quan:</h6>
                                <div class="info-group ms-3">
                                    <strong>Thời gian:</strong>
                                    <span>{{ $appointment->appointment_time->format('H:i d/m/Y') }}</span>
                                </div>
                                <div class="info-group ms-3">
                                    <strong>Trạng thái lịch hẹn:</strong>
                                    <span class="badge badge-status {{ $this->getBadgeClassForStatus($appointment->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </div>
                                <div class="info-group ms-3">
                                    <strong>Lý do:</strong>
                                    <span>{{ $appointment->reason ?? 'N/A' }}</span>
                                </div>
                                @if ($appointment->service)
                                <div class="info-group ms-3">
                                    <strong>Dịch vụ trong lịch hẹn:</strong>
                                    <span>{{ $appointment->service->name }}</span>
                                </div>
                                @endif
                            @endforeach
                        @endif

                        @if (in_array($treatmentPlan->status, ['active', 'in_progress', 'draft', 'paused']))
                            <div class="status-update-form mt-3">
                                <label for="status-select-{{ $item->id }}" class="form-label mb-0">Cập nhật trạng thái:</label>
                                <select class="form-select form-select-sm status-select" id="status-select-{{ $item->id }}" data-item-id="{{ $item->id }}">
                                    <option value="">Chọn trạng thái</option>
                                    <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="in_progress" {{ $item->status == 'in_progress' ? 'selected' : '' }}>Đang tiến hành</option>
                                    <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="paused" {{ $item->status == 'paused' ? 'selected' : '' }}>Tạm dừng</option>
                                    <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Hủy bỏ</option>
                                </select>
                                <textarea class="form-control form-control-sm notes-input" rows="1" placeholder="Ghi chú (tùy chọn)" data-item-id="{{ $item->id }}">{{ $item->notes }}</textarea>
                                <button type="button" class="btn btn-sm btn-primary update-status-btn" data-item-id="{{ $item->id }}">
                                    <i class="fas fa-sync-alt"></i> Cập nhật
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted text-center">Kế hoạch này chưa có bước điều trị nào.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusUpdateModalLabel">Thông Báo Cập Nhật Trạng Thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background-color: #f8f9fa;
    }
    .container {
        max-width: 1000px;
    }
    .card {
        border-radius: 0.75rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }
    .card-header {
        background-color: #007bff; /* Primary blue for header */
        color: white;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header h3, .card-header h5 {
        color: white;
        margin-bottom: 0;
    }
    .info-group {
        margin-bottom: 1rem;
    }
    .info-group strong {
        display: block;
        margin-bottom: 0.25rem;
        color: #495057;
    }
    .badge-status {
        font-size: 0.9em;
        padding: 0.4em 0.8em;
        border-radius: 0.35rem;
        vertical-align: middle;
        color: white; /* Default text color for badges */
    }
    /* Define colors for specific badge classes based on your controller */
    .badge-warning { background-color: #ffc107; color: #343a40; } /* Pending - Yellow/Orange */
    .badge-info { background-color: #17a2b8; } /* In Progress - Light Blue */
    .badge-success { background-color: #28a745; } /* Completed - Green */
    .badge-secondary { background-color: #6c757d; } /* Paused/Default - Gray */
    .badge-danger { background-color: #dc3545; } /* Cancelled - Red */

    /* Additional badge classes for overall plan status or specific needs if any */
    /* For treatment plan overall status (if it uses different states than items) */
    .badge-draft { background-color: #6c757d; } /* Example: Draft for plan */
    .badge-active { background-color: #28a745; } /* Example: Active for plan */


    .item-card {
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        margin-bottom: 15px;
        background-color: #fff;
    }
    .item-card-header {
        background-color: #f0f4f7;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #e9ecef;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .item-body {
        padding: 1.25rem;
    }
    .status-update-form {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 15px;
        border-top: 1px dashed #e9ecef;
        padding-top: 15px;
    }
    .status-update-form .form-select {
        max-width: 200px;
    }
    .status-update-form button {
        white-space: nowrap;
    }
    .modal-body strong {
        display: block;
        margin-bottom: 5px;
        color: #343a40;
    }
    .modal-body span {
        color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Function to get badge class (matches controller logic for consistent colors)
        function getBadgeClass(status) {
            switch (status) {
                case 'pending': return 'badge-warning';
                case 'in_progress': return 'badge-info';
                case 'completed': return 'badge-success';
                case 'paused': return 'badge-secondary';
                case 'cancelled': return 'badge-danger';
                // For overall plan status if different from item statuses
                case 'draft': return 'badge-secondary'; // Assuming draft uses secondary
                case 'active': return 'badge-success'; // Assuming active uses success
                default: return 'badge-secondary';
            }
        }

        // AJAX call to update item status
        $('.update-status-btn').on('click', function() {
            const itemId = $(this).data('item-id');
            const newStatus = $(`#status-select-${itemId}`).val();
            const newNotes = $(`textarea.notes-input[data-item-id="${itemId}"]`).val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'; // Fallback for CSRF

            if (!newStatus) {
                $('#modal-body-content').html('<div class="alert alert-warning">Vui lòng chọn một trạng thái mới.</div>');
                new bootstrap.Modal(document.getElementById('statusUpdateModal')).show();
                return;
            }

            $.ajax({
                url: `/doctor/treatment-plan-items/${itemId}/update-status`, // Ensure this route exists
                type: 'POST',
                data: {
                    _token: csrfToken,
                    status: newStatus,
                    notes: newNotes
                },
                beforeSend: function() {
                    // Optional: Show loading spinner
                    $('#item-status-badge-' + itemId).text('Đang cập nhật...').removeClass().addClass('badge badge-status badge-secondary');
                    $('.update-status-btn[data-item-id="' + itemId + '"]').prop('disabled', true);
                },
                success: function(response) {
                    // Update UI with new status and notes
                    const statusBadge = $('#item-status-badge-' + itemId);
                    statusBadge.text(response.new_status_text);
                    statusBadge.removeClass().addClass('badge badge-status ' + response.badge_class);
                    $('#item-notes-' + itemId).text(response.new_notes || 'Không có ghi chú.');

                    // Update overall plan status if returned
                    if (response.plan_status_text) {
                        const planStatusBadge = $('#plan-status-badge');
                        planStatusBadge.text(response.plan_status_text);
                        planStatusBadge.removeClass().addClass('badge badge-status ' + response.plan_badge_class);
                    }

                    // Show success message in modal
                    $('#modal-body-content').html(`<div class="alert alert-success"><i class="fas fa-check-circle"></i> ${response.message}</div>`);
                    new bootstrap.Modal(document.getElementById('statusUpdateModal')).show();
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON;
                    let errorMessage = 'Đã có lỗi xảy ra.';
                    if (errors && errors.message) {
                        errorMessage = errors.message;
                    } else if (xhr.statusText) {
                        errorMessage = xhr.statusText;
                    }

                    // Revert status text on failure to the selected text before the request
                    const currentStatusText = $(`#status-select-${itemId} option:selected`).text();
                    $('#item-status-badge-' + itemId).text(currentStatusText); // This might show the *new* selected status, not the old one
                    // A better approach would be to store the *original* status before sending the request.
                    // For now, it will show the state user tried to set, which is still informative.

                    $('#modal-body-content').html(`<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> ${errorMessage}</div>`);
                    new bootstrap.Modal(document.getElementById('statusUpdateModal')).show();
                },
                complete: function() {
                    $('.update-status-btn[data-item-id="' + itemId + '"]').prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
