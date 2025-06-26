@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold mt-3 ms-2">SmartCare</span>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- Dashboard --}}
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt"></i>
                <div>Thống kê</div>
            </a>
        </li>

        {{-- Nếu là Admin --}}
        @if ($user->role->name === 'admin')
            @include('admin.partials.sidebar') {{-- Bạn có thể tách phần menu admin ra file riêng --}}
        @endif

        {{-- Nếu là Doctor --}}
        @if ($user->role->name === 'doctor')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Bác sĩ</span></li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                    <div>Lịch hẹn của tôi</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div>Hồ sơ bệnh án</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-capsule"></i>
                    <div>Đơn thuốc</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>Tài khoản</div>
                </a>
            </li>
        @endif

    </ul>
</aside>
