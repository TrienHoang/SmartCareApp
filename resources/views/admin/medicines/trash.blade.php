@extends('admin.dashboard')

@section('title', 'Thuốc đã xóa')

@push('styles')
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .header h2 {
        margin: 0;
        font-size: 1.75rem;
        color: #333;
    }
    .back-link {
        text-decoration: none;
        color: #007bff;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    .back-link:hover {
        color: #0056b3;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .medicines-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .medicines-table th, .medicines-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .medicines-table thead {
        background-color: #f8f9fa;
    }
    .medicines-table th {
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    .medicines-table tbody tr:hover {
        background-color: #f1f1f1;
    }
    .action-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        transition: background-color 0.3s ease;
        white-space: nowrap;
    }
    .btn-restore {
        background-color: #28a745;
        color: #fff;
    }
    .btn-restore:hover {
        background-color: #218838;
    }
    .empty-row {
        text-align: center;
        color: #888;
        padding: 20px;
    }
    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            align-items: flex-start;
        }
        .header h2 {
            margin-bottom: 10px;
        }
        .medicines-table th, .medicines-table td {
            font-size: 0.9rem;
            padding: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="header">
        <h2>Thuốc đã xóa</h2>
        <a href="{{ route('admin.medicines.index') }}" class="back-link">
            ← Quay về danh sách thuốc
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="medicines-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên thuốc</th>
                    <th>Đơn vị</th>
                    <th>Đã xoá lúc</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($medicines as $medicine)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ $medicine->unit }}</td>
                        <td>{{ $medicine->deleted_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.medicines.restore', $medicine->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="action-btn btn-restore">Khôi phục</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-row">Không có thuốc nào đã xoá.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection