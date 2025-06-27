@extends('admin.dashboard')
@section('title', 'Tạo Lịch làm việc')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-calendar-plus me-2 text-primary" style="font-size: 24px;"></i>
                        <h2 class="content-header-title mb-0">Tạo mới Lịch làm việc</h2>
                    </div>
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="">
                                    <a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none">
                                        Trang chủ >
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('admin.schedules.index') }}" class="text-decoration-none">
                                        Lịch làm việc >
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Tạo mới</li>
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
                <!-- Form Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="avatar-wrapper me-3">
                                <div class="avatar avatar-lg bg-primary">
                                    <i class="bx bx-plus text-white" style="font-size: 20px;"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="card-title mb-1">Thông tin Lịch làm việc</h4>
                                <small class="text-muted">Tạo mới lịch làm việc cho bác sĩ</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bx bx-error-circle me-2" style="font-size: 18px;"></i>
                                    <strong>Vui lòng kiểm tra lại thông tin:</strong>
                                </div>
                                <ul class="mb-0 ps-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-error-circle me-2" style="font-size: 18px;"></i>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        <!-- Form -->
                        <form action="{{ route('admin.schedules.store') }}" method="POST" id="formLichLamViec" class="needs-validation" novalidate>
                            @csrf
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-lg-8">
                                    <div class="form-section mb-4">
                                        <div class="section-header mb-3">
                                            <h5 class="section-title">
                                                <i class="bx bx-info-circle text-primary me-2"></i>
                                                Thông tin cơ bản
                                            </h5>
                                        </div>
                                        <!-- Chọn bác sĩ -->
                                        <div class="form-group mb-3">
                                            <label for="bac_si_id" class="form-label d-flex align-items-center">
                                                <i class="bx bx-user me-2 text-muted"></i>
                                                Chọn bác sĩ <span class="text-danger ms-1">*</span>
                                            </label>
                                            <select class="form-select" id="bac_si_id" name="doctor_id" required>
                                                <option value="">-- Vui lòng chọn bác sĩ --</option>
                                                @foreach($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}">{{ $doctor->user->full_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('doctor_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Ngày -->
                                        <div class="form-group mb-3">
                                            <label for="ngay" class="form-label d-flex align-items-center">
                                                <i class="bx bx-calendar me-2 text-muted"></i>
                                                Ngày làm việc <span class="text-danger ms-1">*</span>
                                            </label>
                                            <input type="date" class="form-control @error('day') is-invalid @enderror" id="ngay" name="day" value="{{ old('day') }}" required>
                                            @error('day')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Thứ trong tuần -->
                                        <div class="form-group mb-3">
                                            <label class="form-label d-flex align-items-center">
                                                <i class="bx bx-calendar-week me-2 text-muted"></i>
                                                Thứ
                                            </label>
                                            <select class="form-select" id="thu_hien_thi" disabled>
                                                <option value="">-- Các ngày trong tuần --</option>
                                                <option value="Thứ hai">Thứ Hai</option>
                                                <option value="Thứ ba">Thứ Ba</option>
                                                <option value="Thứ tư">Thứ Tư</option>
                                                <option value="Thứ năm">Thứ Năm</option>
                                                <option value="Thứ sáu">Thứ Sáu</option>
                                                <option value="Thứ bảy">Thứ Bảy</option>
                                                <option value="Chủ nhật">Chủ Nhật</option>
                                            </select>
                                            <input type="hidden" name="day_of_week" id="thu_gui">
                                            @error('day_of_week')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Giờ bắt đầu -->
                                        <div class="form-group mb-3">
                                            <label for="gio_bat_dau" class="form-label d-flex align-items-center">
                                                <i class="bx bx-time me-2 text-muted"></i>
                                                Giờ bắt đầu <span class="text-danger ms-1">*</span>
                                            </label>
                                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="gio_bat_dau" name="start_time" value="{{ old('start_time') }}" required>
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Giờ kết thúc -->
                                        <div class="form-group mb-3">
                                            <label for="gio_ket_thuc" class="form-label d-flex align-items-center">
                                                <i class="bx bx-time-five me-2 text-muted"></i>
                                                Giờ kết thúc <span class="text-danger ms-1">*</span>
                                            </label>
                                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="gio_ket_thuc" name="end_time" value="{{ old('end_time') }}" required>
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Right Column -->
                                <div class="col-lg-4">
                                    <div class="sticky-sidebar">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-primary text-white mb-2">
                                                <h6 class="mb-0">
                                                    <i class="bx bx-cog me-2"></i>
                                                    Hành động
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="action-buttons mt-4">
                                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                                        <i class="bx bx-save me-2"></i>
                                                        Tạo lịch
                                                    </button>
                                                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary w-100">
                                                        <i class="bx bx-x me-2"></i>
                                                        Hủy bỏ
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Quick Tips -->
                                        <div class="card border-0 shadow-sm mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 text-muted">
                                                    <i class="bx bx-bulb me-2"></i>
                                                    Mẹo sử dụng
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled mb-0 quick-tips">
                                                    <li><i class="bx bx-check text-success me-2"></i>Chọn đúng bác sĩ và ngày làm việc</li>
                                                    <li><i class="bx bx-check text-success me-2"></i>Không chọn Chủ Nhật hoặc ngày quá khứ</li>
                                                    <li><i class="bx bx-check text-success me-2"></i>Ca làm không nên vượt quá 8 tiếng</li>
                                                </ul>
                                            </div>
                                        </div>
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

@push('styles')
<style>
    .form-section {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid #e3e6f0;
        margin-bottom: 1.5rem;
    }
    .section-header {
        border-bottom: 2px solid #f8f9fc;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .section-title {
        color: #5a5c69;
        font-weight: 600;
        margin: 0;
    }
    .sticky-sidebar {
        position: sticky;
        top: 2rem;
    }
    .quick-tips li {
        padding: 0.25rem 0;
        font-size: 0.875rem;
        color: #6c757d;
    }
    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .btn-outline-secondary:hover {
        transform: translateY(-1px);
    }
    @media (max-width: 991px) {
        .sticky-sidebar {
            position: static;
            margin-top: 2rem;
        }
    }
    @media (max-width: 768px) {
        .form-section {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputNgay = document.getElementById('ngay');
        const selectThuHienThi = document.getElementById('thu_hien_thi');
        const inputThuGui = document.getElementById('thu_gui');
        const form = document.getElementById('formLichLamViec');

        const inputGioBatDau = document.getElementById('gio_bat_dau');
        const inputGioKetThuc = document.getElementById('gio_ket_thuc');

        const banDoThu = {
            0: 'Chủ nhật',
            1: 'Thứ hai',
            2: 'Thứ ba',
            3: 'Thứ tư',
            4: 'Thứ năm',
            5: 'Thứ sáu',
            6: 'Thứ bảy'
        };

        inputNgay.addEventListener('change', function () {
            const ngayDuocChon = new Date(this.value);
            let canhBaoChuNhat = document.getElementById('canh-bao-chu-nhat');

            if (!isNaN(ngayDuocChon)) {
                const soThu = ngayDuocChon.getDay();
                const tenThu = banDoThu[soThu];

                inputThuGui.value = tenThu;
                selectThuHienThi.value = tenThu;

                if (tenThu === 'Chủ nhật') {
                    if (!canhBaoChuNhat) {
                        canhBaoChuNhat = document.createElement('small');
                        canhBaoChuNhat.id = 'canh-bao-chu-nhat';
                        canhBaoChuNhat.classList.add('text-danger', 'd-block', 'mt-1');
                        canhBaoChuNhat.innerText = 'Bác sĩ không làm việc vào Chủ Nhật.';
                        inputNgay.parentNode.appendChild(canhBaoChuNhat);
                    }
                } else {
                    if (canhBaoChuNhat) canhBaoChuNhat.remove();
                }

                if (!inputGioBatDau.value) {
                    inputGioBatDau.value = '08:00';
                }
                if (!inputGioKetThuc.value) {
                    inputGioKetThuc.value = '17:00';
                }
            }
        });

        form.addEventListener('submit', function (e) {
            const ngayDuocChon = new Date(inputNgay.value);
            const homNay = new Date();
            homNay.setHours(0, 0, 0, 0);

            if (!isNaN(ngayDuocChon)) {
                if (ngayDuocChon.getDay() === 0) {
                    e.preventDefault();
                    alert("Không thể tạo lịch vào Chủ Nhật. Vui lòng chọn ngày khác.");
                    return;
                }

                if (ngayDuocChon < homNay) {
                    e.preventDefault();
                    alert("Không thể tạo lịch cho ngày trong quá khứ.");
                    return;
                }
            }

            const batDau = inputGioBatDau.value;
            const ketThuc = inputGioKetThuc.value;

            if (batDau && ketThuc) {
                const [batDauGio, batDauPhut] = batDau.split(':').map(Number);
                const [ketThucGio, ketThucPhut] = ketThuc.split(':').map(Number);

                const tongPhut = (ketThucGio * 60 + ketThucPhut) - (batDauGio * 60 + batDauPhut);

                if (tongPhut > 480) {
                    const xacNhan = confirm("Ca làm của bác sĩ này đã vượt quá 8 tiếng trong một ngày. Bạn có chắc chắn muốn tiếp tục?");
                    if (!xacNhan) {
                        e.preventDefault();
                        return;
                    }
                }
            }
        });
    });
</script>
@endsection

