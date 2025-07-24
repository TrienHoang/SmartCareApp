@extends('doctor.dashboard')

@section('title', 'Danh sách bác sĩ')

@section('content')
    <div class="container-fluid py-4 animate__animated animate__fadeIn">

        {{-- Header --}}
        <div class="content-header row">
            <div class="content-header-left col-md-12 mb-3">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-primary me-3">
                        <i class="bx bx-user text-white fs-4"></i>
                    </div>
                    <div>
                        <h2 class="content-header-title mb-0 text-primary fw-bold">Danh sách bác sĩ</h2>
                        <p class="text-muted mb-0">Hiển thị tất cả bác sĩ trong hệ thống</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Form tìm kiếm --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('doctor.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Tên bác sĩ</label>
                            <input type="text" name="name" class="form-control" value="{{ request('name') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Chuyên môn</label>
                            <input type="text" name="specialization" class="form-control"
                                value="{{ request('specialization') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Khoa</label>
                            <select name="department_id" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phòng</label>
                            <select name="room_id" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('doctor.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync-alt me-1"></i> Đặt lại
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bảng bác sĩ --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bx bx-list-ul me-2"></i> Danh sách bác sĩ</h5>
            </div>

            <div class="card-body p-0">
                @if ($doctors->isEmpty())
                    <div class="alert alert-info m-4">Không tìm thấy bác sĩ nào phù hợp.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Ảnh</th>
                                    <th>Họ tên</th>
                                    <th>Email</th> {{-- thêm --}}
                                    <th>Điện thoại</th> {{-- thêm --}}
                                    <th>Địa chỉ</th> {{-- thêm --}}
                                    <th>Chuyên môn</th>
                                    <th>Khoa</th>
                                    <th>Phòng</th>
                                    <th>Tiểu sử</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doctors as $index => $doctor)
                                    <tr>
                                        <td>{{ $loop->iteration + ($doctors->currentPage() - 1) * $doctors->perPage() }}</td>
                                        <td>
                                            <img src="{{ $doctor->user->avatar_url ?? asset('images/default-avatar.png') }}"
                                                class="rounded-circle" width="50" height="50" alt="Avatar">
                                        </td>
                                        <td>{{ $doctor->user->full_name }}</td>
                                        <td>{{ $doctor->user->email ?? '—' }}</td> {{-- email --}}
                                        <td>{{ $doctor->user->phone ?? '—' }}</td> {{-- phone --}}
                                        <td>{{ $doctor->user->address ?? '—' }}</td> {{-- address --}}
                                        <td>{{ $doctor->specialization }}</td>
                                        <td>{{ $doctor->department->name ?? '—' }}</td>
                                        <td>{{ $doctor->room->name ?? '—' }}</td>
                                        <td class="text-start px-2">{{ \Illuminate\Support\Str::limit($doctor->biography, 60) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('doctor.show', $doctor->id) }}" class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                @endif
            </div>

            @if(method_exists($doctors, 'links'))
                <div class="card-footer d-flex justify-content-end">
                    {{ $doctors->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tooltip Bootstrap 5
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush