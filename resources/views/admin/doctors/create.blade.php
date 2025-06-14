@extends('admin.dashboard')
@section('content')
<h2>Thêm bác sĩ</h2>

<form action="{{ route('admin.doctors.store') }}" method="POST">
    @csrf

<label>Người dùng:</label>
<select name="user_id" class="form-control" required>
    <option value="">Chọn người dùng</option>
    @foreach($users as $user)
        <option value="{{ $user->id }}">{{ $user->full_name ?? $user->username }}</option>
    @endforeach
</select>

    <label>Chuyên môn:</label>
    <input type="text" name="specialization" class="form-control">

    <label>Phòng ban:</label>
    <select name="department_id" class="form-control">
        <option value="">Chọn phòng ban</option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}">{{ $department->name }}</option>
        @endforeach
    </select>

    <label>Tiểu sử:</label>
    <textarea name="biography" class="form-control"></textarea>

    <button type="submit" class="btn btn-success mt-3">Thêm</button>
</form>
@endsection
