@extends('admin.dashboard')

@section('content')
    <h1>Danh sách danh mục dịch vụ</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

    @if ($categories->isEmpty())
        <p>Không có danh mục nào.</p>
    @else
        <table class="table table-bordered">
            <thead>
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
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Xoá?')" class="btn btn-sm btn-danger">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="card-footer d-flex justify-content-end">
            {{ $categories->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
