@extends('doctor.dashboard')

@section('title', 'Quản lý File Tải lên')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Quản lý File Tải lên</h1>
                <p class="mb-0 text-muted">Quản lý các file đã tải lên cho bệnh nhân</p>
            </div>
            <a href="{{ route('doctor.files.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tải lên File
            </a>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Bộ lọc tìm kiếm</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('doctor.files.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Tìm theo tên file</label>
                                <input type="text" id="search" name="search" class="form-control"
                                    value="{{ request('search') }}" placeholder="Nhập tên file...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="category">Danh mục</label>
                                <select id="category" name="category" class="form-control">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}"
                                            {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="appointment_id">Cuộc hẹn</label>
                                <select id="appointment_id" name="appointment_id" class="form-control">
                                    <option value="">Tất cả cuộc hẹn</option>
                                    @foreach ($appointments as $appointment)
                                        <option value="{{ $appointment->id }}"
                                            {{ request('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                            {{ $appointment->patient->full_name }} -
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d/m/Y H:i') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-search"></i> Lọc
                                    </button>
                                    <a href="{{ route('doctor.files.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Xóa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Files Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Danh sách File ({{ $files->total() }} file)
                </h6>
            </div>
            <div class="card-body">
                @if ($files->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Tên File</th>
                                    <th width="15%">Bệnh nhân</th>
                                    <th width="15%">Danh mục</th>
                                    <th width="15%">Ngày tải lên</th>
                                    <th width="15%">Cuộc hẹn</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($files as $index => $file)
                                    <tr>
                                        <td>{{ $files->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt text-primary mr-2"></i>
                                                <span class="text-truncate" style="max-width: 200px;"
                                                    title="{{ $file->file_name }}">
                                                    {{ $file->file_name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">
                                                {{ $file->appointment->patient->full_name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info category-badge"
                                                data-file-id="{{ $file->id }}"
                                                data-category="{{ $file->file_category }}" style="cursor: pointer;"
                                                title="Click để chỉnh sửa">
                                                {{ $file->file_category }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($file->uploaded_at)->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($file->appointment->appointment_time)->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('doctor.files.show', $file->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#"
                                                    class="btn btn-sm btn-outline-success" title="Tải xuống">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-file-id="{{ $file->id }}"
                                                    data-file-name="{{ $file->file_name }}" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Hiển thị {{ $files->firstItem() }} đến {{ $files->lastItem() }}
                            trong tổng số {{ $files->total() }} file
                        </div>
                        {{ $files->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có file nào được tải lên</h5>
                        <p class="text-muted mb-4">Hãy tải lên file đầu tiên của bạn</p>
                        <a href="{{ route('doctor.files.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tải lên File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa file</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa file <strong id="fileName"></strong>?</p>
                    <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa category -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa danh mục</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="categoryForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="newCategory">Danh mục mới</label>
                            <input type="text" id="newCategory" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Xử lý xóa file
            $('.delete-btn').click(function() {
                const fileId = $(this).data('file-id');
                const fileName = $(this).data('file-name');

                $('#fileName').text(fileName);
                $('#deleteForm').attr('action', '/doctor/files/' + fileId);
                $('#deleteModal').modal('show');
            });

            // Xử lý chỉnh sửa category
            let currentFileId = null;
            $('.category-badge').click(function() {
                currentFileId = $(this).data('file-id');
                const currentCategory = $(this).data('category');

                $('#newCategory').val(currentCategory);
                $('#categoryModal').modal('show');
            });

            // Submit form category
            $('#categoryForm').submit(function(e) {
                e.preventDefault();

                const newCategory = $('#newCategory').val();

                $.ajax({
                    url: '/doctor/files/' + currentFileId + '/category',
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        file_category: newCategory
                    },
                    success: function(response) {
                        if (response.success) {
                            // Cập nhật badge
                            $(`[data-file-id="${currentFileId}"]`).text(newCategory).data(
                                'category', newCategory);
                            $('#categoryModal').modal('hide');

                            // Hiển thị thông báo
                            const alert = $(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> ${response.message}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    `);
                            $('.container-fluid').prepend(alert);

                            // Tự động ẩn sau 3 giây
                            setTimeout(() => {
                                alert.fadeOut();
                            }, 3000);
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi cập nhật danh mục!');
                    }
                });
            });
        });
    </script>
@endpush
