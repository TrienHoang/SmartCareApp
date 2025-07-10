@extends('doctor.dashboard')
@section('title', 'Xem kế hoạch điều trị')


@section('content')
    <div class="container">
        <h1>Chi tiết Kế hoạch Điều trị: {{ $treatmentPlan->plan_title }}</h1>

        <div class="card mb-4">
            <div class="card-header">Thông tin chung</div>
            <div class="card-body">
                <p><strong>Bệnh nhân:</strong> {{ $treatmentPlan->patient->full_name ?? 'N/A' }} </p>
                <p><strong>Bác sĩ phụ trách:</strong> {{ $treatmentPlan->doctor->user->full_name ?? 'N/A' }} </p>
                <p><strong>Chẩn đoán:</strong> {{ $treatmentPlan->diagnosis }}</p>
                <p><strong>Mục tiêu điều trị:</strong> {{ $treatmentPlan->goal }}</p>
                <p><strong>Ngày bắt đầu dự kiến:</strong> {{ $treatmentPlan->start_date?->format('d-m-Y') }}</p>
                <p><strong>Ngày kết thúc dự kiến:</strong> {{ $treatmentPlan->end_date?->format('d-m-Y') }}</p>
                <p><strong>Tổng chi phí ước tính:</strong>
                    {{ number_format($treatmentPlan->total_estimated_cost, 0, ',', '.') }} VNĐ</p>
                <p><strong>Ghi chú chung:</strong> {{ $treatmentPlan->notes }}</p>
                <p><strong>Trạng thái kế hoạch:</strong>
                    <span id="overall-plan-status"
                        class="badge {{ $treatmentPlan->getBadgeClassForStatus($treatmentPlan->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $treatmentPlan->status)) }}
                    </span>
                </p>
                <p><strong>Ngày tạo:</strong> {{ $treatmentPlan->created_at?->format('d-m-Y H:i:s') }}</p>
            </div>
        </div>

        <h2 class="mt-4">Các Bước Điều trị</h2>
        <a href="{{ route('doctor.treatment-plans.edit', $treatmentPlan) }}" class="btn btn-warning mb-3">Chỉnh sửa Kế hoạch
            & Các Bước</a>

        @if ($treatmentPlan->items->isEmpty())
            <div class="alert alert-info">Kế hoạch này chưa có bước điều trị nào.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên/Mô tả bước</th>
                            <th>Ngày BĐ Dự kiến</th>
                            <th>Ngày KT Dự kiến</th>
                            <th>Tần suất</th>
                            <th>Trạng thái</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($treatmentPlan->items as $item)
                            <tr id="item-row-{{ $item->id }}"> {{-- Thêm ID cho hàng để dễ dàng cập nhật bằng JS --}}
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $item->title }}</strong><br>
                                    <small>{{ $item->description }}</small>
                                </td>
                                <td>{{ $item->expected_start_date?->format('d-m-Y') }}</td>
                                <td>{{ $item->expected_end_date?->format('d-m-Y') }}</td>
                                <td>{{ $item->frequency }}</td>
                                <td class="item-status-cell"> {{-- Class để cập nhật trạng thái --}}
                                    <span
                                        class="badge {{ $item->getBadgeClassForStatus($item->status) }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                </td>
                                <td class="item-notes-cell">
                                    <span class="current-notes">{{ $item->notes }}</span>
                                    <textarea class="form-control edit-notes-textarea d-none" rows="2">{{ $item->notes }}</textarea>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary update-status-btn"
                                        data-bs-toggle="modal" data-bs-target="#updateItemStatusModal"
                                        data-item-id="{{ $item->id }}" data-item-title="{{ $item->title }}"
                                        data-current-status="{{ $item->status }}"
                                        data-current-notes="{{ $item->notes }}">
                                        Cập nhật
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Modal để cập nhật trạng thái và ghi chú của Treatment Plan Item --}}
        <div class="modal fade" id="updateItemStatusModal" tabindex="-1" aria-labelledby="updateItemStatusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateItemStatusModalLabel">Cập nhật Tiến độ Bước Điều trị</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateItemStatusForm">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" id="modalItemId" name="item_id">
                            <p><strong>Bước:</strong> <span id="modalItemTitle"></span></p>

                            <div class="mb-3">
                                <label for="modalStatus" class="form-label">Trạng thái:</label>
                                <select class="form-select" id="modalStatus" name="status">
                                    <option value="pending">Chưa thực hiện</option>
                                    <option value="in_progress">Đang tiến hành</option>
                                    <option value="completed">Đã hoàn thành</option>
                                    <option value="paused">Tạm dừng</option>
                                    <option value="cancelled">Hủy bỏ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="modalNotes" class="form-label">Ghi chú tiến độ:</label>
                                <textarea class="form-control" id="modalNotes" name="notes" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="saveItemStatusBtn">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mt-4">Lịch sử Thay đổi Kế hoạch</h2>
        @if ($treatmentPlan->histories->isEmpty())
            <div class="alert alert-info">Chưa có lịch sử thay đổi nào cho kế hoạch này.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Thời gian thay đổi</th>
                            <th>Người thay đổi</th>
                            <th>Mô tả thay đổi</th>
                            <th>Chi tiết dữ liệu cũ</th>
                            <th>Chi tiết dữ liệu mới</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($treatmentPlan->histories->sortByDesc('changed_at') as $history)
                            <tr>
                                <td>{{ $history->changed_at?->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $history->changedBy->name ?? 'Hệ thống' }}</td>
                                <td>{{ $history->change_description }}</td>
                                <td>
                                    @if ($history->old_data)
                                        <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#oldData{{ $history->id }}" aria-expanded="false"
                                            aria-controls="oldData{{ $history->id }}">
                                            Xem chi tiết
                                        </button>
                                        <div class="collapse" id="oldData{{ $history->id }}">
                                            <pre class="bg-light p-2 mt-2" style="max-height: 200px; overflow-y: auto;">{{ json_encode($history->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if ($history->new_data)
                                        <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#newData{{ $history->id }}" aria-expanded="false"
                                            aria-controls="newData{{ $history->id }}">
                                            Xem chi tiết
                                        </button>
                                        <div class="collapse" id="newData{{ $history->id }}">
                                            <pre class="bg-light p-2 mt-2" style="max-height: 200px; overflow-y: auto;">{{ json_encode($history->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <a href="{{ route('doctor.treatment-plans.index') }}" class="btn btn-secondary mt-3">Quay lại Danh sách</a>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kiểm tra xem mã đã được khởi tạo chưa
            if (window.isTreatmentPlanScriptInitialized) return;
            window.isTreatmentPlanScriptInitialized = true;

            const updateItemStatusModal = document.getElementById('updateItemStatusModal');
            const modalItemId = document.getElementById('modalItemId');
            const modalItemTitle = document.getElementById('modalItemTitle');
            const modalStatus = document.getElementById('modalStatus');
            const modalNotes = document.getElementById('modalNotes');
            const saveItemStatusBtn = document.getElementById('saveItemStatusBtn');
            const overallPlanStatusBadge = document.getElementById('overall-plan-status');

            let isProcessing = false;

            // Xử lý khi modal hiển thị
            updateItemStatusModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const itemId = button.getAttribute('data-item-id');
                const itemTitle = button.getAttribute('data-item-title');
                const currentStatus = button.getAttribute('data-current-status');
                const currentNotes = button.getAttribute('data-current-notes');

                modalItemId.value = itemId;
                modalItemTitle.textContent = itemTitle;
                modalStatus.value = currentStatus; // Đặt giá trị select dựa trên data-current-status
                modalNotes.value = currentNotes;
            });

            // Xử lý khi nhấn nút Lưu thay đổi
            const saveItemStatusHandler = function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (isProcessing) return;
                isProcessing = true;

                const itemId = modalItemId.value;
                const newStatus = modalStatus.value;
                const newNotes = modalNotes.value;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // console.log('Sending request to:',
                    //     `/treatment-plans/treatment-plan-items/${itemId}/update-status`);
                    // console.log('Data:', {
                    //     status: newStatus,
                    //     notes: newNotes
                    // });

                fetch(`treatment-plan-items/${itemId}/update-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: newStatus,
                            notes: newNotes
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 422) {
                                return response.json().then(errorData => {
                                    throw new Error(errorData.message || 'Validation error');
                                });
                            }
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        const itemRow = document.getElementById(`item-row-${itemId}`);
                        if (itemRow) {
                            const statusCell = itemRow.querySelector('.item-status-cell span');
                            statusCell.className = `badge ${data.badge_class}`;
                            statusCell.textContent = data.new_status_text;

                            const notesCell = itemRow.querySelector('.item-notes-cell .current-notes');
                            notesCell.textContent = data.new_notes;

                            // Cập nhật data-current-status của nút "Cập nhật" để phản ánh trạng thái mới
                            const updateButton = itemRow.querySelector('.update-status-btn');
                            if (updateButton) {
                                updateButton.setAttribute('data-current-status', newStatus);
                            }
                        }

                        if (overallPlanStatusBadge && data.plan_status_text) {
                            overallPlanStatusBadge.className = `badge ${data.plan_badge_class}`;
                            overallPlanStatusBadge.textContent = data.plan_status_text;
                        }

                        alert(data.message);
                        const bsModal = bootstrap.Modal.getInstance(updateItemStatusModal);
                        bsModal.hide();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Lỗi: ' + error.message);
                    })
                    .finally(() => {
                        isProcessing = false;
                    });
            };

            // Xóa sự kiện cũ nếu có
            saveItemStatusBtn.removeEventListener('click', saveItemStatusHandler);
            // Gắn sự kiện mới
            saveItemStatusBtn.addEventListener('click', saveItemStatusHandler);
        });
    </script>
@endpush
