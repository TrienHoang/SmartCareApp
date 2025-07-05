@extends('admin.dashboard')
@section('content')
    <style>
        /* CSS của bạn ở đây */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            color: rgb(0, 0, 0);
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header p {
            opacity: 0.9;
            font-size: 16px;
        }

        .trash-icon {
            font-size: 32px;
        }

        .controls {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-row {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
            width: 100%;
            background-color: #28a745
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .bulk-actions {
            display: flex;
            gap: 15px;
            align-items: center;
            padding: 15px 0;
            border-top: 1px solid #e9ecef;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8f9fa;
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #dee2e6;
            font-size: 14px;
        }

        .table td {
            padding: 18px 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .patient-name {
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
        }

        .doctor-name {
            color: #17a2b8;
            font-weight: 600;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-deleted {
            background-color: #f8d7da;
            color: #721c24;
        }

        .description {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #666;
        }

        .date-cell {
            color: #666;
            font-size: 13px;
        }

        .deleted-date {
            color: #dc3545;
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .btn-restore {
            background-color: #28a745;
            color: white;
        }

        .btn-restore:hover {
            background-color: #218838;
        }

        .btn-delete-permanent {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete-permanent:hover {
            background-color: #c82333;
        }

        .checkbox {
            transform: scale(1.2);
            margin-right: 10px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            flex: 1;
            text-align: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state img {
            width: 100px;
            opacity: 0.5;
            margin-bottom: 20px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            /* Thêm để căn giữa modal */
            align-items: center;
            /* Thêm để căn giữa modal */
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            /* Thêm đổ bóng cho modal */
        }

        .modal-content h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .modal-content p {
            margin-bottom: 25px;
            color: #666;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .table-container {
                overflow-x: auto;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>

    <div class="container">
        <div class="header">
            <h2 class="content-header-title mb-0 text-primary font-weight-bold">
                <i class="fas fa-trash-alt mr-2"></i>
                Thùng Rác - Kế Hoạch Điều Trị
            </h2>
            <p>Quản lý các kế hoạch điều trị đã xóa. Bạn có thể khôi phục hoặc xóa vĩnh viễn.</p>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="">
                        <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                            Trang chủ >
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.treatment-plans.index') }}" class="text-decoration-none">
                            Kế hoạch Điều trị >
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary font-weight-semibold">
                        Thùng rác
                    </li>
                </ol>
            </nav>
        </div>

        <form method="GET" action="{{ route('admin.treatment-plans.trash') }}" class="controls mb-3">
            <div class="filter-row card-header bg-gradient-primary p-3 " style="width: 100%; border-radius: 8px;">

                {{-- 🔍 Tìm kiếm tên bệnh nhân hoặc bác sĩ --}}
                <div class="filter-group">
                    <label>🔍 Tìm kiếm</label>
                    <input type="text" name="search" placeholder="Tìm theo tên bệnh nhân, bác sĩ..."
                        class="form-control" value="{{ request('search') }}">
                </div>

                {{-- 👨‍⚕️ Bác sĩ - dùng input tự do (không dropdown) --}}
                <div class="filter-group">
                    <label>👨‍⚕️ Tên bác sĩ</label>
                    <input type="text" name="doctor" placeholder="Nhập tên bác sĩ" class="form-control"
                        value="{{ request('doctor') }}">
                </div>

                {{-- 📅 Ngày xóa --}}
                <div class="filter-group">
                    <label>📅 Ngày xóa</label>
                    <input type="date" name="deleted_at" class="form-control" value="{{ request('deleted_at') }}">
                </div>

                {{-- Nút lọc --}}
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </div>

                {{-- Nút reset --}}
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="{{ route('admin.treatment-plans.trash') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>



        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50"></th>
                        <th width="60">#ID</th>
                        <th>BỆNH NHÂN</th>
                        <th>BÁC SĨ</th>
                        <th>MÔ TẢ</th>
                        <th>NGÀY BẮT ĐẦU</th>
                        <th>NGÀY KẾT THÚC</th>
                        <th>NGÀY XÓA</th>
                        <th>TRẠNG THÁI</th>
                        <th width="150">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody id="trashTableBody">
                    @forelse($plans as $plan)
                        <tr>
                            <td><input type="checkbox" class="checkbox item-checkbox" data-id="{{ $plan->id }}"></td>
                            <td><strong>#{{ $plan->id }}</strong></td>
                            <td><a href="#" class="patient-name">{{ $plan->patient->full_name ?? 'N/A' }}</a></td>
                            <td><span class="doctor-name">{{ $plan->doctor->full_name ?? 'N/A' }}</span></td>
                            <td><span class="description">{{ Str::limit($plan->description, 50) }}</span></td>
                            {{-- Giới hạn mô tả --}}
                            <td><span
                                    class="date-cell">{{ \Carbon\Carbon::parse($plan->start_date)->format('d/m/Y') }}</span>
                            </td>
                            <td><span
                                    class="date-cell">{{ \Carbon\Carbon::parse($plan->end_date)->format('d/m/Y') }}</span>
                            </td>
                            <td><span class="deleted-date">{{ $plan->deleted_at->format('d/m/Y H:i:s') }}</span></td>
                            {{-- Hiển thị giờ phút giây --}}
                            <td> @php
                                $statusText = '';
                                $statusClass = '';
                                $statusIcon = '';
                                switch ($plan->status) {
                                    case 'huy_bo':
                                        $statusText = 'Hủy bỏ';
                                        $statusClass = 'danger';
                                        $statusIcon = 'bx-x-circle';
                                        break;
                                    default:
                                        $statusText = 'Chưa tiến hành';
                                        $statusClass = 'secondary';
                                        // $statusIcon = 'bx-question-mark';
                                        break;
                                }
                            @endphp
                                <span class="badge badge-{{ $statusClass }} badge-pill">
                                    <i class="bx {{ $statusIcon }} mr-1"></i>
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="actions">
                                {{-- Nút khôi phục --}}
                                <form id="restore-form-{{ $plan->id }}" method="POST"
                                    action="{{ route('admin.treatment-plans.restore', $plan->id) }}">
                                    @csrf
                                    <button type="button" class="btn btn-outline-success btn-sm"
                                        onclick="confirmRestore({{ $plan->id }})" title="Khôi phục kế hoạch">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>

                                {{-- Nút xóa vĩnh viễn --}}
                                <form id="delete-form-{{ $plan->id }}" method="POST"
                                    action="{{ route('admin.treatment-plans.force-delete', $plan->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="confirmDelete({{ $plan->id }})" title="Xóa vĩnh viễn">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <img src="https://www.svgrepo.com/show/504828/trash-empty.svg" alt="Empty Trash">
                                    <p>Không có dữ liệu.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <i class="bx bx-error-circle fs-1 text-warning mb-2"></i>
            <h3 id="modalTitle"></h3>
            <p id="modalMessage"></p>
            <div class="modal-actions">
                <button onclick="closeModal()"class="btn btn-secondary">Hủy</button>
                <button id="confirmBtn" onclick="confirmAction()"class="btn btn-danger">Xác nhận</button>
            </div>
        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        let currentAction = null;
        let selectedItems = [];
        // Biến này không được sử dụng nhưng có thể giữ lại nếu bạn định dùng nó khác

        function showModal(title, message, action) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('confirmModal').style.display = 'flex'; // Thay đổi thành 'flex' để căn giữa
            currentAction = action;
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
            currentAction = null;
        }

        function confirmAction() {
            if (currentAction) {
                currentAction();
            }
            closeModal();
        }
        let formToSubmit = null;

        function confirmDelete(id) {
            formToSubmit = document.getElementById(`delete-form-${id}`);
            showModal(
                'Xóa vĩnh viễn kế hoạch',
                'Bạn có chắc chắn muốn xóa kế hoạch điều trị này? Hành động này không thể hoàn tác.',
                () => formToSubmit?.submit()
            );
        }

        function confirmRestore(id) {
            formToSubmit = document.getElementById(`restore-form-${id}`);
            showModal(
                'Khôi phục kế hoạch điều trị',
                'Bạn có chắc chắn muốn khôi phục kế hoạch điều trị này không?',
                () => formToSubmit?.submit()
            );
        }

        function confirmEmptyTrash() {
            const form = document.getElementById('empty-trash-form');
            showModal(
                'Dọn sạch thùng rác',
                'Bạn có chắc chắn muốn xóa vĩnh viễn toàn bộ kế hoạch trong thùng rác? Hành động này không thể hoàn tác.',
                form
            );
        }
    </script>
@endsection
