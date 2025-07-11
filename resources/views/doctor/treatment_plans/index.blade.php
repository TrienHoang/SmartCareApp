@extends('doctor.dashboard')
@section('title', 'Quản lý Kế hoạch điều trị')

@section('content')
    <div class="container">
        <h1>Quản lý Kế hoạch Điều trị</h1>

        <a href="{{ route('doctor.treatment-plans.create') }}" class="btn btn-primary mb-3">Tạo Kế hoạch Mới</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-header">Bộ lọc Kế hoạch</div>
            <div class="card-body">
                <form method="GET" action="{{ route('doctor.treatment-plans.index') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="plan_title">Tìm theo tiêu đề:</label>
                                <input type="text" name="plan_title" id="plan_title" class="form-control"
                                    value="{{ request('plan_title') }}" placeholder="Nhập tiêu đề kế hoạch">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="patient_id">Bệnh nhân:</label>
                                <select name="patient_id" id="patient_id" class="form-control select2">
                                    <option value="">Tất cả bệnh nhân</option>
                                    @if (request('patient_id'))
                                        <option value="{{ request('patient_id') }}" selected>
                                            {{ \App\Models\User::find(request('patient_id'))?->full_name }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Trạng thái:</label>
                                <select name="status" id="status" class="form-control">
                                    @foreach ($statuses as $statusOption)
                                        <option value="{{ $statusOption }}"
                                            {{ request('status', 'all') == $statusOption ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date_filter">Ngày bắt đầu từ:</label>
                                <input type="date" name="start_date_filter" id="start_date_filter" class="form-control"
                                    value="{{ request('start_date_filter') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date_filter">Ngày kết thúc đến:</label>
                                <input type="date" name="end_date_filter" id="end_date_filter" class="form-control"
                                    value="{{ request('end_date_filter') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info mt-3">Lọc</button>
                    <a href="{{ route('doctor.treatment-plans.index') }}" class="btn btn-secondary mt-3"
                        id="resetFilterBtn">Đặt lại</a>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Bệnh nhân</th>
                    <th>Trạng thái</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($treatmentPlans as $plan)
                    <tr>
                        <td>{{ $plan->id }}</td>
                        <td>{{ $plan->plan_title }}</td>
                        <td>{{ $plan->patient->full_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $plan->getBadgeClassForStatus($plan->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $plan->status)) }}
                            </span>
                        </td>
                        <td>{{ $plan->start_date?->format('d-m-Y') }}</td>
                        <td>{{ $plan->end_date?->format('d-m-Y') }}</td>
                        <td>{{ $plan->created_at?->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('doctor.treatment-plans.show', $plan) }}" class="btn btn-info btn-sm">Xem</a>
                            <a href="{{ route('doctor.treatment-plans.edit', $plan) }}"
                                class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('doctor.treatment-plans.destroy', $plan) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa kế hoạch này và các mục liên quan?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Không tìm thấy kế hoạch điều trị nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $treatmentPlans->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#patient_id').select2({
                placeholder: "Nhập tên hoặc email bệnh nhân để tìm kiếm",
                allowClear: true,
                ajax: {
                    url: "{{ route('doctor.treatment-plans.searchPatient') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        console.log('Select2 data:', data);
                        return {
                            results: data.map(function(patient) {
                                return {
                                    id: patient.id,
                                    text: patient.full_name + ' (' + patient.email +
                                        ')'
                                };
                            }),
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            }).on('select2:open', function() {
                setTimeout(function() {
                    $('.select2-search__field').focus();
                }, 50);
            });

            // Các phần còn lại của script cho Flatpickr và nút reset
            const startDatePicker = flatpickr("#start_date_filter", {
                dateFormat: "Y-m-d",
                maxDate: "#end_date_filter",
                allowInput: true,
                onChange: function(selectedDates, dateStr, instance) {
                    if (!dateStr) {
                        $('#filterForm').submit();
                    }
                }
            });

            const endDatePicker = flatpickr("#end_date_filter", {
                dateFormat: "Y-m-d",
                minDate: "#start_date_filter",
                allowInput: true,
                onChange: function(selectedDates, dateStr, instance) {
                    if (!dateStr) {
                        $('#filterForm').submit();
                    }
                }
            });

            $('#resetFilterBtn').on('click', function() {
                const form = $('#filterForm')[0];
                if (form) {
                    startDatePicker.clear();
                    endDatePicker.clear();
                }
            });
        });
    </script>
@endpush
