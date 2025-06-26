@extends('admin.dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1 class="mb-4">Danh sách danh mục dịch vụ</h1>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Add New Category Button --}}
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

        {{-- Check if categories are empty --}}
        @if ($categories->isEmpty())
            <p class="text-muted">Không có danh mục nào.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description }}</td>
                                <td>
                                    <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-info">Xem</a>
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá?')">Xoá</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="card-footer d-flex justify-content-end">
                {{ $categories->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
