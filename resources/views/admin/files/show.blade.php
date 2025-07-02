@extends('admin.dashboard')

@section('title', 'Chi tiết File')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.files.index') }}">Quản lý File</a>
                        </li>
                        <li class="breadcrumb-item active">Chi tiết File</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 text-gray-800 mt-2">Chi tiết File</h1>
            </div>
            <div>
                <a href="{{ route('admin.files.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- File Information -->
        <div class="row">
            <div class="col-lg-8">
                <!-- File Details Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin File</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tên File:</label>
                                    <p class="mb-0">
                                        <i class="fas fa-file-alt text-primary mr-2"></i>
                                        {{ $file->file_name }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kích thước file:</label>
                                    <p class="mb-0">
                                        <i class="fas fa-weight text-muted mr-2"></i>
                                        {{ number_format($file->size / 1024, 2) }} KB
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Danh mục:</label>
                                    <p class="mb-0">
                                        <span class="badge badge-info">{{ $file->file_category }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Ngày tải lên:</label>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar text-info mr-2"></i>
                                        {{ \Carbon\Carbon::parse($file->uploaded_at)->format('d/m/Y H:i:s') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Người tải lên:</label>
                                    <p class="mb-0">
                                        <i class="fas fa-user-md text-success mr-2"></i>
                                        @if ($file->user?->doctor)
                                            <span class="badge bg-primary">Bác sĩ</span>
                                        @else
                                            <span class="badge bg-info">Bệnh nhân</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- File Preview (if applicable) -->
                        @php
                            $fileExtension = pathinfo($file->file_name, PATHINFO_EXTENSION);
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        @endphp

                        @if (in_array(strtolower($fileExtension), $imageExtensions))
                            <div class="form-group">
                                <label class="font-weight-bold">Xem trước:</label>
                                <div class="text-center">
                                    <img src="{{ Storage::url($file->file_path) }}" alt="{{ $file->file_name }}"
                                        class="img-fluid rounded shadow" style="max-height: 400px; cursor: pointer;"
                                        data-toggle="modal" data-target="#imageModal">
                                    <p class="text-muted mt-2"><small>Click để xem kích thước đầy đủ</small></p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- File History -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Lịch sử File</h6>
                    </div>
                    <div class="card-body">
                        @if ($file->uploadHistories && $file->uploadHistories->count() > 0)
                            <div class="timeline">
                                @foreach ($file->uploadHistories->sortByDesc('timestamp') as $history)
                                    <div class="timeline-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="timeline-marker mr-3">
                                                @switch($history->action)
                                                    @case('uploaded')
                                                        <i class="fas fa-upload text-success"></i>
                                                    @break

                                                    @case('downloaded')
                                                        <i class="fas fa-download text-info"></i>
                                                    @break

                                                    @case('category_updated')
                                                        <i class="fas fa-edit text-warning"></i>
                                                    @break

                                                    @case('deleted')
                                                        <i class="fas fa-trash text-danger"></i>
                                                    @break

                                                    @default
                                                        <i class="fas fa-info-circle text-secondary"></i>
                                                @endswitch
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="font-weight-bold">
                                                            @switch($history->action)
                                                                @case('uploaded')
                                                                    File được tải lên
                                                                @break

                                                                @case('downloaded')
                                                                    File được tải xuống
                                                                @break

                                                                @case('category_updated')
                                                                    Cập nhật danh mục
                                                                @break

                                                                @case('deleted')
                                                                    File bị xóa
                                                                @break

                                                                @default
                                                                    {{ ucfirst($history->action) }}
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($history->timestamp)->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle mr-2"></i>
                                Chưa có lịch sử hoạt động nào
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Appointment Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin Cuộc hẹn</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Bệnh nhân:</label>
                            <p class="mb-0">
                                <i class="fas fa-user text-info mr-2"></i>
                                {{ $file->appointment->patient->full_name ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Thời gian hẹn:</label>
                            <p class="mb-0">
                                <i class="fas fa-clock text-warning mr-2"></i>
                                {{ \Carbon\Carbon::parse($file->appointment->appointment_time)->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Trạng thái:</label>
                            <p class="mb-0">
                                @switch($file->appointment->status)
                                    @case('pending')
                                        <span class="badge badge-warning">Chờ xác nhận</span>
                                    @break

                                    @case('confirmed')
                                        <span class="badge badge-info">Đã xác nhận</span>
                                    @break

                                    @case('completed')
                                        <span class="badge badge-success">Hoàn thành</span>
                                    @break

                                    @case('cancelled')
                                        <span class="badge badge-danger">Đã hủy</span>
                                    @break
                                @endswitch
                            </p>
                        </div>

                        @if ($file->appointment->reason)
                            <div class="form-group">
                                <label class="font-weight-bold">Lý do khám:</label>
                                <p class="mb-0 text-muted">{{ $file->appointment->reason }}</p>
                            </div>
                        @endif

                        @if ($file->note)
                            <div class="mt-3">
                                <strong>Ghi chú:</strong>
                                <p class="text-gray-700">{{ $file->note }}</p>
                            </div>
                        @endif

                        <div class="form-group mb-0">
                            <a href="{{ route('admin.appointments.show', $file->appointment->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Xem chi tiết cuộc hẹn
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Patient Information -->
                @if ($file->appointment->patient)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Thông tin Bệnh nhân</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Họ tên:</label>
                                <p class="mb-0">{{ $file->appointment->patient->full_name }}</p>
                            </div>

                            @if ($file->appointment->patient->phone)
                                <div class="form-group">
                                    <label class="font-weight-bold">Số điện thoại:</label>
                                    <p class="mb-0">
                                        <a href="tel:{{ $file->appointment->patient->phone }}">
                                            {{ $file->appointment->patient->phone }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                            @if ($file->appointment->patient->email)
                                <div class="form-group">
                                    <label class="font-weight-bold">Email:</label>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $file->appointment->patient->email }}">
                                            {{ $file->appointment->patient->email }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                            @if ($file->appointment->patient->date_of_birth)
                                <div class="form-group">
                                    <label class="font-weight-bold">Ngày sinh:</label>
                                    <p class="mb-0">
                                        {{ \Carbon\Carbon::parse($file->appointment->patient->date_of_birth)->format('d/m/Y') }}
                                        ({{ \Carbon\Carbon::parse($file->appointment->patient->date_of_birth)->age }} tuổi)
                                    </p>
                                </div>
                            @endif

                            @if ($file->appointment->patient->gender)
                                <div class="form-group">
                                    <label class="font-weight-bold">Giới tính:</label>
                                    <p class="mb-0">{{ $file->appointment->patient->gender }}</p>
                                </div>
                            @endif

                            @if ($file->appointment->patient->address)
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Địa chỉ:</label>
                                    <p class="mb-0">{{ $file->appointment->patient->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thao tác</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.files.download', $file->id) }}" class="btn btn-success">
                                <i class="fas fa-download"></i> Tải xuống File
                            </a>

                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#categoryModal">
                                <i class="fas fa-edit"></i> Chỉnh sửa Danh mục
                            </button>

                            <button type="button" class="btn btn-danger delete-btn" data-file-id="{{ $file->id }}"
                                data-file-name="{{ $file->file_name }}">
                                <i class="fas fa-trash"></i> Xóa File
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    @if (in_array(strtolower($fileExtension), $imageExtensions))
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $file->file_name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ Storage::url($file->file_path) }}" alt="{{ $file->file_name }}" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Category Edit Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoryForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="newCategory">Danh mục mới</label>
                            <select id="newCategory" class="form-control" data-current="{{ $file->file_category }}">
                                <option value="">-- Chọn danh mục --</option>
                                <option value="Kết quả xét nghiệm"
                                    {{ $file->file_category == 'Kết quả xét nghiệm' ? 'selected' : '' }}>Kết quả xét nghiệm
                                </option>
                                <option value="Chẩn đoán hình ảnh"
                                    {{ $file->file_category == 'Chẩn đoán hình ảnh' ? 'selected' : '' }}>Chẩn đoán hình ảnh
                                </option>
                                <option value="Đơn thuốc" {{ $file->file_category == 'Đơn thuốc' ? 'selected' : '' }}>Đơn
                                    thuốc</option>
                                <option value="Khác" {{ $file->file_category == 'Khác' ? 'selected' : '' }}>Khác
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="updateCategoryBtn" disabled>Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa file</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa file <strong id="fileName"></strong>?</p>
                    <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-marker {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fc;
            border: 2px solid #e3e6f0;
        }

        .timeline-content {
            flex: 1;
            padding: 10px 15px;
            background-color: #f8f9fc;
            border-radius: 5px;
            border-left: 3px solid #e3e6f0;
        }

        .d-grid {
            display: grid;
        }

        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const $select = $('#newCategory');
            const $submitBtn = $('#updateCategoryBtn');
            const original = $select.data('current');

            // Khi mở modal, reset lại trạng thái ban đầu
            $('#categoryModal').on('shown.bs.modal', function() {
                const selected = $select.val();
                $submitBtn.prop('disabled', selected === original);
            });

            // Theo dõi thay đổi dropdown
            $select.on('change', function() {
                const selected = $(this).val();
                $submitBtn.prop('disabled', selected === original || selected === "");
            });

            // Submit
            $('#categoryForm').submit(function(e) {
                e.preventDefault();
                const selected = $select.val();

                if (selected === original) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Không có thay đổi',
                        text: 'Bạn chưa thay đổi danh mục.'
                    });
                    return;
                }

                $.ajax({
                    url: '{{ route('admin.files.updateCategory', $file->id) }}',
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        file_category: selected
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#categoryModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Cập nhật thành công',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại',
                            text: 'Có lỗi xảy ra khi cập nhật danh mục!'
                        });
                    }
                });
            });

            // Handle delete
            $('.delete-btn').click(function() {
                const fileId = $(this).data('file-id');
                const fileName = $(this).data('file-name');

                $('#fileName').text(fileName);
                $('#deleteForm').attr('action', '{{ route('admin.files.destroy', ':id') }}'.replace(':id',
                    fileId));
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
