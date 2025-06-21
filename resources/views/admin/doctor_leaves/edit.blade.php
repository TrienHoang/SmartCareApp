@extends('admin.dashboard')
@section('title', 'Cập nhật ngày nghỉ bác sĩ')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bx bx-calendar-check me-2 fs-5"></i>
                    <h5 class="mb-0">Cập nhật ngày nghỉ bác sĩ</h5>
                </div>
                <div class="card-body">

                    {{-- Thông tin bác sĩ --}}
                    <div class="row align-items-center mb-4">
                        <div class="col-md-3 text-center">
                            @if($leave->doctor->user->avatar)
                                <img src="{{ Storage::url($leave->doctor->user->avatar) }}"
                                     alt="Avatar"
                                     class="img-thumbnail rounded-circle shadow-sm"
                                     style="width: 100px; height: 100px;">
                            @else
                                <div class="bg-light border rounded-circle d-flex justify-content-center align-items-center"
                                     style="width: 100px; height: 100px;">
                                    <i class="bx bx-user text-muted fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Tên bác sĩ:</strong> {{ $leave->doctor->user->full_name ?? 'Không rõ' }}</li>
                                <li class="list-group-item"><strong>Phòng làm việc:</strong> {{ $leave->doctor->room->name ?? 'Không có' }}</li>
                                <li class="list-group-item"><strong>Thời gian nghỉ:</strong>
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}
                                    đến
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}
                                </li>
                                <li class="list-group-item"><strong>Lý do nghỉ:</strong> {{ $leave->reason ?? 'Không có lý do' }}</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Form cập nhật --}}
                    <form action="{{ route('admin.doctor_leaves.update', $leave->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="approved" class="form-label fw-semibold">Trạng thái duyệt</label>
                            <select id="approved" name="approved" class="form-select">
                                <option value="0" {{ $leave->approved == 0 ? 'selected' : '' }}>Chưa duyệt</option>
                                <option value="1" {{ $leave->approved == 1 ? 'selected' : '' }}>Đã duyệt</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.doctor_leaves.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bx bx-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
    });
</script>
@endsection
