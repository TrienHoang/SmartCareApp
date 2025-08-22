@extends('admin.dashboard')

@section('title', 'Chi tiết phòng ban: ' . $department->name)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            {{-- Hero Header Card --}}
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 25px; overflow: hidden;">
                <div class="position-relative">
                    <div class="bg-gradient-primary text-white p-5">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle bg-white bg-opacity-20 me-4">
                                        <i class="fas fa-building fa-2x text-white"></i>
                                    </div>
                                    <div>
                                        <h2 class="mb-1 font-weight-bold">{{ $department->name }}</h2>
                                        <p class="mb-0 opacity-90 fs-5">Chi tiết thông tin phòng ban</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    @if ($department->is_active)
                                        <span class="badge bg-success bg-opacity-20 text-white px-3 py-2 rounded-pill">
                                            <i class="fas fa-check-circle me-2"></i>Đang hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-20 text-white px-3 py-2 rounded-pill">
                                            <i class="fas fa-times-circle me-2"></i>Ngừng hoạt động
                                        </span>
                                    @endif
                                    <span class="badge bg-light bg-opacity-20 text-white px-3 py-2 rounded-pill">
                                        <i class="fas fa-calendar me-2"></i>{{ $department->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                
                                @if($department->description)
                                    <p class="mb-0 opacity-90 fs-6">{{ $department->description }}</p>
                                @else
                                    <p class="mb-0 opacity-70 fs-6 fst-italic">Chưa có mô tả cho phòng ban này</p>
                                @endif
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('admin.departments.index') }}" class="btn btn-light btn-lg shadow-sm px-4 py-3" style="border-radius: 15px;">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- Decorative shapes --}}
                    <div class="position-absolute top-0 end-0 p-4" style="opacity: 0.1;">
                        <i class="fas fa-building" style="font-size: 120px;"></i>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #667eea, #764ba2);">
                        <div class="card-body text-white text-center p-4">
                            <i class="fas fa-user-md fa-3x mb-3 opacity-80"></i>
                            <h4 class="mb-1 font-weight-bold">{{ $department->doctors->count() }}</h4>
                            <p class="mb-0">Bác sĩ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #f093fb, #f5576c);">
                        <div class="card-body text-white text-center p-4">
                            <i class="fas fa-concierge-bell fa-3x mb-3 opacity-80"></i>
                            <h4 class="mb-1 font-weight-bold">{{ $department->services->where('status', 'active')->count() }}</h4>
                            <p class="mb-0">Dịch vụ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #4facfe, #00f2fe);">
                        <div class="card-body text-white text-center p-4">
                            <i class="fas fa-door-open fa-3x mb-3 opacity-80"></i>
                            <h4 class="mb-1 font-weight-bold">{{ $department->rooms->count() }}</h4>
                            <p class="mb-0">Phòng khám</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: linear-gradient(135deg, #43e97b, #38f9d7);">
                        <div class="card-body text-white text-center p-4">
                            <i class="fas fa-chart-line fa-3x mb-3 opacity-80"></i>
                            <h4 class="mb-1 font-weight-bold">100%</h4>
                            <p class="mb-0">Hiệu suất</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="row">
                {{-- Doctors Section --}}
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                        <div class="card-header border-0 bg-transparent p-4 pb-0">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                    <i class="fas fa-user-md text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 font-weight-bold text-dark">Đội ngũ bác sĩ</h5>
                                    <p class="mb-0 text-muted">{{ $department->doctors->count() }} bác sĩ đang công tác</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @forelse ($department->doctors as $index => $doctor)
                                <div class="d-flex align-items-center p-3 mb-3 bg-light rounded-3 hover-lift">
                                    <div class="avatar-circle bg-primary text-white me-3">
                                        <span class="font-weight-bold">{{ substr($doctor->user->full_name ?? 'N/A', 0, 1) }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 font-weight-bold text-dark">{{ $doctor->user->full_name ?? 'Không rõ tên' }}</h6>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-phone me-2"></i>
                                            <span>{{ $doctor->user->phone ?? 'Chưa cập nhật' }}</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-success rounded-pill px-3 py-2">{{ $doctor->position ?? 'Bác sĩ' }}</span>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Phòng ban này chưa có bác sĩ nào</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Services Section --}}
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                        <div class="card-header border-0 bg-transparent p-4 pb-0">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-warning bg-opacity-10 me-3">
                                    <i class="fas fa-concierge-bell text-warning fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 font-weight-bold text-dark">Dịch vụ khám chữa bệnh</h5>
                                    <p class="mb-0 text-muted">{{ $department->services->where('status', 'active')->count() }} dịch vụ đang hoạt động</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @forelse ($department->services->where('status', 'active') as $service)
                                <div class="service-card p-3 mb-3 border rounded-3 hover-lift">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="font-weight-bold text-dark mb-0">{{ $service->name }}</h6>
                                        <span class="badge bg-primary rounded-pill">{{ $service->duration }}min</span>
                                    </div>
                                    @if($service->description)
                                        <p class="text-muted small mb-2">{{ Str::limit($service->description, 80) }}</p>
                                    @endif
                                    <div class="d-flex align-items-center">
                                        <span class="text-success font-weight-bold me-3">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ number_format($service->price) }}đ
                                        </span>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $service->duration }} phút
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có dịch vụ nào đang hoạt động</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Rooms Section --}}
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header border-0 bg-transparent p-4 pb-0">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-info bg-opacity-10 me-3">
                                    <i class="fas fa-door-open text-info fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 font-weight-bold text-dark">Phòng khám</h5>
                                    <p class="mb-0 text-muted">{{ $department->rooms->count() }} phòng khám thuộc phòng ban</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @forelse ($department->rooms->chunk(3) as $roomChunk)
                                <div class="row mb-3">
                                    @foreach($roomChunk as $room)
                                        <div class="col-md-4 mb-3">
                                            <div class="room-card p-4 bg-light rounded-3 text-center hover-lift h-100">
                                                <i class="fas fa-door-open fa-2x text-info mb-3"></i>
                                                <h6 class="font-weight-bold text-dark mb-2">{{ $room->name }}</h6>
                                                @if($room->description)
                                                    <p class="text-muted small mb-0">{{ $room->description }}</p>
                                                @else
                                                    <p class="text-muted small mb-0 fst-italic">Chưa có mô tả</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-door-closed fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có phòng khám nào thuộc phòng ban này</p>
                                </div>
                            @endforelse
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
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .hover-lift {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .service-card {
        transition: all 0.3s ease;
        background: #f8f9fa;
        border: 1px solid #e9ecef !important;
    }
    
    .service-card:hover {
        background: white;
        border-color: #667eea !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
    }
    
    .room-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .room-card:hover {
        background: white !important;
        border-color: #17a2b8;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.15);
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
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
    
    .card:nth-child(2) { animation-delay: 0.1s; }
    .card:nth-child(3) { animation-delay: 0.2s; }
    .card:nth-child(4) { animation-delay: 0.3s; }
    
    .col-md-3:nth-child(1) .card { animation-delay: 0.1s; }
    .col-md-3:nth-child(2) .card { animation-delay: 0.2s; }
    .col-md-3:nth-child(3) .card { animation-delay: 0.3s; }
    .col-md-3:nth-child(4) .card { animation-delay: 0.4s; }
    
    .bg-opacity-20 {
        --bs-bg-opacity: 0.2;
    }
    
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
</style>

{{-- Custom JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add ripple effect to cards
        const cards = document.querySelectorAll('.hover-lift');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '';
            });
        });
        
        // Animate counters
        const counters = document.querySelectorAll('.card-body h4');
        
        counters.forEach(counter => {
            const target = parseInt(counter.innerText);
            let current = 0;
            const increment = target / 20;
            const timer = setInterval(() => {
                current += increment;
                counter.innerText = Math.ceil(current);
                if (current >= target) {
                    counter.innerText = target;
                    clearInterval(timer);
                }
            }, 50);
        });
    });
</script>
@endsection