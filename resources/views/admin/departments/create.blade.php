@extends('admin.dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                <h4 class="mb-0">Thêm phòng ban</h4>
            </div>

            <div class="card-body">
                {{-- Thông báo thành công --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('admin.departments.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Tên phòng ban <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description"
                                  class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Thêm
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
