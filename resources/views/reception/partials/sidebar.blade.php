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
        {{-- Sidebar Lễ tân --}}
        @if ($user->role->name === 'receptionist')
            <li class="menu-item">
                <a href="{{ route('receptionist.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bar-chart-alt"></i>
                    <div>Check in</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Lễ tân</span>
            </li>

            <li class="menu-item">
                <a href="{{ route('receptionist.appointments.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                    <div>Quản lý lịch hẹn</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('receptionist.patients.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>Quản lý bệnh nhân</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('receptionist.doctors.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-edit"></i>
                    <div>Lịch làm việc bác sĩ</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('receptionist.chat.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-chat"></i>
                    <div>Chat</div>
                </a>
            </li>
        @endif

        {{-- Nếu là Admin --}}
        @if ($user->role->name === 'admin')
            @include('admin.partials.sidebar')
        @endif

        {{-- Nếu là Doctor --}}
        @if ($user->role->name === 'doctor')
            @include('doctor.partials.sidebar')
        @endif
    </ul>

</aside>
