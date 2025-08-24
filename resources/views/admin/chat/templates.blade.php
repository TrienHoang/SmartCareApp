@extends('admin.dashboard')

@section('title', 'Quản lý Mẫu kịch bản chat')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Mẫu kịch bản chat</h3>
                        <div class="d-flex align-items-center">
                            <!-- Form Search -->
                            <form action="{{ route('admin.chat.templates') }}" method="GET" class="form-inline mr-2">
                                <input type="text" name="search" class="form-control form-control-sm mr-2"
                                    placeholder="Tìm theo từ khóa..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>

                            <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-comments"></i> Quản lý Chat
                            </a>
                            <a href="{{ route('admin.chat.templates.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm kịch bản mới
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Từ khóa</th>
                                        <th>Phản hồi</th>
                                        <th>Dịch vụ gợi ý</th>
                                        <th>Độ ưu tiên</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($templates as $key => $template)
                                        <tr>
                                            <td>{{ $loop->iteration + ($templates->currentPage() - 1) * $templates->perPage() }}
                                            </td>
                                            <td>
                                                <strong>{{ $template->keyword }}</strong>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;"
                                                    title="{{ $template->response }}">
                                                    {{ Str::limit($template->response, 60) }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($template->suggested_services && is_array($template->suggested_services))
                                                    @foreach ($template->suggested_services as $service)
                                                        <span class="badge badge-info mr-1">{{ $service }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Không có</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $template->priority >= 7 ? 'danger' : ($template->priority >= 4 ? 'warning' : 'secondary') }}">
                                                    {{ $template->priority }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.chat.templates.toggle', $template) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm btn-{{ $template->is_active ? 'success' : 'secondary' }}"
                                                        title="{{ $template->is_active ? 'Tạm dừng' : 'Kích hoạt' }}">
                                                        <i
                                                            class="fas fa-{{ $template->is_active ? 'check' : 'times' }}"></i>
                                                        {{ $template->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>{{ $template->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.chat.templates.edit', $template) }}"
                                                        class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.chat.templates.destroy', $template) }}"
                                                        method="POST" style="display: inline;"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa template này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <div class="py-4">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">Chưa có template nào được tạo.</p>
                                                    <a href="{{ route('admin.chat.templates.create') }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Tạo Template Đầu Tiên
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($templates->hasPages())
                            <div class="d-flex justify-content-end mt-3">
                                {{ $templates->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Auto dismiss alerts after 5 seconds
            $('.alert').delay(5000).fadeOut();
        });
    </script>
@endpush
