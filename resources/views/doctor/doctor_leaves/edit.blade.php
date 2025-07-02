@extends('doctor.dashboard')

@section('title', 'Chỉnh sửa lịch nghỉ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa lịch nghỉ</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.leaves.update', $leave->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="start_date" class="form-label fw-semibold">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', $leave->start_date) }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label fw-semibold">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', $leave->end_date) }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label fw-semibold">Lý do <span class="text-danger">*</span></label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" placeholder="Nhập lý do nghỉ">{{ old('reason', $leave->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Cập nhật
                            </button>
                            <a href="{{ route('doctor.leaves.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
