@extends('admin.dashboard')

@section('title', 'Chỉnh sửa Ngày nghỉ Bác sĩ')

@section('content')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <i class="bx bx-calendar-check text-primary me-2" style="font-size: 24px;"></i>
                    <h2 class="mb-0">Chỉnh sửa Ngày nghỉ Bác sĩ</h2>
                </div>
                <nav aria-label="breadcrumb" class="mt-1">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard.index') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.doctor_leaves.index') }}">Ngày nghỉ Bác sĩ</a>
                        </li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="content-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h4 class="card-title mb-0">
                                <i class="bx bx-edit text-primary me-2"></i> Thông tin Ngày nghỉ
                            </h4>
                            <small class="text-muted">Cập nhật trạng thái duyệt</small>
                        </div>
                        <div class="card-body">
                            <!-- Error -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong><i class="bx bx-error-circle me-1"></i> Vui lòng kiểm tra lại:</strong>
                                    <ul class="mt-2 ps-3 mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Form -->
                            <form action="{{ route('admin.doctor_leaves.update', $leave->id) }}" method="POST" novalidate>
                                @csrf
                                @method('PUT')

                                <!-- Doctor Info -->
                                <div class="form-section mb-4">
                                    <h6 class="section-title mb-3">
                                        <i class="bx bx-info-circle text-primary me-2"></i>
                                        Thông tin ngày nghỉ (không chỉnh sửa)
                                    </h6>

                                    <div class="mb-3">
                                        <label class="form-label">Bác sĩ</label>
                                        <input type="text" class="form-control"
                                            value="{{ $leave->doctor->user->full_name ?? 'Không rõ' }}" readonly>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ngày bắt đầu</label>
                                            <input type="text" class="form-control"
                                                value="{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}"
                                                readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ngày kết thúc</label>
                                            <input type="text" class="form-control"
                                                value="{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Lý do nghỉ</label>
                                        <textarea class="form-control" rows="3" readonly>{{ $leave->reason ?? 'Không có lý do' }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Bác sĩ thay thế</label>
                                        <select name="replacement_doctor_id"
                                            class="form-select @error('replacement_doctor_id') is-invalid @enderror">
                                            <option value="">-- Chưa chọn --</option>
                                            @foreach ($replacementDoctors as $doc)
                                                <option value="{{ $doc->id }}"
                                                    {{ $leave->replacement_doctor_id == $doc->id ? 'selected' : '' }}>
                                                    {{ $doc->user->full_name ?? 'Không có tên' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('replacement_doctor_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="bx bx-cog me-1"></i> Trạng thái & Hành động</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Trạng thái duyệt <span
                                                    class="text-danger">*</span></label>
                                            <select name="approved"
                                                class="form-select @error('approved') is-invalid @enderror">
                                                <option value="0" {{ $leave->approved == 0 ? 'selected' : '' }}>Chưa
                                                    duyệt</option>
                                                <option value="1" {{ $leave->approved == 1 ? 'selected' : '' }}>Đã
                                                    duyệt</option>
                                            </select>
                                            @error('approved')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary flex-fill">
                                                <i class="bx bx-save me-1"></i> Cập nhật
                                            </button>
                                            <a href="{{ route('admin.doctor_leaves.index') }}"
                                                class="btn btn-outline-secondary flex-fill">
                                                <i class="bx bx-x me-1"></i> Hủy bỏ
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-muted"><i class="bx bx-bulb me-1"></i> Mẹo sử dụng</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li><i class="bx bx-check text-success me-1"></i> Chỉ thay đổi trạng thái duyệt</li>
                                <li><i class="bx bx-check text-success me-1"></i> Đảm bảo chọn bác sĩ thay thế trước khi
                                    duyệt</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-section {
            background: #fafafa;
            border-radius: 8px;
            padding: 1.25rem;
            border: 1px solid #e3e6f0;
        }

        .section-title {
            font-weight: 600;
            color: #4e73df;
        }

        .btn {
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-1px);
        }
    </style>
@endpush
