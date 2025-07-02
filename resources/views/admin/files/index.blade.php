@extends('admin.dashboard')
@section('title', 'Quản lý Tài liệu y tế')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <h1 class="h4">Quản lý Tài liệu y tế</h1>
            <a href="{{ route('admin.files.trash') }}" class="btn btn-outline-danger mb-2">
                <i class="fas fa-trash-alt"></i> Thùng rác
            </a>
        </div>

        <!-- 📊 Thống kê nhanh -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="card shadow rounded-2xl p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Tổng số file</div>
                            <div class="h5">{{ $totalFiles }}</div>
                        </div>
                        <i class="fas fa-file-medical fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="card shadow rounded-2xl p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Tổng dung lượng</div>
                            <div class="h5">{{ number_format($totalSize / 1024, 2) }} MB</div>
                        </div>
                        <i class="fas fa-database fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔍 Bộ lọc nâng cao -->
        <form method="GET" action="{{ route('admin.files.index') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên hoặc mô tả"
                        value="{{ request('keyword') }}">
                </div>
                <div class="col-md-2">
                    <select name="uploader_type" class="form-select">
                        <option value="">-- Người tải lên --</option>
                        <option value="doctor" {{ request('uploader_type') == 'doctor' ? 'selected' : '' }}>Bác sĩ</option>
                        <option value="patient" {{ request('uploader_type') == 'patient' ? 'selected' : '' }}>Bệnh nhân
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <select name="file_category" class="form-select">
                        <option value="">-- Danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('file_category') == $category ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('-', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>

        <!-- 📂 Danh sách file -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên file</th>
                        <th>Người tải lên</th>
                        <th>Loại tài liệu</th>
                        <th>Ngày tải lên</th>
                        <th>Lịch hẹn</th>
                        <th>Ghi chú</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($files as $file)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $file->file_name }}</td>
                            <td>
                                {{ $file->user?->full_name ?? 'Không xác định' }}
                                <span class="d-block small text-muted">ID: {{ $file->user_id }}</span>
                            </td>
                            <td>{{ $file->file_category ?? 'Không có' }}</td>
                            <td>{{ $file->uploaded_at ? $file->uploaded_at->format('d/m/Y H:i') : 'Chưa rõ' }}</td>
                            <td>
                                Mã: #{{ $file->appointment_id }}
                                @if ($file->appointment)
                                    <div class="small text-muted">
                                        {{ $file->appointment->appointment_time->format('d/m/Y H:i') }}</div>
                                @endif
                            </td>
                            <td>{{ $file->note ?? '-' }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                    class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ asset('storage/' . $file->file_path) }}" download
                                    class="btn btn-sm btn-success">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form method="POST" action="{{ route('doctor.files.destroy', $file->id) }}"
                                    class="d-inline" onsubmit="return confirm('Xoá file này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có file nào.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if ($files->hasPages())
            <div class="pagination-wrapper bg-light p-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-info">
                        <small class="text-muted">
                            Hiển thị {{ $files->firstItem() }} - {{ $files->lastItem() }}
                            trong tổng số {{ $files->total() }} kết quả
                        </small>
                    </div>
                    <div class="pagination-links">
                        {{ $files->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
