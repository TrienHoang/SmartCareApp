@extends('admin.dashboard')
@section('title', 'Thêm Đơn thuốc')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Thêm Toa Thuốc</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.prescriptions.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <label for="medical_record_id" class="col-sm-2 col-form-label fw-bold">Hồ sơ bệnh án:</label>
                    <div class="col-sm-10">
                        <select name="medical_record_id" id="medical_record_id" class="form-select" required>
                            <option value="">-- Chọn hồ sơ --</option>
                            @foreach ($medicalRecords as $record)
                                <option value="{{ $record->id }}">
                                    {{ $record->id }} - {{ $record->appointment->patient->full_name ?? 'Bệnh nhân' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="prescribed_at" class="col-sm-2 col-form-label fw-bold">Ngày kê toa:</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" name="prescribed_at" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="notes" class="col-sm-2 col-form-label fw-bold">Ghi chú:</label>
                    <div class="col-sm-10">
                        <textarea name="notes" class="form-control" rows="3" placeholder="Nhập ghi chú nếu có..."></textarea>
                    </div>
                </div>

                <hr>
                <h5 class="fw-bold mb-3">Danh sách thuốc được kê</h5>
                <div id="medicine-list">
                    <div class="medicine-item border rounded p-3 mb-3">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Tên thuốc:</label>
                                <select name="medicines[0][medicine_id]" class="form-select" required>
                                    <option value="">-- Chọn thuốc --</option>
                                    @foreach ($medicines as $med)
                                        <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Số lượng:</label>
                                <input type="number" name="medicines[0][quantity]" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Hướng dẫn sử dụng:</label>
                                <textarea name="medicines[0][usage_instructions]" class="form-control" rows="1" placeholder="VD: Uống sau ăn..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="button" onclick="addMedicine()" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-plus-medical"></i> Thêm thuốc
                    </button>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary me-2">
                        <i class="bx bx-arrow-back"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Lưu Toa Thuốc
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Template thuốc ẩn --}}
<template id="medicine-template">
    <div class="medicine-item border rounded p-3 mb-3">
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label">Tên thuốc:</label>
                <select name="medicines[__index__][medicine_id]" class="form-select" required>
                    <option value="">-- Chọn thuốc --</option>
                    @foreach ($medicines as $med)
                        <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->unit }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label">Số lượng:</label>
                <input type="number" name="medicines[__index__][quantity]" class="form-control" min="1" required>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label">Hướng dẫn sử dụng:</label>
                <textarea name="medicines[__index__][usage_instructions]" class="form-control" rows="1" placeholder="VD: Uống 2 lần mỗi ngày..."></textarea>
            </div>
        </div>
    </div>
</template>

<script src="{{ asset('js/Prescription/create.js') }}"></script>
@endsection
