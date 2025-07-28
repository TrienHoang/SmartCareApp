@php
    use Illuminate\Support\Facades\Auth;

    /** @var \App\Models\User $user */
    $user = Auth::user();
    $unreadNotifications = $user->unreadNotifications()->count();
@endphp

<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-xxl">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="#">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">

                <!-- Quick Links (shortcuts) -->
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class='bx bx-grid-alt bx-sm'></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto">Đi nhanh</h5>
                            </div>
                        </div>
                        <div class="dropdown-shortcuts-list scrollable-container">
                            @if ($user->role->name === 'admin')
                                <div class="row row-bordered overflow-visible g-0">
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-calendar fs-4"></i></span>
                                        <a href="{{ route('admin.appointments.index') }}" class="stretched-link">Lịch hẹn</a>
                                    </div>
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-food-menu fs-4"></i></span>
                                        <a href="{{ route('orders.index') }}" class="stretched-link">Đơn hàng</a>
                                    </div>
                                </div>
                                <div class="row row-bordered overflow-visible g-0">
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-user fs-4"></i></span>
                                        <a href="{{ route('admin.users.index') }}" class="stretched-link">Người dùng</a>
                                    </div>
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-check-shield fs-4"></i></span>
                                        <a href="{{ route('admin.roles.index') }}" class="stretched-link">Phân quyền</a>
                                    </div>
                                </div>
                                <div class="row row-bordered overflow-visible g-0">
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-home-circle fs-4"></i></span>
                                        <a href="{{ route('admin.dashboard.index') }}" class="stretched-link">Thống kê</a>
                                    </div>
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-cog fs-4"></i></span>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="stretched-link">Tài khoản</a>
                                    </div>
                                </div>
                            @elseif ($user->role->name === 'doctor')
                                <div class="row row-bordered overflow-visible g-0">
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-calendar-check fs-4"></i></span>
                                        <a href="#" class="stretched-link">Lịch hẹn</a>
                                    </div>
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-file fs-4"></i></span>
                                        <a href="#" class="stretched-link">Hồ sơ</a>
                                    </div>
                                </div>
                                <div class="row row-bordered overflow-visible g-0">
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-injection fs-4"></i></span>
                                        <a href="#" class="stretched-link">Đơn thuốc</a>
                                    </div>
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i class="bx bx-cog fs-4"></i></span>
                                        <a href="#" class="stretched-link">Tài khoản</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </li>

                <!-- Notifications -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                    <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="bx bx-bell bx-sm"></i>
                        @if ($unreadNotifications)
                            <span class="badge bg-danger rounded-pill badge-notifications">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                        <li class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto">Thông báo</h5>
                                <a href="#" class="text-body">
                                    <i class="bx fs-4 bx-envelope-open"></i>
                                </a>
                            </div>
                        </li>
                        <li class="dropdown-notifications-list scrollable-container">
                            <ul class="list-group list-group-flush">
                                @forelse($user->unreadNotifications->take(5) as $notify)
                                    <li class="list-group-item dropdown-notifications-item">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $notify->data['title'] ?? 'Thông báo' }}</h6>
                                                <p class="mb-0 small">{{ $notify->data['body'] ?? '' }}</p>
                                                <small class="text-muted">{{ $notify->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">Không có thông báo mới</li>
                                @endforelse
                            </ul>
                        </li>
                        <li class="dropdown-menu-footer border-top">
                            <a href="#" class="dropdown-item d-flex justify-content-center p-3">Xem tất cả thông báo</a>
                        </li>
                    </ul>
                </li>

                <!-- User dropdown -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ $user->avatar_url ?? asset('admin/assets/img/avatars/1.png') }}" class="rounded-circle" alt="avatar">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online"><img src="{{ $user->avatar_url ?? asset('admin/assets/img/avatars/1.png') }}" class="rounded-circle"></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-medium d-block lh-1">{{ $user->full_name }}</span>
                                        <small>{{ ucfirst($user->role->name) }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li><div class="dropdown-divider"></div></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i><span class="align-middle">Đăng&nbsp;xuất</span></a>
                            <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none"></form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
