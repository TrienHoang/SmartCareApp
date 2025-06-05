@extends('admin.dashboard')
@section('title', 'Thêm Đơn thuốc')

@section('content')
<div class="container">
    <h2>Thêm Toa Thuốc</h2>

    <form action="" method="POST">
        @csrf

        <div class="mb-3">
            <label for="medical_record_id">Hồ sơ bệnh án</label>
            <select name="medical_record_id" id="medical_record_id" class="form-control" required>
                <option value="">-- Chọn hồ sơ --</option>
                @foreach($medicalRecords as $record)
                    <option value="{{ $record->id }}">
                        {{ $record->id }} - {{ $record->appointment->patient->full_name ?? 'Bệnh nhân' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="prescribed_at">Ngày kê toa</label>
            <input type="datetime-local" name="prescribed_at" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="notes">Ghi chú</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <hr>
        <h4>Thuốc được kê</h4>
        <div id="medicine-list">
            <div class="medicine-item border p-2 mb-2">
                <select name="medicines[0][medicine_id]" class="form-control mb-2" required>
                    <option value="">-- Chọn thuốc --</option>
                    @foreach($medicines as $med)
                        <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->unit }})</option>
                    @endforeach
                </select>

                <input type="number" name="medicines[0][quantity]" class="form-control mb-2" placeholder="Số lượng" min="1" required>

                <textarea name="medicines[0][usage_instructions]" class="form-control" placeholder="Hướng dẫn sử dụng"></textarea>
            </div>
        </div>

        <button type="button" onclick="addMedicine()" class="btn btn-sm btn-secondary mb-3">+ Thêm thuốc</button>

        <br>
        <button type="submit" class="btn btn-primary">Lưu Toa Thuốc</button>
    </form>
</div>

<script>
    let medicineIndex = 1;
    function addMedicine() {
        const container = document.getElementById('medicine-list');
        const item = `
            <div class="medicine-item border p-2 mb-2">
                <select name="medicines[${medicineIndex}][medicine_id]" class="form-control mb-2" required>
                    <option value="">-- Chọn thuốc --</option>
                    @foreach($medicines as $med)
                        <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->unit }})</option>
                    @endforeach
                </select>

                <input type="number" name="medicines[${medicineIndex}][quantity]" class="form-control mb-2" placeholder="Số lượng" min="1" required>

                <textarea name="medicines[${medicineIndex}][usage_instructions]" class="form-control" placeholder="Hướng dẫn sử dụng"></textarea>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', item);
        medicineIndex++;
    }
</script>
@endsection