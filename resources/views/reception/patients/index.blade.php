@extends('reception.dashboard')

@section('title', 'Quản lý bệnh nhân')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-primary mr-3">
                        <i class="fas fa-user-injured text-white"></i>
                    </div>
                    <div>
                        <h2 class="mb-0 text-primary font-weight-bold">Quản lý bệnh nhân</h2>
                        <p class="text-muted mb-0">Theo dõi và chỉnh sửa hồ sơ bệnh nhân</p>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-md-right">
                <a href="{{ route('receptionist.patients.create') }}" class="btn btn-gradient-primary btn-lg shadow">
                    <i class="fas fa-plus mr-1"></i> Tạo hồ sơ bệnh nhân
                </a>
            </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-body">
                {{-- Form tìm kiếm --}}
                <form action="{{ route('receptionist.patients.index') }}" method="GET" class="mb-4">
                    <div class="form-row align-items-end">
                        <div class="col-md-4">
                            <label class="font-weight-semibold">Tìm kiếm bệnh nhân</label>
                            <input type="text" name="search" class="form-control" placeholder="Tên, SĐT, mã hồ sơ..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-search mr-1"></i> Tìm kiếm
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('receptionist.patients.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-redo mr-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Danh sách bệnh nhân --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Họ tên</th>
                                <th>SĐT</th>
                                <th>Ngày sinh</th>
                                <th>Email</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patients as $patient)
                                <tr>
                                    <td class="font-weight-bold text-primary">#{{ $patient->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-primary text-white rounded-circle d-flex justify-content-center align-items-center mr-2">
                                                {{ strtoupper(mb_substr($patient->full_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-semibold">{{ $patient->full_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $patient->phone }}</td>
                                    <td>
                                        {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') : 'Chưa cập nhật' }}
                                    </td>
                                    <td>{{ $patient->email ?? 'Chưa cập nhật' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('receptionist.patients.edit', $patient->id) }}"
                                            class="btn btn-sm btn-outline-warning" data-toggle="tooltip" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('receptionist.patients.appointments', $patient->id) }}"
                                            class="btn btn-sm btn-outline-info" data-toggle="tooltip" title="Lịch sử khám">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-user-times fa-2x mb-2"></i><br>
                                        Không tìm thấy bệnh nhân nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($patients->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Hiển thị {{ $patients->firstItem() }} - {{ $patients->lastItem() }} /
                                {{ $patients->total() }} bệnh nhân
                            </small>
                        </div>
                        <div>
                            {{ $patients->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .icon-circle {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .avatar-sm {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
                font-weight: bold;
                text-transform: uppercase;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    @endpush
@endsection
