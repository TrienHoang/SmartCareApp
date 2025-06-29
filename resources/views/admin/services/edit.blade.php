@extends('admin.dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2>Thêm Dịch Vụ Mới</h2>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Quay lại
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.services.store') }}">
                    @csrf

                    @include('admin.services.form')

                    <div class="text-end mt-4">
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="bx bx-reset"></i> Đặt lại
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Lưu Dịch Vụ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
