@extends('admin.dashboard')

@section('title', 'Sửa thuốc')

@push('styles')
<style>
    .edit-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px 0;
    }
    
    .form-wrapper {
        max-width: 700px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        position: relative;
    }
    
    .form-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="edit-pattern" width="25" height="25" patternUnits="userSpaceOnUse"><path d="M12.5 5L20 12.5L12.5 20L5 12.5Z" fill="rgba(255,255,255,0.1)" stroke="none"/></pattern></defs><rect width="100" height="100" fill="url(%23edit-pattern)"/></svg>');
    }
    
    .header-content {
        position: relative;
        z-index: 1;
    }
    
    .form-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .medicine-name {
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 1rem;
        font-weight: 500;
    }
    
    .form-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin: 8px 0 0 0;
    }
    
    .form-body {
        padding: 40px;
    }
    
    .info-card {
        background: #e8f4fd;
        border: 1px solid #bee5eb;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .info-icon {
        font-size: 2rem;
        color: #17a2b8;
    }
    
    .info-content h4 {
        margin: 0 0 5px 0;
        color: #0c5460;
        font-size: 1.1rem;
    }
    
    .info-details {
        color: #0c5460;
        font-size: 0.9rem;
        margin: 0;
    }
    
    .form-group {
        margin-bottom: 25px;
        position: relative;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .required {
        color: #e53e3e;
    }
    
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #fff;
        box-sizing: border-box;
        position: relative;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #ff6b6b;
        box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
        transform: translateY(-1px);
    }
    
    .form-input:hover {
        border-color: #cbd5e0;
    }
    
    .form-textarea {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
    }
    
    .input-help {
        font-size: 12px;
        color: #718096;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .error-message {
        color: #e53e3e;
        font-size: 12px;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .changed-indicator {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #ff6b6b;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .form-input.changed + .changed-indicator {
        opacity: 1;
    }
    
    .breadcrumb {
        background: #fff3cd;
        padding: 15px 20px;
        margin: -20px -20px 20px -20px;
        border-radius: 8px;
        font-size: 14px;
        color: #856404;
    }
    
    .breadcrumb a {
        color: #856404;
        text-decoration: none;
    }
    
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    
    .quick-fill {
        background: #f8f9fa;
        border: 1px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .quick-fill-title {
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 10px;
    }
    
    .quick-units {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .unit-tag {
        background: white;
        border: 1px solid #dee2e6;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .unit-tag:hover {
        background: #ff6b6b;
        color: white;
        border-color: #ff6b6b;
    }
    
    .unit-tag.current {
        background: #28a745;
        color: white;
        border-color: #28a745;
    }
    
    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }
    
    .btn-group-left {
        display: flex;
        gap: 12px;
    }
    
    .btn-group-right {
        display: flex;
        gap: 12px;
    }
    
    .btn {
        padding: 14px 28px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 14px;
        min-width: 120px;
        justify-content: center;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
    }
    
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-secondary {
        background: #f7fafc;
        color: #4a5568;
        border: 2px solid #e2e8f0;
    }
    
    .btn-secondary:hover {
        background: #edf2f7;
        border-color: #cbd5e0;
        transform: translateY(-1px);
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c82333;
        transform: translateY(-1px);
    }
    
    .btn-info {
        background: #17a2b8;
        color: white;
    }
    
    .btn-info:hover {
        background: #138496;
        transform: translateY(-1px);
    }
    
    .changes-summary {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: none;
    }
    
    .changes-summary.show {
        display: block;
    }
    
    .changes-title {
        font-weight: 600;
        color: #155724;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .changes-list {
        color: #155724;
        font-size: 14px;
        margin: 0;
        padding-left: 20px;
    }
    
    @media (max-width: 768px) {
        .form-wrapper {
            margin: 10px;
            border-radius: 12px;
        }
        
        .form-body {
            padding: 30px 20px;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 15px;
        }
        
        .btn-group-left,
        .btn-group-right {
            width: 100%;
            justify-content: center;
        }
        
        .btn {
            flex: 1;
            min-width: auto;
        }
        
        .info-card {
            flex-direction: column;
            text-align: center;
        }
    }
    
    /* Animation */
    .form-group {
        opacity: 0;
        transform: translateY(20px);
        animation: slideUp 0.6s ease forwards;
    }
    
    .form-group:nth-child(1) { animation-delay: 0.1s; }
    .form-group:nth-child(2) { animation-delay: 0.2s; }
    .form-group:nth-child(3) { animation-delay: 0.3s; }
    .form-group:nth-child(4) { animation-delay: 0.4s; }
    .form-group:nth-child(5) { animation-delay: 0.5s; }
    
    @keyframes slideUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="edit-container">
    <div class="form-wrapper">
        <!-- Header -->
        <div class="form-header">
            <div class="header-content">
                <h1 class="form-title">
                    Chỉnh sửa thuốc
                </h1>
                <div class="medicine-name">{{ $medicine->name }}</div>
                <p class="form-subtitle">Cập nhật thông tin thuốc trong hệ thống</p>
            </div>
        </div>

        <div class="form-body">
            <!-- Medicine Info Card -->
            <div class="info-card">
                <div class="info-icon">📋</div>
                <div class="info-content">
                    <h4>Thông tin hiện tại</h4>
                    <p class="info-details">
                        • <strong>ID:</strong> #{{ $medicine->id }} <br> • 
                        <strong>Tạo:</strong> {{ $medicine->created_at->format('d/m/Y H:i') }} <br> • 
                        <strong>Cập nhật:</strong> {{ $medicine->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Changes Summary -->
            <div class="changes-summary" id="changesSummary">
                <div class="changes-title">
                    Các thay đổi đã thực hiện:
                </div>
                <ul class="changes-list" id="changesList"></ul>
            </div>

            <form action="{{ route('admin.medicines.update', $medicine->id) }}" method="POST" id="medicineEditForm">
                @csrf
                @method('PUT')

                <!-- Tên thuốc -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        Tên thuốc <span class="required">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="text" 
                               id="name"
                               name="name" 
                               class="form-input @error('name') error @enderror" 
                               value="{{ old('name', $medicine->name) }}"
                               data-original="{{ $medicine->name }}"
                               placeholder="Ví dụ: Paracetamol, Amoxicillin..."
                               required
                               autocomplete="off">
                        <span class="changed-indicator">●</span>
                    </div>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @else
                        <div class="input-help">Tên thuốc hiện tại: <strong>{{ $medicine->name }}</strong></div>
                    @enderror
                </div>

                <!-- Đơn vị -->
                <div class="form-group">
                    <label for="unit" class="form-label">
                        Đơn vị <span class="required">*</span>
                    </label>
                    
                    <!-- Quick Select Units -->
                    <div class="quick-fill">
                        <div class="quick-fill-title">Chọn nhanh đơn vị phổ biến:</div>
                        <div class="quick-units">
                            <span class="unit-tag {{ $medicine->unit == 'viên' ? 'current' : '' }}" onclick="selectUnit('viên')">viên</span>
                            <span class="unit-tag {{ $medicine->unit == 'vỉ' ? 'current' : '' }}" onclick="selectUnit('vỉ')">vỉ</span>
                            <span class="unit-tag {{ $medicine->unit == 'chai' ? 'current' : '' }}" onclick="selectUnit('chai')">chai</span>
                            <span class="unit-tag {{ $medicine->unit == 'ống' ? 'current' : '' }}" onclick="selectUnit('ống')">ống</span>
                            <span class="unit-tag {{ $medicine->unit == 'tuýp' ? 'current' : '' }}" onclick="selectUnit('tuýp')">tuýp</span>
                            <span class="unit-tag {{ $medicine->unit == 'gói' ? 'current' : '' }}" onclick="selectUnit('gói')">gói</span>
                            <span class="unit-tag {{ $medicine->unit == 'ml' ? 'current' : '' }}" onclick="selectUnit('ml')">ml</span>
                            <span class="unit-tag {{ $medicine->unit == 'mg' ? 'current' : '' }}" onclick="selectUnit('mg')">mg</span>
                        </div>
                    </div>
                    
                    <div style="position: relative;">
                        <input type="text" 
                               id="unit"
                               name="unit" 
                               class="form-input @error('unit') error @enderror" 
                               value="{{ old('unit', $medicine->unit) }}"
                               data-original="{{ $medicine->unit }}"
                               placeholder="Nhập đơn vị hoặc chọn từ danh sách trên"
                               readonly>
                        <span class="changed-indicator">●</span>
                    </div>
                    @error('unit')
                        <div class="error-message">{{ $message }}</div>
                    @else
                        <div class="input-help">Đơn vị hiện tại: <strong>{{ $medicine->unit }}</strong></div>
                    @enderror
                </div>

                <!-- Mô tả -->
                <div class="form-group">
                    <label for="description" class="form-label">
                        Mô tả
                    </label>
                    <div style="position: relative;">
                        <textarea id="description"
                                  name="description" 
                                  class="form-input form-textarea @error('description') error @enderror"
                                  data-original="{{ $medicine->description }}"
                                  placeholder="Mô tả về công dụng, cách dùng, lưu ý...">{{ old('description', $medicine->description) }}</textarea>
                        <span class="changed-indicator" style="top: 20px;">●</span>
                    </div>
                    @error('description')
                        <div class="error-message"> {{ $message }}</div>
                    @else
                        <div class="input-help">Mô tả hiện tại: {{ $medicine->description ? '"' . Str::limit($medicine->description, 50) . '"' : 'Chưa có mô tả' }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="btn-group-left">
                        <a href="{{ route('admin.medicines.show', $medicine->id) }}" class="btn btn-info">
                            Xem chi tiết
                        </a>
                    </div>
                    
                    <div class="btn-group-right">
                        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                            ← Quay lại
                        </a>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            Khôi phục
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                         Cập nhật
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('medicineEditForm');
    const submitBtn = document.getElementById('submitBtn');
    const changesSummary = document.getElementById('changesSummary');
    const changesList = document.getElementById('changesList');
    
    const inputs = {
        name: document.getElementById('name'),
        unit: document.getElementById('unit'),
        description: document.getElementById('description')
    };
    
    const originalValues = {
        name: inputs.name.dataset.original,
        unit: inputs.unit.dataset.original,
        description: inputs.description.dataset.original
    };
    
    // Track changes
    function trackChanges() {
        const changes = [];
        let hasChanges = false;
        
        Object.keys(inputs).forEach(key => {
            const input = inputs[key];
            const original = originalValues[key];
            const current = input.value.trim();
            
            if (current !== original) {
                hasChanges = true;
                input.classList.add('changed');
                
                const fieldName = {
                    name: 'Tên thuốc',
                    unit: 'Đơn vị',
                    description: 'Mô tả'
                }[key];
                
                changes.push(`<li><strong>${fieldName}:</strong> "${original || 'Trống'}" → "${current || 'Trống'}"</li>`);
            } else {
                input.classList.remove('changed');
            }
        });
        
        // Update changes summary
        if (hasChanges) {
            changesList.innerHTML = changes.join('');
            changesSummary.classList.add('show');
            submitBtn.innerHTML = 'Cập nhật (' + changes.length + ' thay đổi)';
        } else {
            changesSummary.classList.remove('show');
            submitBtn.innerHTML = 'Cập nhật';
        }
        
        return hasChanges;
    }
    
    // Add event listeners
    Object.values(inputs).forEach(input => {
        input.addEventListener('input', trackChanges);
        input.addEventListener('blur', trackChanges);
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        if (!trackChanges()) {
            e.preventDefault();
            showNotification('Không có thay đổi nào để lưu!', 'info');
            return;
        }
        
        if (!inputs.name.value.trim()) {
            e.preventDefault();
            inputs.name.focus();
            showNotification('Vui lòng nhập tên thuốc', 'error');
            return;
        }
        
        if (!inputs.unit.value.trim()) {
            e.preventDefault();
            inputs.unit.focus();
            showNotification('Vui lòng nhập đơn vị', 'error');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Đang cập nhật...';
        
        // Confirm changes
        const changeCount = document.querySelectorAll('.form-input.changed').length;
        if (changeCount > 0) {
            if (!confirm(`Bạn có chắc chắn muốn lưu ${changeCount} thay đổi này?`)) {
                e.preventDefault();
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Cập nhật (' + changeCount + ' thay đổi)';
                return;
            }
        }
    });
    
    // Initial check
    setTimeout(trackChanges, 100);
    
    // Auto-focus first input
    inputs.name.focus();
    inputs.name.setSelectionRange(inputs.name.value.length, inputs.name.value.length);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            if (trackChanges()) {
                form.submit();
            }
        }
        
        // Ctrl/Cmd + R to reset
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            resetForm();
        }
        
        // Escape to go back
        if (e.key === 'Escape') {
            if (trackChanges()) {
                if (confirm('Bạn có những thay đổi chưa lưu. Bạn có muốn rời khỏi trang này?')) {
                    window.location.href = '{{ route("admin.medicines.index") }}';
                }
            } else {
                window.location.href = '{{ route("admin.medicines.index") }}';
            }
        }
    });
});

// Function to select unit quickly
function selectUnit(unit) {
    const unitInput = document.getElementById('unit');
    unitInput.value = unit;
    unitInput.focus();
    
    // Trigger change tracking
    unitInput.dispatchEvent(new Event('input'));
    
    // Update unit tags
    document.querySelectorAll('.unit-tag').forEach(tag => {
        tag.classList.remove('current');
    });
    event.target.classList.add('current');
    
    // Visual feedback
    event.target.style.transform = 'scale(1.1)';
    setTimeout(() => {
        event.target.style.transform = '';
    }, 200);
}

// Reset form function
function resetForm() {
    if (confirm('Bạn có chắc chắn muốn khôi phục tất cả về giá trị ban đầu?')) {
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            if (input.dataset.original !== undefined) {
                input.value = input.dataset.original;
                input.classList.remove('changed');
            }
        });
        
        // Hide changes summary
        document.getElementById('changesSummary').classList.remove('show');
        document.getElementById('submitBtn').innerHTML = 'Cập nhật';
        
        showNotification('Đã khôi phục tất cả về giá trị ban đầu', 'success');
    }
}

// Show notification function
function showNotification(message, type = 'info') {
    const colors = {
        success: { bg: '#d1edff', color: '#0c5460', border: '#17a2b8' },
        error: { bg: '#f8d7da', color: '#721c24', border: '#dc3545' },
        info: { bg: '#d4edda', color: '#155724', border: '#28a745' }
    };
    
    const alert = document.createElement('div');
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type].bg};
        color: ${colors[type].color};
        padding: 12px 20px;
        border-radius: 8px;
        border-left: 4px solid ${colors[type].border};
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1000;
        animation: slideInRight 0.3s ease;
        max-width: 300px;
    `;
    
    const icons = { success: '✅', error: '⚠️', info: 'ℹ️' };
    alert.innerHTML = `${icons[type]} ${message}`;
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.style.animation = 'slideOutRight 0.3s ease forwards';
        setTimeout(() => alert.remove(), 300);
    }, 3000);
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endsection