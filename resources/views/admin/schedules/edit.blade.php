@extends('admin.dashboard')

@section('title', 'Chỉnh sửa Lịch làm việc')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="content-header row">
        <div class="content-header-left col-md-12">
            <h2 class="mb-2">
                <i class="bx bx-calendar-edit text-primary"></i> Chỉnh sửa Lịch làm việc
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.schedules.index') }}">Lịch làm việc</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thông tin lịch làm việc</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Bác sĩ -->
                        <div class="col-md-6 mb-3">
                            <label>Bác sĩ</label>
                            <select name="doctor_id" class="form-select" required>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $schedule->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Ngày -->
                        <div class="col-md-6 mb-3">
                            <label>Ngày làm việc</label>
                            <input type="date" class="form-control" id="ngay" name="day" value="{{ old('day', \Carbon\Carbon::parse($schedule->day)->format('Y-m-d')) }}">
                        </div>

                        <!-- Thứ -->
                        <div class="col-md-6 mb-3">
                            <label>Thứ</label>
                            <input type="text" class="form-control" id="thu_hien_thi" disabled>
                            <input type="hidden" name="day_of_week" id="thu_gui" value="{{ old('day_of_week', $schedule->day_of_week) }}">
                        </div>

                        <!-- Giờ -->
                        <div class="col-md-6 mb-3">
                            <label>Giờ bắt đầu</label>
                            <input type="time" class="form-control" name="start_time" value="{{ old('start_time', $schedule->start_time) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Giờ kết thúc</label>
                            <input type="time" class="form-control" name="end_time" value="{{ old('end_time', $schedule->end_time) }}" required>
                        </div>

                        <!-- Nút -->
                        <div class="col-12">
                            <button class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </div>
                </form>
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

        capNhatThu(inputNgay.value); // Gọi khi load lại trang
    });
</script>
@endsection
