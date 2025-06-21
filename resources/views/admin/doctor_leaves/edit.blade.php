@extends('admin.dashboard')
@section('title', 'Cập nhật ngày nghỉ bác sĩ')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Cập nhật trạng thái duyệt ngày nghỉ</h5>
                    </div>
                    <div class="card-body">
                        {{-- Thông tin bác sĩ --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên bác sĩ:</label>
                            <div class="form-control-plaintext">{{ $leave->doctor->user->full_name ?? 'Không có thông tin' }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phòng làm việc:</label>
                            <div class="form-control-plaintext">{{ $leave->doctor->room->name ?? 'Không có thông tin' }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Thời gian nghỉ:</label>
                            <div class="form-control-plaintext">
                                {{ $leave->start_date }} đến {{ $leave->end_date }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Lý do:</label>
                            <div class="form-control-plaintext text-wrap">
                                {{ $leave->reason ?? 'Không có lý do' }}
                            </div>
                        </div>

                        {{-- Form cập nhật --}}
                        <form action="{{ route('admin.doctor_leaves.update', $leave->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="approved" class="form-label fw-semibold">Trạng thái duyệt:</label>
                                <select id="approved" name="approved" class="form-select" >
                                    <option value="0" {{ $leave->approved == 0 ? 'selected' : '' }}>Chưa duyệt</option>
                                    <option value="1" {{ $leave->approved == 1 ? 'selected' : '' }}>Đã duyệt</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.doctor_leaves.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-arrow-back"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-save"></i> Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
