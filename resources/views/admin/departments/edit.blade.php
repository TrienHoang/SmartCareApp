@extends('admin.dashboard')

@section('content')
<h2>Sửa phòng ban</h2>
<form action="{{ route('admin.departments.update', $department->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Tên phòng ban:</label>
    <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>

    <label>Mô tả:</label>
    <textarea name="description" class="form-control">{{ $department->description }}</textarea>

    <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
</form>
@endsection
