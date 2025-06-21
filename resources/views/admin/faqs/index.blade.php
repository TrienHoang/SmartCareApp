@extends('admin.dashboard')

@section('content')
    <div class="container mt-4">
        <h2>Danh sách Câu hỏi thường gặp</h2>

        {{-- Form lọc theo danh mục --}}
        <form action="{{ route('admin.faqs.index') }}" method="GET" class="row row-cols-lg-auto g-2 align-items-center mb-3">
            <div class="col-12">
                <label class="form-label mb-0 me-2">Lọc theo danh mục:</label>
                <select name="service_category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}" {{ request('service_category_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                @if (request('service_category_id'))
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">🧹 Xoá lọc</a>
                @endif
            </div>
        </form>

        {{-- Nút thêm --}}
        <div class="mb-3">
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">➕ Thêm câu hỏi</a>
        </div>

        {{-- Thông báo --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Bảng danh sách --}}
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Câu hỏi</th>
                    <th>Danh mục</th>
                    <th>Thứ tự</th>
                    <th>Hiển thị</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($faqs as $faq)
                    <tr>
                        <td>{{ $loop->iteration + ($faqs->currentPage() - 1) * $faqs->perPage() }}</td>
                        <td>{{ $faq->question }}</td>
                        <td>{{ $faq->category_name }}</td>
                        <td>{{ $faq->display_order }}</td>
                        <td>
                            @if ($faq->is_active)
                                <span class="badge bg-success">Hiện</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Xoá câu hỏi này?')"
                                    class="btn btn-sm btn-danger">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Không có câu hỏi nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="card-footer d-flex justify-content-end">
            {{ $faqs->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
