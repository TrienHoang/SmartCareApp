@extends('admin.dashboard')
@section('title', 'Danh sách lịch hẹn khám')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Appointments /</span> Danh sách lịch hẹn khám
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Danh sách lịch hẹn</h5>
            </div>

            @if (session('success'))
                <div class="alert alert-success m-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger m-3">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th>STT</th>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ</th>
                            <th>Dịch vụ</th>
                            <th>Phòng</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->id }}</td>
                                <td>{{ $appointment->patient->full_name ?? 'N/A' }}</td>
                                <td>{{ $appointment->doctor->user->full_name ?? 'N/A' }}</td>
                                <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                <td>{{ $appointment->doctor->room->name ?? 'N/A' }}</td>
                                <td>{{ $appointment->formatted_time }}</td>
                                <td>
                                    @php
                                        $statusColor = match ($appointment->status) {
                                            'pending' => 'secondary',
                                            'confirmed' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'dark',
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $statusColor }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{-- <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-show"></i>
                                    </a> --}}

                                    @if ($appointment->status === 'pending')
                                        {{-- <a href="{{ route('admin.appointments.confirm', $appointment->id) }}" class="btn btn-sm btn-success">
                                            <i class="bx bx-check-circle"></i>
                                        </a>
                                        <a href="{{ route('admin.appointments.cancel', $appointment->id) }}" class="btn btn-sm btn-danger"
                                           onclick="return confirm('Bạn có chắc muốn hủy lịch hẹn này?')">
                                            <i class="bx bx-x-circle"></i>
                                        </a> --}}
                                    @elseif ($appointment->status === 'confirmed')
                                        {{-- <a href="{{ route('admin.appointments.complete', $appointment->id) }}" class="btn btn-sm btn-success">
                                            <i class="bx bx-check-double"></i>
                                        </a> --}}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Không có lịch hẹn nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex justify-content-end">
                {{ $appointments->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
