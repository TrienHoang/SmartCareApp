@extends('admin.dashboard')
@section('title', 'Quản lý đơn thuốc')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Danh sách đơn thuốc</h3>
                    @can('create_prescriptions')
                    <a href="{{ route('admin.prescriptions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo đơn thuốc mới
                    </a>
                    @endcan
                </div>
                <div class="card-body">
                    @if(session('success'))
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
                                    <td>{{ $prescription->id }}</td>
                                    <td>
                                        <strong>{{ $prescription->medicalRecord->appointment->patient->full_name }}</strong><br>
                                        <small class="text-muted">{{ $prescription->medicalRecord->appointment->patient->phone }}</small>
                                    </td>
                                    <td>
                                        {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}<br>
                                        <small class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</small>
                                    </td>
                                    <td>{{ $prescription->formatted_date }}</td>
                                    <td>{{ $prescription->prescriptionItems->count() }} loại</td>
                                <td>
                                    {{ $prescription->prescriptionItems->sum('quantity') }} viên/chai/gói
                                </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.prescriptions.show', $prescription->id) }}" class="btn btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                          
                                            <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}" class="btn btn-warning" title="Chỉnh sửa">
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
                        {{ $prescriptions->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

