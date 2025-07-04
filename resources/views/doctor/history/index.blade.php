@extends('doctor.dashboard')

@section('content')
    <div class="container">
        <h4 class="mb-4">🩺 Lịch sử khám bệnh</h4>

        <form method="GET" class="mb-3">
            <input type="text" name="keyword" class="form-control" placeholder="Tìm bệnh nhân theo tên, email, SĐT..."
                value="{{ request('keyword') }}">
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Bệnh nhân</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Dịch vụ</th>
                    <th>Ngày khám</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->patient->full_name ?? 'N/A' }}</td>
                        <td>{{ $appointment->patient->email ?? 'N/A' }}</td>
                        <td>{{ $appointment->patient->phone ?? 'N/A' }}</td>
                        <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->appointment_time->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('doctor.history.show', $appointment->id) }}" class="btn btn-sm btn-primary">Chi
                                tiết</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có lịch sử khám bệnh.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $appointments->withQueryString()->links() }}
    </div>
@endsection