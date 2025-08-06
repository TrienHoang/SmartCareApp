@extends('admin.dashboard')

@section('title', 'Quản lý thuốc')

@push('styles')
    <style>
        .medicine-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            position: relative;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            position: relative;
            z-index: 1;
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .action-bar {
            padding: 25px 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .search-container {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 8px;
            display: flex;
            align-items: center;
            min-width: 300px;
            transition: all 0.3s ease;
        }

        .search-container:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-input {
            border: none;
            outline: none;
            padding: 8px 12px;
            flex: 1;
            font-size: 14px;
        }

        .search-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .search-btn:hover {
            background: #5a67d8;
        }

        .clear-filter {
            color: #dc3545;
            text-decoration: none;
            font-size: 12px;
            margin-left: 10px;
        }

        .alert {
            margin: 20px 30px;
            padding: 15px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d1edff;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }

        .table-container {
            padding: 0 30px 30px 30px;
        }

        .medicine-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .medicine-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .medicine-table th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .medicine-table td {
            padding: 16px 15px;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
        }

        .medicine-table tbody tr {
            transition: all 0.3s ease;
        }

        .medicine-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .medicine-name {
            font-weight: 600;
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .medicine-name:hover {
            color: #5a67d8;
            text-decoration: underline;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #28a745;
            color: white;
        }

        .btn-edit:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .pagination-wrapper {
            padding: 20px 30px;
            display: flex;
            justify-content: center;
            border-top: 1px solid #e9ecef;
        }

        .no-data {
            text-align: center;
            padding: 60px 30px;
            color: #6c757d;
        }

        .no-data-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .stats-bar {
            background: #e3f2fd;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #1976d2;
        }

        @media (max-width: 768px) {
            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-group {
                justify-content: center;
            }

            .search-container {
                min-width: auto;
            }

            .medicine-table {
                font-size: 14px;
            }

            .medicine-table th,
            .medicine-table td {
                padding: 12px 8px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="medicine-container">
        <div class="content-wrapper">
            <!-- Header Section -->
            <div class="header-section">
                <h1 class="page-title">Quản lý thuốc</h1>
                <p class="page-subtitle">Quản lý danh sách thuốc trong hệ thống</p>
            </div>

            <!-- Stats Bar -->
            <div class="stats-bar">
                <span><strong>Tổng số thuốc:</strong> {{ $medicines->total() ?? count($medicines) }}</span>
                @if (request('search'))
                    <span><strong>Kết quả tìm kiếm:</strong> "{{ request('search') }}"</span>
                @endif
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="btn-group">
                    <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
                        <span></span> Thêm thuốc mới
                    </a>
                    <a href="{{ route('admin.medicines.trash') }}" class="btn btn-secondary">
                        <span></span> Thùng rác
                    </a>
                </div>

                <!-- Search Form -->
                <form action="{{ route('admin.medicines.index') }}" method="GET">
                    <div class="search-container">
                        <input type="text" name="search" class="search-input" placeholder=" Tìm kiếm theo tên thuốc..."
                            value="{{ request('search') }}">
                        <button type="submit" class="search-btn">Tìm</button>
                    </div>
                    @if (request('search'))
                        <a href="{{ route('admin.medicines.index') }}" class="clear-filter">Xóa lọc</a>
                    @endif
                </form>
            </div>



            <!-- Table Container -->
            <div class="table-container">
                @if ($medicines->count() > 0)
                    <table class="medicine-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">#</th>
                                <th>Tên thuốc</th>
                                <th style="width: 120px;">Đơn vị</th>
                                <th style="width: 140px;">Ngày tạo</th>
                                <th style="width: 180px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medicines as $medicine)
                                <tr>
                                    <td><strong>{{ $medicine->id }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.medicines.show', $medicine->id) }}" class="medicine-name">
                                            {{ $medicine->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span
                                            style="background: #e3f2fd; padding: 4px 8px; border-radius: 4px; font-size: 12px; color: #1976d2;">
                                            {{ $medicine->unit }}
                                        </span>
                                    </td>
                                    <td>{{ $medicine->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.medicines.show', $medicine->id) }}" class="btn-sm btn-info">
                                                Xem
                                            </a>
                                            <a href="{{ route('admin.medicines.edit', $medicine->id) }}"
                                                class="btn-sm btn-edit" title="Chỉnh sửa">
                                                Sửa
                                            </a>
                                            <form action="{{ route('admin.medicines.destroy', $medicine->id) }}"
                                                method="POST" class="form-delete" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-delete"
                                                    data-medicine="{{ $medicine->name }}" title="Xóa">
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">
                        <div class="no-data-icon"></div>
                        <h3>Không tìm thấy thuốc nào</h3>
                        @if (request('search'))
                            <p>Không có kết quả nào cho từ khóa "<strong>{{ request('search') }}</strong>"</p>
                            <a href="{{ route('admin.medicines.index') }}" class="btn btn-primary">
                                Xem tất cả thuốc
                            </a>
                        @else
                            <p>Danh sách thuốc hiện tại đang trống</p>
                            <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
                                Thêm thuốc đầu tiên
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if (method_exists($medicines, 'links') && $medicines->hasPages())
                <div class="pagination-wrapper">
                    {{ $medicines->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Thêm hiệu ứng loading khi submit form
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');

                    // Nếu là form xóa thuốc thì xử lý confirm
                    if (form.classList.contains('form-delete')) {
                        const medicineName = submitBtn.getAttribute('data-medicine') || 'thuốc này';
                        const confirmed = confirm(
                            `Bạn có chắc chắn muốn xóa thuốc "${medicineName}" không?\n\nHành động này có thể hoàn tác từ thùng rác.`
                        );
                        if (!confirmed) {
                            e.preventDefault(); // huỷ submit
                            return;
                        }
                    }

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = 'Đang xử lý...';
                    }
                });
            });

            // Auto focus vào ô tìm kiếm nếu có search term
            const searchInput = document.querySelector('.search-input');
            if (searchInput && searchInput.value) {
                searchInput.focus();
                searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
            }

            // Thêm keyboard shortcut
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K để focus vào search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput?.focus();
                }

                // Ctrl/Cmd + N để thêm thuốc mới
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = '{{ route('admin.medicines.create') }}';
                }
            });
        });
    </script>
@endsection
