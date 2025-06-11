@extends('admin.dashboard')
@section('title', 'Quản lý đơn thuốc')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tìm kiếm và lọc đơn thuốc</h3>
                    </div>
                    <div class="card-body">
                        <form id="search-form" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tên bệnh nhân</label>
                                        <input type="text" name="patient_name" class="form-control"
                                            value="{{ request('patient_name') }}" placeholder="Nhập tên bệnh nhân">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tên bác sĩ</label>
                                        <input type="text" name="doctor_name" class="form-control"
                                            value="{{ request('doctor_name') }}" placeholder="Nhập tên bác sĩ">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Từ ngày</label>
                                        <input type="date" name="date_from" class="form-control"
                                            value="{{ request('date_from') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Đến ngày</label>
                                        <input type="date" name="date_to" class="form-control"
                                            value="{{ request('date_to') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Thuốc</label>
                                        <select name="medicine_id" class="form-control select2">
                                            <option value="">-- Tất cả thuốc --</option>
                                            @foreach ($medicines as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ request('medicine_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Khoa/Phòng ban</label>
                                        <select name="department_id" class="form-control select2">
                                            <option value="">-- Tất cả khoa/phòng ban --</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <a href="{{ route('admin.prescriptions.index') }}"
                                                class="btn btn-secondary btn-block">
                                                <i class="fas fa-undo"></i> Đặt lại
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Danh sách đơn thuốc</h3>
                        @can('create_prescriptions')
                            <a href="{{ route('admin.prescriptions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo đơn thuốc mới
                            </a>
                        @endcan
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Bệnh nhân</th>
                                        <th>Bác sĩ kê đơn</th>
                                        <th>Ngày kê đơn</th>
                                        <th>Số loại thuốc</th>
                                        <th>Tổng số lượng</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($prescriptions as $prescription)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $prescription->medicalRecord->appointment->patient->full_name }}</strong><br>
                                                <small
                                                    class="text-muted">{{ $prescription->medicalRecord->appointment->patient->phone }}</small>
                                            </td>
                                            <td>
                                                {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}<br>
                                                <small
                                                    class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</small><br>
                                                <small
                                                    class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->department->name ?? 'Không có' }}</small>
                                            </td>
                                            <td>{{ $prescription->formatted_date }}</td>
                                            <td>{{ $prescription->prescriptionItems->count() }} loại</td>
                                            <td>{{ $prescription->prescriptionItems->sum('quantity') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.prescriptions.show', $prescription->id) }}"
                                                        class="btn btn-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}"
                                                        class="btn btn-warning" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không có đơn thuốc nào</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $prescriptions->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
