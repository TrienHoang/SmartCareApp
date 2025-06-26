@extends('admin.dashboard')
@section('title', 'Chỉnh Sửa Lịch Làm Việc')
@section('content')
<div class="container">
    <h1 class="mb-4">Chỉnh Sửa Lịch Làm Việc</h1>
    <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" id="formChinhSuaLich">
        @csrf
        @method('PUT')

        {{-- Chọn bác sĩ --}}
        <div class="mb-3">
            <label for="bac_si_id" class="form-label">Chọn bác sĩ</label>
            <select class="form-select" id="bac_si_id" name="doctor_id">
                <option value="">-- Vui lòng chọn bác sĩ --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ $doctor->id == $schedule->doctor_id ? 'selected' : '' }}>
                        {{ $doctor->user->full_name }}
                    </option>
                @endforeach
            </select>
            @error('doctor_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Ngày --}}
        <div class="mb-3">
            <label for="ngay" class="form-label">Ngày làm việc</label>
            <input type="date" class="form-control" id="ngay" name="day" value="{{ old('day', $schedule->day) }}">
            @error('day')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Thứ trong tuần --}}
        <div class="mb-3">
            <label class="form-label">Thứ</label>
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
            <input type="hidden" name="day_of_week" id="thu_gui" value="{{ old('day_of_week', $schedule->day_of_week) }}">
            @error('day_of_week')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Giờ bắt đầu --}}
        <div class="mb-3">
            <label for="gio_bat_dau" class="form-label">Giờ bắt đầu</label>
            <input type="time" class="form-control" id="gio_bat_dau" name="start_time" value="{{ old('start_time', $schedule->start_time) }}">
            @error('start_time')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Giờ kết thúc --}}
        <div class="mb-3">
            <label for="gio_ket_thuc" class="form-label">Giờ kết thúc</label>
            <input type="time" class="form-control" id="gio_ket_thuc" name="end_time" value="{{ old('end_time', $schedule->end_time) }}">
            @error('end_time')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Cập nhật lịch</button>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputNgay = document.getElementById('ngay');
        const selectThuHienThi = document.getElementById('thu_hien_thi');
        const inputThuGui = document.getElementById('thu_gui');
        const form = document.getElementById('formChinhSuaLich');

        const inputGioBatDau = document.getElementById('gio_bat_dau');
        const inputGioKetThuc = document.getElementById('gio_ket_thuc');

        const banDoThu = {
            0: 'Thứ hai',
            1: 'Thứ ba',
            2: 'Thứ tư',
            3: 'Thứ năm',
            4: 'Thứ sáu',
            5: 'Thứ bảy',
            6: 'Chủ nhật'
        };

        function capNhatThuTuNgay(dateStr) {
            const ngay = new Date(dateStr);
            if (!isNaN(ngay)) {
                const soThu = ngay.getDay();
                const tenThu = banDoThu[soThu];
                selectThuHienThi.value = tenThu;
                inputThuGui.value = tenThu;
            }
        }

        capNhatThuTuNgay(inputNgay.value); // Gán giá trị ban đầu nếu có

        inputNgay.addEventListener('change', function () {
            const ngayDuocChon = new Date(this.value);
            let canhBaoChuNhat = document.getElementById('canh-bao-chu-nhat');

            if (!isNaN(ngayDuocChon)) {
                const soThu = ngayDuocChon.getDay();
                const tenThu = banDoThu[soThu];

                selectThuHienThi.value = tenThu;
                inputThuGui.value = tenThu;

                // Chủ Nhật
                if (tenThu === 'Sunday') {
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

            // Xác nhận nếu ca làm > 8 tiếng
            const batDau = inputGioBatDau.value;
            const ketThuc = inputGioKetThuc.value;
            if (batDau && ketThuc) {
                const [gioBD, phutBD] = batDau.split(':').map(Number);
                const [gioKT, phutKT] = ketThuc.split(':').map(Number);

                const tongPhut = (gioKT * 60 + phutKT) - (gioBD * 60 + phutBD);
                if (tongPhut > 480) {
                    const xacNhan = confirm("Ca làm của bác sĩ này đã vượt quá 8 tiếng. Bạn có chắc chắn muốn tiếp tục?");
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
