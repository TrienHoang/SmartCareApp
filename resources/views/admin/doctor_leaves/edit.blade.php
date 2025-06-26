@extends('admin.dashboard')

@section('title', 'Cập nhật ngày nghỉ Bác sĩ')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <!-- Enhanced Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-8 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary me-3 ">
                            <i class="bx bx-calendar-check text-white "></i> {{-- Icon for Doctor Leave Edit --}}
                        </div>
                        <div>
                            <h2 class="content-header-title mb-0 text-primary fw-bold">Cập nhật Ngày nghỉ Bác sĩ</h2>
                            <p class="text-muted mb-0">Chỉnh sửa trạng thái và thông tin yêu cầu nghỉ</p>
                        </div>
                    </div>
                    <div class="breadcrumb-wrapper col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">Trang chủ</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.doctor_leaves.index') }}" class="text-decoration-none">Ngày nghỉ Bác sĩ</a>
                                </li>
                                <li class="breadcrumb-item active text-primary fw-semibold">Cập nhật</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-4 col-12 text-md-end">
            <div class="form-group breadcrum-right">
                <a href="{{ route('admin.doctor_leaves.index') }}"
                    class="btn btn-secondary btn-lg waves-effect waves-light shadow-lg">
                    <i class="bx bx-arrow-back me-2"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex align-items-center rounded-top-4 p-4">
                    <i class="bx bx-calendar-check me-2 fs-5"></i>
                    <h5 class="mb-0">Cập nhật ngày nghỉ bác sĩ</h5>
                </div>
                <div class="card-body p-4">

                    {{-- Thông tin bác sĩ --}}
                    <div class="row align-items-center mb-4 border-bottom pb-4"> {{-- Added border-bottom for separation --}}
                        <div class="col-md-3 text-center mb-3 mb-md-0"> {{-- Added mb-3 for mobile spacing --}}
                            @if($leave->doctor->user->avatar)
                                <img src="{{ Storage::url($leave->doctor->user->avatar) }}"
                                     alt="Avatar"
                                     class="img-fluid rounded-circle border border-3 border-primary shadow-sm" {{-- img-fluid for responsiveness --}}
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-light border rounded-circle d-flex justify-content-center align-items-center"
                                     style="width: 100px; height: 100px;">
                                    <i class="bx bx-user text-muted fs-1"></i>
                                </div>
                            @endif
                            <h6 class="mt-2 mb-0 fw-bold">{{ $leave->doctor->user->full_name ?? 'Không rõ' }}</h6>
                            <small class="text-muted">{{ $leave->doctor->specialization ?? 'Chuyên môn' }}</small>
                        </div>
                        <div class="col-md-9">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0"><strong>Tên bác sĩ:</strong> {{ $leave->doctor->user->full_name ?? 'Không rõ' }}</li>
                                <li class="list-group-item px-0"><strong>Phòng làm việc:</strong> {{ $leave->doctor->room->name ?? 'Không có' }}</li>
                                <li class="list-group-item px-0"><strong>Thời gian nghỉ:</strong>
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}
                                    đến
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}
                                </li>
                                <li class="list-group-item px-0"><strong>Lý do nghỉ:</strong> {{ $leave->reason ?? 'Không có lý do' }}</li>
                                <li class="list-group-item px-0"><strong>Ngày tạo yêu cầu:</strong> {{ \Carbon\Carbon::parse($leave->created_at)->format('H:i d/m/Y') }}</li> {{-- Added \Carbon\Carbon::parse() here --}}
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

                        <div class="d-flex justify-content-center gap-3"> {{-- Changed to center buttons --}}
                            <button type="submit" class="btn btn-success btn-lg px-5 py-3 rounded-3 shadow-sm">
                                <i class="bx bx-save me-2"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.doctor_leaves.index') }}" class="btn btn-secondary btn-lg px-5 py-3 rounded-3 shadow-sm">
                                <i class="bx bx-arrow-back me-2"></i> Quay lại
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- No specific scripts needed here as tooltips are initialized globally and feather icons are not used --}}
@endpush
