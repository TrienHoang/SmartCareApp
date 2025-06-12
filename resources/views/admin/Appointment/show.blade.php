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
                @php
                    use Carbon\Carbon;

                    $now = Carbon::now();
                    $appointmentTime = Carbon::parse($appointment->appointment_time);
                    $shouldShowEndTime = $appointmentTime->isPast() || $appointment->status === 'completed';
                @endphp

                @if ($shouldShowEndTime)
                    <tr>
                        <th>Thời gian kết thúc Dự kiến</th>
                        <td>{{ $appointment->end_time->format('d-m-Y H:i') }}</td>
                    </tr>
                @endif


                <tr>
                    <th>Trạng thái</th>
                    <td>
                        @php
                            $statusConfig = [
                                'pending' => [
                                    'color' => 'warning',
                                    'text' => 'Chờ xác nhận',
                                    'icon' => 'bx-time',
                                ],
                                'confirmed' => [
                                    'color' => 'info',
                                    'text' => 'Đã xác nhận',
                                    'icon' => 'bx-check',
                                ],
                                'completed' => [
                                    'color' => 'success',
                                    'text' => 'Hoàn thành',
                                    'icon' => 'bx-check-double',
                                ],
                                'cancelled' => ['color' => 'danger', 'text' => 'Đã hủy', 'icon' => 'bx-x'],
                            ];
                            $config = $statusConfig[$appointment->status] ?? [
                                'color' => 'secondary',
                                'text' => $appointment->status,
                                'icon' => 'bx-help',
                            ];
                        @endphp

                        <span class="badge bg-{{ $config['color'] }} status-badge">
                            <i class="bx {{ $config['icon'] }}"></i> {{ $config['text'] }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Ghi chú</th>
                    <td>{{ $appointment->reason ?? 'Không có ghi chú' }}</td>
                </tr>
                <tr>
                    <th>Lí do hủy</th>
                    <th>{{ $appointment->cancel_reason ?? 'Không' }}</th>
                </tr>
                <tr>
                    <th>Note sau khi hoàn thành</th>
                    <td>
                        {{ optional($appointment->logs->where('status_after', 'completed')->sortByDesc('change_time')->first())->note ??
                            'Không có ghi chú' }}
                    </td>
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

        <h4>Lịch sử cập nhật</h4>

        @if ($appointment->logs->isEmpty())
            <p class="text-muted">Không có lịch sử cập nhật.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Người thay đổi</th>
                            <th>Thời gian</th>
                            <th>Ghi chú thay đổi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointment->logs->sortByDesc('change_time') as $log)
                            <tr>
                                <td>{{ $log->user->full_name ?? 'N/A' }}</td>
                                <td>{{ $log->change_time->format('d/m/Y H:i') }}</td>
                                <td>
                                    <pre class="mb-0">{{ $log->note ?? 'Không có ghi chú' }}</pre>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
