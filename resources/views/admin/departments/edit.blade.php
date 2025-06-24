@extends('admin.dashboard')

@section('content')
    <h2>Sửa phòng ban</h2>

    {{-- Hiển thị lỗi --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{ route('admin.departments.update', $department->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name">Tên phòng ban:</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $department->name) }}"
                   class="form-control @error('name') is-invalid @enderror" >
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description">Mô tả:</label>
            <textarea name="description" id="description" class="form-control"
                      rows="3">{{ old('description', $department->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
