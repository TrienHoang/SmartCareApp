@extends('reception.dashboard')

@section('title', 'Lịch làm việc ngày ' . $selectedDate)

@section('content')
    <div class="container">
        <h3 class="mb-4">Lịch làm việc ngày {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</h3>

        <a href="{{ route('receptionist.doctors.index') }}" class="btn btn-secondary mb-3">
            ← Chọn ngày khác
        </a>

        @if ($schedules->isEmpty())
            <div class="alert alert-warning">
                Không có lịch làm việc nào cho ngày này.
            </div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Bác sĩ</th>
                        <th>Chuyên khoa</th>
                        <th>Ca làm</th>
                        <th>Phòng</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->doctor->user->full_name }}</td>
                            <td>{{ $schedule->doctor->department->name ?? '-' }}</td>
                            <td>{{ $schedule->shift->name ?? '-' }}</td>
                            <td>{{ $schedule->room->name ?? '-' }}</td>
                            <td>
                                @if (in_array($schedule->doctor_id, $leaves))
                                    <span class="badge bg-danger">Nghỉ</span>
                                @else
                                    <span class="badge bg-success">Làm việc</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
