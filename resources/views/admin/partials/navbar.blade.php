@php
    use Illuminate\Support\Facades\Auth;

    /** @var \App\Models\User $admin */
    $admin = Auth::user();
    // Lấy số thông báo chưa đọc (nếu bạn dùng Laravel notifications)
    $unreadNotifications = $admin->unreadNotifications()->count();
@endphp

<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-xxl">
        <!-- Toggle nhỏ -->
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="#">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Style Switcher -->
                <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown"><i
                            class='bx bx-sm'></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                        <li><a class="dropdown-item" href="#" data-theme="light"><i
                                    class='bx bx-sun me-2'></i>Light</a></li>
                        <li><a class="dropdown-item" href="#" data-theme="dark"><i
                                    class='bx bx-moon me-2'></i>Dark</a></li>
                        <li><a class="dropdown-item" href="#" data-theme="system"><i
                                    class="bx bx-desktop me-2"></i>System</a></li>
                    </ul>
                </li>

                <!-- Quick links (tham chiếu route) -->
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside">
                        <i class='bx bx-grid-alt bx-sm'></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto">Đi nhanh</h5>
                            </div>
                        </div>
                        <div class="dropdown-shortcuts-list scrollable-container">
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i
                                            class="bx bx-calendar fs-4"></i></span>
                                    <a href="{{ route('admin.appointments.index') }}" class="stretched-link">Lịch
                                        hẹn</a>
                                </div>
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i
                                            class="bx bx-food-menu fs-4"></i></span>
                                    <a href="{{ route('orders.index') }}" class="stretched-link">Đơn hàng</a>
                                </div>
                            </div>
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i
                                            class="bx bx-user fs-4"></i></span>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="stretched-link">Người&nbsp;dùng</a>
                                </div>
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i
                                            class="bx bx-check-shield fs-4"></i></span>
                                    <a href="{{ route('admin.roles.index') }}" class="stretched-link">Phân quyền</a>
                                </div>
                            </div>
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i
                                            class="bx bx-home-circle fs-4"></i></span>
                                    <a href="{{ route('admin.dashboard.index') }}" class="stretched-link">Thống kê</a>
                                </div>
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i
                                            class="bx bx-cog fs-4"></i></span>
                                    <a href="{{ route('admin.users.edit', $admin->id) }}"
                                        class="stretched-link">Tài&nbsp;khoản</a>
                                </div>
                            </div>
                            <div class="row row-bordered overflow-visible g-0">
                                {{-- <div class="dropdown-shortcuts-item col">
                                    <a class="dropdown-item" href="{{ route('doctor.dashboard') }}">
                                        <i class="bx bx-transfer me-2"></i>
                                        <span class="align-middle">Chuyển sang Bác sĩ</span>
                                    </a>
                                </div> --}}
                                {{-- <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2"><i
                                            class="bx bx-group fs-4"></i></span>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="stretched-link">Người&nbsp;dùng</a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Notification -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger rounded-pill">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown"
                        style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                            <span>Thông báo</span>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('admin.notifications.markAllRead') }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="small text-decoration-none btn btn-link p-0 m-0">Đánh
                                        dấu đã đọc</button>
                                </form>
                            @endif
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        @forelse(auth()->user()->unreadNotifications->take(5) as $notify)
                            <li class="px-3 py-2">
                                <h6 class="mb-1 fw-bold">{{ $notify->data['title'] ?? 'Thông báo' }}</h6>
                                <p class="mb-0 small text-muted">{{ $notify->data['content'] ?? '' }}</p>
                                <small class="text-muted">{{ $notify->created_at->diffForHumans() }}</small>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @empty
                            <li class="px-3 py-2 text-center text-muted">
                                Không có thông báo mới
                            </li>
                        @endforelse

                        <li class="text-center">
                            <a href="{{ route('admin.notifications.history') }}" class="small text-decoration-none">Xem
                                tất
                                cả</a>
                        </li>
                    </ul>
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            @if ($admin->avatar)
                                <img src="{{ asset('storage/' . $admin->avatar) }}" alt="avatar"
                                    class="w-10 h-10 rounded-full object-cover shadow-lg border-2 border-white">
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg font-bold shadow-lg">
                                    {{ substr($admin->full_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            @if ($admin->avatar)
                                                <img src="{{ asset('storage/' . $admin->avatar) }}" alt="avatar"
                                                    class="w-10 h-10 rounded-full object-cover shadow-lg border-2 border-white">
                                            @else
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg font-bold shadow-lg">
                                                    {{ substr($admin->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-medium d-block lh-1">{{ $admin->full_name }}</span>
                                        <small>{{ ucfirst($admin->role->name) }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="bx bx-power-off me-2"></i><span class="align-middle">Đăng&nbsp;xuất</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
