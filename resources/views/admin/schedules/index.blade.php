@extends('admin.dashboard')
@section('title', 'Quản lý lịch làm việc bác sĩ')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">

    {{-- Flash messages --}}
    @foreach (['success', 'error', 'info'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- Tiêu đề + Bộ lọc --}}
    <div class="card bg-white border-0 shadow mb-4 rounded-xl">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h5 class="text-primary m-0 d-flex align-items-center gap-2">
                <i data-feather="calendar"></i> Danh sách lịch làm việc
            </h5>
            <form action="{{ route('admin.schedules.index') }}" method="get" class="d-flex gap-2 flex-wrap align-items-end">
                <div>
                    <label class="form-label small mb-0">Tên bác sĩ</label>
                    <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm theo tên..." value="{{ request('keyword') }}">
                </div>
                <div>
        <label class="form-label small mb-0">Từ giờ</label>
            <input type="time" name="start_time" class="form-control form-control-sm" value="{{ request('start_time') }}">
        </div>
        <div>
            <label class="form-label small mb-0">Đến giờ</label>
            <input type="time" name="end_time" class="form-control form-control-sm" value="{{ request('end_time') }}">
        </div>

                <div>
                    <label class="form-label small mb-0">Thứ</label>
                    <select name="day_of_week" class="form-select form-select-sm">
                        <option value="">-- Tất cả --</option>
                        @foreach(['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'] as $thu)
                            <option value="{{ $thu }}" {{ request('day_of_week') === $thu ? 'selected' : '' }}>{{ $thu }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-outline-primary" id="btnSearch">
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
                            <tr>
                                <td>{{ $schedule->id }}</td>
                                <td>{{ $schedule->doctor->user->full_name ?? 'Không rõ' }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->day)->format('d/m/Y') }}</td>
                                <td><span class="badge bg-secondary">{{ $schedule->day_of_week }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.schedules.show', $schedule->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Chi tiết">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xoá lịch này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Xoá">
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
    .card {
        border-radius: 12px;
    }
    .table th {
        background-color: #e3f2fd;
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

        // Tooltip
        [...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach(el => {
            new bootstrap.Tooltip(el);
        });

        // Spinner khi tìm kiếm
        document.getElementById('btnSearch')?.addEventListener('click', function () {
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang tìm...';
        });
    });
</script>
@endsection
