@extends('admin.dashboard')

@section('content')
    <h2>Thêm phòng ban</h2>

    {{-- Thông báo lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Thông báo thành công --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{ route('admin.departments.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name">Tên phòng ban:</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description">Mô tả:</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Thêm</button>
    </form>
@endsection
