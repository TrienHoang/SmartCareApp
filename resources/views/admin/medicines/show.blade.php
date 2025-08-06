@extends('admin.dashboard')

@section('title', 'Chi tiết thuốc')

@push('styles')
<style>
    .detail-container {
        padding: 20px;
        background-color: #f4f7f9;
        min-height: 100vh;
    }
    .content-wrapper {
        max-width: 900px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }
    h1 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 5px;
    }
    .subtitle {
        color: #666;
        margin-bottom: 20px;
    }
    .detail-item {
        margin-bottom: 20px;
    }
    .detail-label {
        font-weight: bold;
        color: #555;
        display: block;
        margin-bottom: 5px;
    }
    .detail-value {
        padding: 10px 15px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: #333;
    }
    .detail-value.empty {
        color: #888;
        font-style: italic;
    }
    .actions-card, .info-card {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #eee;
    }
    .card-title {
        font-size: 1.25rem;
        border-bottom: 2px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    .stat-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    .stat-item:last-child {
        border-bottom: none;
    }
    .stat-label, .stat-value {
        font-size: 0.9rem;
    }
    .stat-label {
        color: #666;
    }
    .stat-value {
        font-weight: bold;
    }
    .btn {
        display: inline-block;
        padding: 10px 15px;
        border-radius: 4px;
        text-align: center;
        text-decoration: none;
        color: #fff;
        font-weight: bold;
        transition: background-color 0.3s;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-bottom: 10px;
    }
    .btn-primary {
        background-color: #007bff;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-danger {
        background-color: #dc3545;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    @media (max-width: 768px) {
        .content-wrapper {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="detail-container">
    <div class="content-wrapper">
        <div>
            <div class="details-header">
                <h1>{{ $medicine->name }}</h1>
                <p class="subtitle">ID: #{{ $medicine->id }}</p>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Tên thuốc</span>
                <div class="detail-value">{{ $medicine->name }}</div>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Đơn vị tính</span>
                <div class="detail-value">{{ $medicine->unit }}</div>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Mô tả</span>
                <div class="detail-value {{ !$medicine->description ? 'empty' : '' }}">
                    {{ $medicine->description ?: 'Chưa có mô tả cho thuốc này' }}
                </div>
            </div>
        </div>

        <div class="sidebar">
            <div class="info-card">
                <h3 class="card-title">Thống kê</h3>
                <div class="stat-item">
                    <span class="stat-label">Ngày tạo</span>
                    <span class="stat-value">{{ $medicine->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Cập nhật lần cuối</span>
                    <span class="stat-value">{{ $medicine->updated_at->diffForHumans() }}</span>
                </div>
            </div>
            
            <div class="actions-card">
                <h3 class="card-title">Hành động</h3>
                <a href="{{ route('admin.medicines.edit', $medicine->id) }}" class="btn btn-primary">
                    Chỉnh sửa
                </a>
                <form action="{{ route('admin.medicines.destroy', $medicine->id) }}" 
                    method="POST" 
                    onsubmit="return confirmDelete()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm(`Bạn có chắc chắn muốn xóa thuốc "{{ $medicine->name }}"?`);
    }
</script>
@endsection