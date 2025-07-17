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

        {{-- Nếu là Admin --}}
        @if ($user->role->name === 'admin')
            @include('admin.partials.sidebar')
        @endif

        {{-- Nếu là Doctor --}}
        @if ($user->role->name === 'doctor')
            @php
                $doctorId = $user->doctor->id;
            @endphp

            <li class="menu-item">
                <a href="{{ route('doctor.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bar-chart-alt"></i>
                    <div>Thống kê</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Bác sĩ</span></li>

            <li class="menu-item">
                <a href="{{ route('doctor.appointments.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                    <div>Lịch hẹn của tôi</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('doctor.files.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div>Quản lý file tải lên</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('doctor.treatment-plans.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-notepad"></i>
                    <div>Kế hoạch điều trị</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('doctor.leaves.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-minus"></i>
                    <div>Lịch nghỉ của bác sĩ</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('doctor.prescriptions.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-capsule"></i>
                    <div>Đơn thuốc</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('doctor.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <span>Danh sách bác sĩ</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('doctor.history.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <span>Lịch sử khám</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('doctor.reviews.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-star"></i>
                    <span>Đánh giá từ bệnh nhân</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('doctor.calendar.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                    <span>Lịch bác sĩ</span>
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
