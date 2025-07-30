@extends('client.layouts.app')
@section('title', 'Mã giảm giá đặt lịch khám')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    
    .medical-gradient {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    }
    
    .card-medical {
        background: white;
        border: 1px solid #e1f5fe;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .card-medical:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #0284c7;
    }
    
    .card-medical::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #0284c7, #0ea5e9);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .card-medical:hover::before {
        transform: scaleX(1);
    }
    
    .promotion-badge {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
        position: absolute;
        top: -6px;
        right: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    
    .code-display {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 2px dashed #0284c7;
        border-radius: 8px;
        padding: 12px;
        position: relative;
    }
    
    .code-text {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 16px;
        font-weight: 700;
        color: #1e40af;
        letter-spacing: 0.5px;
    }
    
    .copy-btn {
        background: #0284c7;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 4px 6px;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .copy-btn:hover {
        background: #0369a1;
    }
    
    .medical-icon {
        width: 18px;
        height: 18px;
        color: #059669;
    }
    
    .unavailable-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        opacity: 0.6;
    }
    
    .modal-backdrop {
        backdrop-filter: blur(8px);
        background: rgba(0, 0, 0, 0.4);
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.25);
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #047857, #059669);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .pulse-dot {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: #10b981;
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .health-header {
        background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
    }
    
    .section-compact {
        padding: 2rem 0;
    }
    
    .card-compact {
        padding: 1.25rem;
    }
</style>

<div x-data="{ 
    selectedPromotion: null,
    copiedCode: '',
    showNotification: false,
    
    copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            this.copiedCode = code;
            this.showNotification = true;
            setTimeout(() => {
                this.showNotification = false;
            }, 3000);
        });
    },
    
    formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }
}" class="min-h-screen medical-gradient">

    <!-- Header Section -->
    <div class="health-header text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-white bg-opacity-20 rounded-full mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2">Mã Giảm Giá Đặt Lịch Khám</h1>
                <p class="text-blue-100 max-w-xl mx-auto">
                    Tiết kiệm chi phí khám chữa bệnh với các mã giảm giá độc quyền
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 section-compact">
        
        {{-- DANH SÁCH MÃ CÓ THỂ DÙNG --}}
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Mã Khuyến Mãi Khả Dụng</h2>
                    <p class="text-gray-600 text-sm">Áp dụng ngay để tiết kiệm chi phí khám bệnh</p>
                </div>
                <div class="bg-green-50 text-green-700 px-3 py-2 rounded-full font-semibold border border-green-200 text-sm">
                    <span class="pulse-dot inline-block w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                    {{ count($availablePromotions) }} mã khả dụng
                </div>
            </div>
            
            <div class="grid lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse ($availablePromotions as $item)
                    @php $promotion = $item['promotion']; @endphp
                    <div @click="selectedPromotion = {{ $promotion->toJson() }}"
                        class="card-medical cursor-pointer card-compact relative">
                        
                        {{-- Promotion Badge --}}
                        <div class="promotion-badge">
                            {{ $promotion->discount_percentage }}% OFF
                        </div>
                        
                        {{-- Header --}}
                        <div class="mb-4 pt-3">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $promotion->code }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $promotion->description }}</p>
                        </div>
                        
                        {{-- Promotion Code --}}
                        <div class="code-display mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="text-xs text-gray-600 mb-1">Mã khuyến mãi</div>
                                    <div class="code-text text-sm">{{ $promotion->code }}</div>
                                </div>
                                <button @click.stop="copyCode('{{ $promotion->code }}')" class="copy-btn">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        {{-- Compact Details --}}
                        <div class="space-y-2 mb-4 text-sm">
                            <div class="info-item py-1">
                                <svg class="medical-icon mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span class="text-gray-600">Giảm:</span>
                                <span class="font-semibold text-gray-900 ml-auto">{{ number_format($promotion->discount_percentage, 0) }}%</span>
                            </div>
                            
                            <div class="info-item py-1">
                                <svg class="medical-icon mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-600">Hết hạn:</span>
                                <span class="font-semibold text-gray-900 ml-auto text-xs">{{ \Carbon\Carbon::parse($promotion->valid_until)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        
                        {{-- Action Button --}}
                        <button class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold py-2.5 px-4 rounded-lg transition-colors border border-blue-200 text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Xem Chi Tiết & Sử Dụng
                        </button>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <p class="text-gray-500">Hiện tại chưa có mã giảm giá nào khả dụng</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- DANH SÁCH MÃ KHÔNG ĐỦ ĐIỀU KIỆN --}}
        @if(count($unavailablePromotions) > 0)
        <div class="mb-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-1">Mã Chưa Đủ Điều Kiện</h2>
                <p class="text-gray-600 text-sm">Các mã khuyến mãi bạn chưa thể sử dụng</p>
            </div>
            
            <div class="grid lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($unavailablePromotions as $item)
                    @php
                        $promotion = $item['promotion'];
                        $reason = $item['reason'];
                    @endphp
                    <div class="unavailable-card card-compact rounded-xl">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-500 mb-1">{{ $promotion->code }}</h3>
                            </div>
                            <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ number_format($promotion->discount_percentage, 0) }}% OFF
                            </span>
                        </div>
                        
                        <p class="text-gray-500 mb-3 text-sm line-clamp-2">{{ $promotion->description }}</p>
                        
                        <div class="bg-gray-200 border-2 border-dashed border-gray-300 rounded-lg p-3 mb-3">
                            <div class="code-text text-gray-500 text-sm">{{ $promotion->code }}</div>
                        </div>
                        
                        <div class="text-xs text-gray-500 mb-3">
                            <div class="flex justify-between mb-1">
                                <span>Hiệu lực:</span>
                                <span>{{ \Carbon\Carbon::parse($promotion->valid_from)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Hết hạn:</span>
                                <span>{{ \Carbon\Carbon::parse($promotion->valid_until)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-red-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-red-700 text-xs font-medium">{{ $reason }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- MODAL CHI TIẾT --}}
    <div x-show="selectedPromotion" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center modal-backdrop">
        <div @click.outside="selectedPromotion = null" class="modal-content max-w-xl w-full mx-4">
            
            {{-- Modal Header --}}
            <div class="health-header text-white p-6 relative">
                <button @click="selectedPromotion = null" 
                        class="absolute top-4 right-4 text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-1">Chi tiết mã khuyến mãi</h3>
                        <p class="opacity-90 text-sm">Thông tin chi tiết và cách sử dụng</p>
                    </div>
                </div>
            </div>
            
            {{-- Modal Content --}}
            <div class="p-6">
                {{-- Discount Display --}}
                <div class="text-center mb-6">
                    <div class="inline-block bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl">
                        <div class="text-2xl font-bold mb-1" x-text="selectedPromotion?.discount_percentage + '%'"></div>
                        <div class="text-xs opacity-90">GIẢM GIÁ CHI PHÍ KHÁM</div>
                    </div>
                </div>
                
                {{-- Promotion Code --}}
                <div class="code-display mb-6">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2">Mã khuyến mãi</div>
                        <div class="code-text text-xl mb-3" x-text="selectedPromotion?.code"></div>
                        <button @click="copyCode(selectedPromotion?.code)" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors inline-flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Sao Chép Mã
                        </button>
                    </div>
                </div>
                
                {{-- Description --}}
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-2">Mô tả khuyến mãi</h4>
                    <p class="text-gray-700 text-sm" x-text="selectedPromotion?.description"></p>
                </div>
                
                {{-- Details Grid --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="text-xs font-medium text-gray-600">Giảm giá</span>
                        </div>
                        <div class="text-lg font-bold text-gray-900" x-text="selectedPromotion?.discount_percentage + '%'"></div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-4 10V9M6 13h4M10 21l4-4m-4 0l4 4"></path>
                            </svg>
                            <span class="text-xs font-medium text-gray-600">Bắt đầu</span>
                        </div>
                        <div class="text-sm font-bold text-gray-900" x-text="new Date(selectedPromotion?.valid_from).toLocaleDateString('vi-VN')"></div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 col-span-2">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs font-medium text-gray-600">Hết hạn vào</span>
                        </div>
                        <div class="text-sm font-bold text-gray-900" x-text="new Date(selectedPromotion?.valid_until).toLocaleDateString('vi-VN')"></div>
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="POST" :action="`/promotions/apply/${selectedPromotion?.id}`" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-primary w-full flex items-center justify-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Áp Dụng Mã Giảm Giá
                        </button>
                    </form>
                    
                    <button @click="selectedPromotion = null"
                            class="flex-1 sm:flex-none bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors text-sm">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Copy Success Notification --}}
    <div :class="{ 'show': showNotification }" class="notification">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-sm">Đã sao chép: <strong x-text="copiedCode"></strong></span>
        </div>
    </div>
</div>
@endsection