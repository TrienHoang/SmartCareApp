@extends('admin.dashboard')
@section('title', 'Quản lý lịch làm việc bác sĩ')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">

    {{-- Card tiêu đề --}}
    <div class="card bg-white border-0 shadow mb-4 rounded-xl">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h5 class="text-primary m-0 d-flex align-items-center gap-2">
                <i data-feather="calendar"></i> Danh sách lịch làm việc
            </h5>
            <form action="{{ route('admin.schedules.index') }}" method="get" class="d-flex gap-2 flex-wrap">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm theo tên bác sĩ..." value="{{ $keyword ?? '' }}">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i data-feather="search"></i> Tìm
                </button>
                <a href="{{ route('admin.schedules.create') }}" class="btn btn-sm btn-primary">
                    <i data-feather="plus-circle"></i> Thêm lịch
                </a>
            </form>
        </div>
    </div>

    {{-- Bảng dữ liệu --}}
    <div class="card bg-white border-0 shadow rounded-xl">
        <div class="card-body p-0">
            @if($schedules->isEmpty())
                <div class="alert alert-info m-4 text-center">
                    <i data-feather="info"></i> Không có lịch làm việc nào.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                       @php
                        $currentField = request('field');
                        $currentSort = request('sort', 'asc');

                        function sortLink($label, $field) {
                            $isCurrent = request('field') === $field;
                            $newSort = (request('sort') === 'asc' && $isCurrent) ? 'desc' : 'asc';
                            $icon = $isCurrent ? ($newSort === 'asc' ? '↑' : '↓') : '';
                            $query = request()->except(['page']);
                            $url = request()->fullUrlWithQuery(array_merge($query, ['field' => $field, 'sort' => $newSort]));
                            return "<a href=\"$url\" class=\"text-decoration-none text-dark\">$label $icon</a>";
                        }
                        @endphp

                        <thead class="table-light text-primary small fw-bold">
                            <tr>
                                <th>{!! sortLink('#', 'id') !!}</th>
                                <th>{!! sortLink('Bác sĩ', 'doctor_name') !!}</th>
                                <th>{!! sortLink('Ngày', 'day') !!}</th>
                                <th>{!! sortLink('Thứ', 'day_of_week') !!}</th>
                                <th>{!! sortLink('Giờ bắt đầu', 'start_time') !!}</th>
                                <th>{!! sortLink('Giờ kết thúc', 'end_time') !!}</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr class="align-middle">
                                <td>{{ $schedule->id }}</td>
                                <td>{{ $schedule->doctor->user->full_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') }}</td>
                                <td>{{ $schedule->day_of_week }}</td>
                                <td>{{ $schedule->start_time }}</td>
                                <td>{{ $schedule->end_time }}</td>
                                <td>
                                    <a href="{{ route('admin.schedules.show', $schedule->id) }}" class="btn btn-sm btn-info" title="Chi tiết">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch làm việc này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3 px-4">
                    {{ $schedules->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body {
        background-color: #f4f7f9;
    }

    .card {
        border-radius: 12px;
    }

    .table th {
        background-color: #e3f2fd;
    }

    .btn-primary {
        background-color: #2196f3;
        border-color: #2196f3;
    }

    .btn-outline-primary {
        color: #2196f3;
        border-color: #2196f3;
    }

    .btn-outline-primary:hover {
        background-color: #2196f3;
        color: white;
    }

    .btn-info {
        background-color: #00bcd4;
        border-color: #00bcd4;
    }

    .btn-warning {
        background-color: #ff9800;
        border-color: #ff9800;
    }

    .btn-danger {
        background-color: #f44336;
        border-color: #f44336;
    }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        feather.replace();
        [...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach(el => {
            new bootstrap.Tooltip(el);
        });
    });
</script>
@endsection
