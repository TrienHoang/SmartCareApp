@extends('admin.dashboard')
@section('title', 'Chỉnh sửa Lịch làm việc')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar-edit me-2 text-primary" style="font-size: 24px;"></i>
                        <h2 class="content-header-title mb-0">Chỉnh sửa Lịch làm việc</h2>
                    </div>
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li><a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">Trang chủ ></a></li>
                                <li><a href="{{ route('admin.schedules.index') }}" class="text-decoration-none">Lịch làm việc ></a></li>
                                <li class="breadcrumb-item active">Chỉnh sửa</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="avatar-wrapper me-3">
                                <div class="avatar avatar-lg bg-primary">
                                    <i class="bx bx-edit text-white" style="font-size: 20px;"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="card-title mb-1">Thông tin Lịch làm việc</h4>
                                <small class="text-muted">Cập nhật thông tin lịch làm việc của bác sĩ</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
                        @endif

                        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label>Bác sĩ</label>
                                        <select class="form-select" name="doctor_id">
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}" {{ $doctor->id == $schedule->doctor_id ? 'selected' : '' }}>{{ $doctor->user->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Ngày làm việc</label>
                                        <input type="date" class="form-control" name="day" id="ngay" value="{{ old('day', \Carbon\Carbon::parse($schedule->day)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Thứ</label>
                                        <input type="text" class="form-control" id="thu_hien_thi" disabled>
                                        <input type="hidden" name="day_of_week" id="thu_gui" value="{{ old('day_of_week', $schedule->day_of_week) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Giờ bắt đầu</label>
                                        <input type="time" class="form-control" name="start_time" value="{{ old('start_time', $schedule->start_time) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Giờ kết thúc</label>
                                        <input type="time" class="form-control" name="end_time" value="{{ old('end_time', $schedule->end_time) }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card p-3">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Cập nhật</button>
                                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputNgay = document.getElementById('ngay');
        const selectThu = document.getElementById('thu_hien_thi');
        const inputThu = document.getElementById('thu_gui');
        const banDoThu = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];

        function capNhatThu(ngayStr) {
            const d = new Date(ngayStr);
            if (!isNaN(d)) {
                const thu = d.getDay();
                const tenThu = banDoThu[thu];
                selectThu.value = tenThu;
                inputThu.value = tenThu;
            }
        }

        inputNgay.addEventListener('change', function () {
            capNhatThu(this.value);
        });

        capNhatThu(inputNgay.value);
    });
</script>
@endsection
