@extends('admin.dashboard')
@section('title', 'Quản lý đơn thuốc')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h3 class="card-title mb-0">Tìm kiếm và lọc đơn thuốc</h3>
                        <a href="{{ route('admin.prescriptions.trashed') }}"
                            class="btn btn-outline-danger mt-2 mt-sm-0 ml-sm-2">
                            <i class="fas fa-trash"></i> Đơn thuốc đã xóa mềm
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="search-form" method="GET">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label>Tên bệnh nhân</label>
                                        <input type="text" name="patient_name" class="form-control"
                                            value="{{ request('patient_name') }}" placeholder="Nhập tên bệnh nhân">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label>Tên bác sĩ</label>
                                        <input type="text" name="doctor_name" class="form-control"
                                            value="{{ request('doctor_name') }}" placeholder="Nhập tên bác sĩ">
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label>Từ ngày</label>
                                        <input type="date" name="date_from" class="form-control"
                                            value="{{ old('date_from', $from_input ?? request('date_from')) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label>Đến ngày</label>
                                        <input type="date" name="date_to" class="form-control"
                                            value="{{ old('date_to', $to_input ?? request('date_to')) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Thuốc</label>
                                        <select name="medicine_id" class="form-control select2">
                                            <option value="">-- Tất cả thuốc --</option>
                                            @foreach ($medicines as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ request('medicine_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Khoa/Phòng ban</label>
                                        <select name="department_id" class="form-control select2">
                                            <option value="">-- Tất cả khoa/phòng ban --</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <a href="{{ route('admin.prescriptions.index') }}"
                                                class="btn btn-secondary btn-block">
                                                <i class="fas fa-undo"></i> Đặt lại
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h3 class="card-title">Danh sách đơn thuốc</h3>
                        @can('create_prescriptions')
                            <a href="{{ route('admin.prescriptions.create') }}" class="btn btn-primary mt-2 mt-sm-0">
                                <i class="fas fa-plus"></i> Tạo đơn thuốc mới
                            </a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <script>
                            @if (session('success'))
                                toastr.success("{{ session('success') }}", "Thành công");
                            @endif

                            @if (session('error'))
                                toastr.error("{{ session('error') }}", "Lỗi");
                            @endif
                        </script>

                        {{-- Desktop Table (visible on medium and larger screens) --}}
                        <div class="d-none d-md-block">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Bệnh nhân</th>
                                            <th>Bác sĩ kê đơn</th>
                                            <th>Ngày kê đơn</th>
                                            <th>Số loại thuốc</th>
                                            <th>Tổng số lượng</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prescriptions as $prescription)
                                            <tr>
                                                <td>{{ ($prescriptions->currentPage() - 1) * $prescriptions->perPage() + $loop->iteration }}
                                                </td>
                                                <td>
                                                    <strong>{{ $prescription->medicalRecord->appointment->patient->full_name }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ $prescription->medicalRecord->appointment->patient->phone }}</small>
                                                </td>
                                                <td>
                                                    {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}<br>
                                                    <small
                                                        class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</small><br>
                                                    <small
                                                        class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->department->name ?? 'Không có' }}</small>
                                                </td>
                                                <td>{{ $prescription->formatted_date }}</td>
                                                <td>{{ $prescription->prescriptionItems->count() }} loại</td>
                                                <td>{{ $prescription->prescriptionItems->sum('quantity') }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.prescriptions.show', $prescription->id) }}"
                                                            class="btn btn-outline-info" data-toggle="tooltip"
                                                            title="Xem chi tiết">
                                                            <i class='bx bx-show-alt'></i>
                                                        </a>
                                                        <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}"
                                                            class="btn btn-outline-warning" data-toggle="tooltip"
                                                            title="Chỉnh sửa">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger"
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                            data-id="{{ $prescription->id }}"
                                                            data-name="Đơn thuốc #{{ $prescription->id }}"
                                                            data-toggle="tooltip" title="Xóa">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có đơn thuốc nào</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Mobile Cards (visible on extra small and small screens) --}}
                        <div class="d-block d-md-none" id="prescriptionAccordion"> {{-- Added ID for accordion parent --}}
                            @forelse($prescriptions as $prescription)
                                <div class="card mb-3"> {{-- Card for each prescription --}}
                                    <div class="card-header" id="headingPrescription{{ $prescription->id }}">
                                        <h5 class="mb-0">
                                            {{-- Adjusted button for better alignment and preventing line breaks --}}
                                            <button
                                                class="btn btn-link text-decoration-none text-dark d-flex align-items-center p-0 w-100"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapsePrescription{{ $prescription->id }}"
                                                aria-expanded="false"
                                                aria-controls="collapsePrescription{{ $prescription->id }}">
                                                <i class="fas fa-plus-circle fa-fw me-2"
                                                    id="iconPrescription{{ $prescription->id }}"></i>
                                                <span class="text-start flex-grow-1 text-nowrap">
                                                    Đơn thuốc
                                                    #{{ ($prescriptions->currentPage() - 1) * $prescriptions->perPage() + $loop->iteration }}
                                                    <span
                                                        class="ms-2 text-muted">{{ $prescription->formatted_date }}</span>
                                                </span>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapsePrescription{{ $prescription->id }}" class="collapse"
                                        aria-labelledby="headingPrescription{{ $prescription->id }}"
                                        data-bs-parent="#prescriptionAccordion"> {{-- Using a parent ID for accordion behavior --}}
                                        <div class="card-body">
                                            <p><strong>Bệnh nhân:</strong>
                                                {{ $prescription->medicalRecord->appointment->patient->full_name }}
                                                ({{ $prescription->medicalRecord->appointment->patient->phone }})</p>
                                            <p><strong>Bác sĩ kê đơn:</strong>
                                                {{ $prescription->medicalRecord->appointment->doctor->user->full_name }}<br>
                                                <small
                                                    class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</small><br>
                                                <small
                                                    class="text-muted">{{ $prescription->medicalRecord->appointment->doctor->department->name ?? 'Không có' }}</small>
                                            </p>
                                            <p><strong>Số loại thuốc:</strong>
                                                {{ $prescription->prescriptionItems->count() }} loại</p>
                                            <p><strong>Tổng số lượng:</strong>
                                                {{ $prescription->prescriptionItems->sum('quantity') }}</p>
                                            <div class="d-flex justify-content-around mt-3">
                                                <a href="{{ route('admin.prescriptions.show', $prescription->id) }}"
                                                    class="btn btn-outline-info btn-sm" data-toggle="tooltip"
                                                    title="Xem chi tiết">
                                                    <i class='bx bx-show-alt'></i> Xem
                                                </a>
                                                <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}"
                                                    class="btn btn-outline-warning btn-sm" data-toggle="tooltip"
                                                    title="Chỉnh sửa">
                                                    <i class="bx bx-edit"></i> Sửa
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-id="{{ $prescription->id }}"
                                                    data-name="Đơn thuốc #{{ ($prescriptions->currentPage() - 1) * $prescriptions->perPage() + $loop->iteration }}"
                                                    data-toggle="tooltip" title="Xóa">
                                                    <i class="bx bx-trash"></i> Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info text-center" role="alert">
                                    Không có đơn thuốc nào
                                </div>
                            @endforelse
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $prescriptions->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <i class="bx bx-error-circle fs-1 text-warning mb-2"></i>
                    <h4 class="modal-title mb-2">Xác nhận xóa mềm</h4>
                    <p id="deleteModalMessage">
                        Bạn có chắc chắn muốn xóa mục này? Dữ liệu sẽ được lưu trong thùng rác và có thể khôi phục sau.
                    </p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script>
        let deleteId = null;

        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const triggerButton = event.relatedTarget;
            deleteId = triggerButton.getAttribute('data-id');
            const name = triggerButton.getAttribute('data-name');

            document.getElementById('deleteModalMessage').textContent =
                `Bạn có chắc chắn muốn xóa ${name}? Dữ liệu sẽ được lưu trong thùng rác và có thể khôi phục sau.`;
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteId) {
                const deleteUrl = `{{ route('admin.prescriptions.destroy', ':id') }}`.replace(':id', deleteId);

                fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.message) {
                            // Lưu thông báo vào localStorage để hiển thị sau reload
                            localStorage.setItem('flashSuccess', data.message);

                            const modalInstance = bootstrap.Modal.getInstance(deleteModal);
                            modalInstance.hide();

                            // Tải lại trang
                            window.location.href = "{{ route('admin.prescriptions.index') }}";
                        }
                    })
                    .catch(() => {});
            }
        });

        // Khi load lại trang, kiểm tra nếu có thông báo thành công thì hiển thị
        window.addEventListener('DOMContentLoaded', function() {
            const successMessage = localStorage.getItem('flashSuccess');
            if (successMessage) {
                toastr.success(successMessage, 'Thành công');
                localStorage.removeItem('flashSuccess');
            }

            // JavaScript for toggling plus/minus icon on accordion collapse/expand
            const collapseElements = document.querySelectorAll('.collapse');

            collapseElements.forEach(function(collapseEl) {
                collapseEl.addEventListener('show.bs.collapse', function() {
                    const icon = document.getElementById('icon' + collapseEl.id.replace('collapse',
                        'Prescription'));
                    if (icon) {
                        icon.classList.remove('fa-plus-circle');
                        icon.classList.add('fa-minus-circle');
                    }
                });

                collapseEl.addEventListener('hide.bs.collapse', function() {
                    const icon = document.getElementById('icon' + collapseEl.id.replace('collapse',
                        'Prescription'));
                    if (icon) {
                        icon.classList.remove('fa-minus-circle');
                        icon.classList.add('fa-plus-circle');
                    }
                });
            });
        });
    </script>
@endpush
