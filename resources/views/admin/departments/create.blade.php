@extends('admin.dashboard')

@section('content')
<h2>Thêm phòng ban</h2>
<form action="{{ route('admin.departments.store') }}" method="POST">
    @csrf
    <label>Tên phòng ban:</label>
    <input type="text" name="name" class="form-control" required>

    <label>Mô tả:</label>
    <textarea name="description" class="form-control"></textarea>

    <button type="submit" class="btn btn-success mt-3">Thêm</button>
</form>
@endsection
