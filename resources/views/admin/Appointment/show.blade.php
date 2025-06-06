@extends('admin.dashboard')
@section('title', 'Chi tiết Lịch hẹn khám')
@section('content')
    <div class="container">
        <h1>Chi tiết Lịch hẹn khám #{{ $appointment->id }}</h1>

        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary mb-3">
            <i class="bx bx-arrow-back"></i> Quay lại danh sách
        </a>

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Bệnh nhân</th>
                    <td>{{ $appointment->patient->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Bác sĩ</th>
                    <td>{{ $appointment->doctor->user->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Dịch vụ khám</th>
                    <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Phòng khám</th>
                    <td>{{ $appointment->doctor->room->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Ngày giờ khám</th>
                    <td>{{ $appointment->formatted_time }}</td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        @if ($appointment->status === 'pending')
                            <span class="badge bg-warning">Chờ xác nhận</span>
                        @elseif ($appointment->status === 'confirmed')
                            <span class="badge bg-success">Đã xác nhận</span>
                        @elseif ($appointment->status === 'cancelled')
                            <span class="badge bg-danger">Đã hủy</span>
                        @else
                            <span class="badge bg-secondary">{{ $appointment->status }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Ghi chú</th>
                    <td>{{ $appointment->reason ?? 'Không có ghi chú' }}</td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td>{{ $appointment->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Cập nhật lần cuối</th>
                    <td>{{ $appointment->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
