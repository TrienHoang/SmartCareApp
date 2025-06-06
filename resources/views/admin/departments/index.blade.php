@extends('admin.dashboard')

@section('content')
<h2>Danh sách phòng ban</h2>
<a href="{{ route('admin.departments.create') }}" class="btn btn-success">Thêm phòng ban</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên phòng ban</th>
            <th>Mô tả</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($departments as $department)
        <tr>
            <td>{{ $department->id }}</td>
            <td>{{ $department->name }}</td>
            <td>{{ $department->description }}</td>
            <td>
                <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-primary">Sửa</a>
                <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
