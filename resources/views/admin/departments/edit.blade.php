@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <!-- Header Card -->
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                <div class="card-body p-0">
                    <div class="bg-gradient-primary text-white p-4" style="border-radius: 20px 20px 0 0;">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white bg-opacity-20 me-3">
                                <i class="fas fa-building text-white fa-lg"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 font-weight-bold">Cập nhật khoa</h3>
                                <p class="mb-0 opacity-90">Chỉnh sửa thông tin khoa của bạn</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body p-5">
                    {{-- Hiển thị lỗi với thiết kế đẹp hơn --}}
                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 15px; background: linear-gradient(135deg, #ff6b6b, #ee5a52);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                                <div class="flex-grow-1">
                                    <h6 class="alert-heading text-white mb-2">Vui lòng kiểm tra các lỗi sau:</h6>
                                    <ul class="mb-0 text-white list-unstyled">
                                        @foreach($errors->all() as $error)
                                            <li class="mb-1">
                                                <i class="fas fa-dot-circle me-2" style="font-size: 8px;"></i>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Tên khoa --}}
                        <div class="form-floating mb-4">
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name', $department->name) }}" 
                                   placeholder="Nhập tên khoa"
                                   required
                                   style="border-radius: 15px; border: 2px solid #e9ecef; transition: all 0.3s;">
                            <label for="name">
                                <i class="fas fa-building me-2 text-primary"></i>
                                Tên khoa <span class="text-danger">*</span>
                            </label>
                            @error('name')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Mô tả --}}
                        <div class="form-floating mb-4">
                            <textarea name="description" 
                                      id="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Nhập mô tả khoa"
                                      rows="4"
                                      style="border-radius: 15px; border: 2px solid #e9ecef; min-height: 120px; transition: all 0.3s;">{{ old('description', $department->description) }}</textarea>
                            <label for="description">
                                <i class="fas fa-align-left me-2 text-info"></i>
                                Mô tả khoa
                            </label>
                            @error('description')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="form-floating mb-5">
                            <select name="is_active" 
                                    id="is_active"
                                    class="form-select form-select-lg @error('is_active') is-invalid  @enderror"
                                    style="border-radius: 15px; border: 2px solid #e9ecef; transition: all 0.3s;    height: fit-content;">
                                <option value="1" {{ old('is_active', $department->is_active) == 1 ? 'selected' : '' }}>
                                    Đang hoạt động
                                </option>
                                <option value="0" {{ old('is_active', $department->is_active) == 0 ? 'selected' : '' }}>
                                    Ngừng hoạt động
                                </option>
                            </select>
                            <label for="is_active">
                                <i class="fas fa-toggle-on me-2 text-success"></i>
                                Trạng thái hoạt động
                            </label>
                            @error('is_active')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow-lg" style="border-radius: 15px; background: linear-gradient(135deg, #667eea, #764ba2); border: none; transition: all 0.3s;">
                                <i class="fas fa-save me-2"></i>
                                Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary btn-lg px-5 py-3 shadow-sm" style="border-radius: 15px; border: 2px solid #6c757d; transition: all 0.3s;">
                                <i class="fas fa-arrow-left me-2"></i>
                                Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
                <div class="card-body p-4 bg-light" style="border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-info me-3 fa-lg"></i>
                        <div>
                            <h6 class="mb-1 text-dark">Lưu ý quan trọng</h6>
                            <small class="text-muted">
                                Việc thay đổi trạng thái khoa có thể ảnh hưởng đến các nhân viên thuộc khoa này.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        transform: translateY(-2px);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
    }
    
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
        background-color: #6c757d;
        color: white;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .form-floating > label {
        font-weight: 500;
        color: #495057;
    }
    
    .alert {
        border-left: 5px solid rgba(255, 255, 255, 0.8);
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card {
        animation: slideInUp 0.6s ease-out;
    }
    
    .card:nth-child(2) {
        animation-delay: 0.1s;
    }
    
    .card:nth-child(3) {
        animation-delay: 0.2s;
    }
</style>

{{-- Custom JavaScript for enhanced UX --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation feedback
        const form = document.querySelector('.needs-validation');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                }
            });
        });
        
        // Smooth form submission
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang lưu...';
            submitBtn.disabled = true;
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.remove(), 300);
                }
            });
        }, 5000);
    });
</script>
@endsection