@extends('admin.dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark d-flex align-items-center">
                <i class="fas fa-edit mr-2"></i>
                <h4 class="mb-0">Sửa phòng ban</h4>
            </div>

            <div class="card-body">
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

                    <div class="row">
                        {{-- Tên --}}
                        <div class="col-12 mb-3">
                            <label for="name">Tên phòng ban:</label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $department->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" >
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mô tả --}}
                        <div class="col-12 mb-3">
                            <label for="description">Mô tả:</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $department->description) }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nút --}}
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection