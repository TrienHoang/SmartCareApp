@extends('admin.dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2 class="mb-4">Sửa phòng ban</h2>

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
                <label for="name" class="form-label">Tên phòng ban:</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $department->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea name="description" id="description" class="form-control"
                          rows="4">{{ old('description', $department->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
