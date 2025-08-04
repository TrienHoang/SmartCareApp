@extends('admin.dashboard')

@section('title', 'Thêm thuốc')

@push('styles')
    <style>
        .create-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        .form-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1.5" fill="rgba(255,255,255,0.2)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 8px 0;
            position: relative;
            z-index: 1;
        }

        .form-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .form-body {
            padding: 40px;
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
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
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

        .progress-indicator {
            height: 4px;
            background: #f1f5f9;
            margin-bottom: 20px;
            border-radius: 2px;
            overflow: hidden;
        }


        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            width: 33.33%;
            transition: width 0.3s ease;
        }

        .breadcrumb {
            background: #e3f2fd;
            padding: 15px 20px;
            margin: -20px -20px 20px -20px;
            border-radius: 8px;
            font-size: 14px;
            color: #1976d2;
        }

        .breadcrumb a {
            color: #1976d2;
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
            margin-bottom: 20px;
            text-align: center;
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
            justify-content: center;
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
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .unit-tag.selected {
            background: #667eea;
            color: white;
            border-color: #667eea;
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
            }

            .btn {
                width: 100%;
            }

            .quick-units {
                justify-content: flex-start;
            }
        }

        /* Animation cho form load */
        .form-group {
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.6s ease forwards;
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-group:nth-child(3) {
            animation-delay: 0.3s;
        }

        .form-group:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="create-container">
        <div class="form-wrapper">
            <!-- Header -->
            <div class="form-header">
                <h1 class="form-title">Thêm thuốc mới</h1>
                <p class="form-subtitle">Nhập thông tin thuốc vào hệ thống</p>
            </div>

            <div class="form-body">

                <!-- Progress Indicator -->
                <div class="progress-indicator">
                    <div class="progress-bar"></div>
                </div>

                <form action="{{ route('admin.medicines.store') }}" method="POST" id="medicineForm">
                    @csrf

                    <!-- Tên thuốc -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Tên thuốc <span class="required">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            class="form-input @error('name') error @enderror" value="{{ old('name') }}"
                            placeholder="Ví dụ: Paracetamol, Amoxicillin..." required autocomplete="off">
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @else
                            <div class="input-help"> Nhập tên thuốc chính xác để dễ tìm kiếm</div>
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
                                <span class="unit-tag" onclick="selectUnit('viên')">viên</span>
                                <span class="unit-tag" onclick="selectUnit('vỉ')">vỉ</span>
                                <span class="unit-tag" onclick="selectUnit('chai')">chai</span>
                                <span class="unit-tag" onclick="selectUnit('ống')">ống</span>
                                <span class="unit-tag" onclick="selectUnit('tuýp')">tuýp</span>
                                <span class="unit-tag" onclick="selectUnit('gói')">gói</span>
                                <span class="unit-tag" onclick="selectUnit('ml')">ml</span>
                                <span class="unit-tag" onclick="selectUnit('mg')">mg</span>
                            </div>
                        </div>

                        <input type="text" id="unit" name="unit"
                            class="form-input @error('unit') error @enderror" value="{{ old('unit') }}"
                            placeholder="Nhập đơn vị hoặc chọn từ danh sách trên" readonly>
                        @error('unit')
                            <div class="error-message"> {{ $message }}</div>
                        @else
                            <div class="input-help"> Đơn vị tính của thuốc (viên, chai, tuýp...)</div>
                        @enderror
                    </div>

                    <!-- Mô tả -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            Mô tả
                        </label>
                        <textarea id="description" name="description" class="form-input form-textarea @error('description') error @enderror"
                            placeholder="Mô tả về công dụng, cách dùng, lưu ý... (không bắt buộc)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-message"> {{ $message }}</div>
                        @else
                            <div class="input-help">Thông tin bổ sung về thuốc (tùy chọn)</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Lưu thuốc
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('medicineForm');
            const submitBtn = document.getElementById('submitBtn');
            const progressBar = document.querySelector('.progress-bar');
            const nameInput = document.getElementById('name');
            const unitInput = document.getElementById('unit');
            const descInput = document.getElementById('description');

            // Update progress bar based on filled fields
            function updateProgress() {
                let filled = 0;
                if (nameInput.value.trim()) filled++;
                if (unitInput.value.trim()) filled++;
                if (descInput.value.trim()) filled++;

                const percentage = (filled / 3) * 100;
                progressBar.style.width = percentage + '%';
            }

            // Add event listeners for progress tracking
            [nameInput, unitInput, descInput].forEach(input => {
                input.addEventListener('input', updateProgress);
            });

            // Form submission handling
            form.addEventListener('submit', function(e) {
                // Validation
                if (!nameInput.value.trim()) {
                    e.preventDefault();
                    nameInput.focus();
                    showError('Vui lòng nhập tên thuốc');
                    return;
                }

                if (!unitInput.value.trim()) {
                    e.preventDefault();
                    unitInput.focus();
                    showError('Vui lòng nhập đơn vị');
                    return;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Đang lưu...';

                // Simulate processing time for better UX
                setTimeout(() => {
                    // Form will submit naturally
                }, 500);
            });

            // Auto-focus first input
            nameInput.focus();

            // Initial progress update
            updateProgress();

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + S to save
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    form.submit();
                }

                // Escape to cancel
                if (e.key === 'Escape') {
                    if (confirm('Bạn có muốn hủy bỏ và quay lại danh sách thuốc?')) {
                        window.location.href = '{{ route('admin.medicines.index') }}';
                    }
                }
            });
        });

        function selectUnit(unit) {
            // Set giá trị cho input
            document.getElementById('unit').value = unit;
            document.getElementById('unit').focus();
            document.getElementById('unit').dispatchEvent(new Event('input'));

            // Xoá class "selected" của tất cả unit-tag trước đó
            const allTags = document.querySelectorAll('.unit-tag');
            allTags.forEach(tag => {
                tag.classList.remove('selected');
            });

            // Thêm class "selected" vào tag vừa click
            event.target.classList.add('selected');
        }

        // Show error message
        function showError(message) {
            // Create temporary alert
            const alert = document.createElement('div');
            alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #fee;
        color: #c53030;
        padding: 12px 20px;
        border-radius: 8px;
        border-left: 4px solid #e53e3e;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1000;
        animation: slideInRight 0.3s ease;
    `;
            alert.innerHTML = `${message}`;

            document.body.appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 3000);
        }

        // Add CSS for slide animation
        const style = document.createElement('style');
        style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
        document.head.appendChild(style);
    </script>
@endsection
