<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <!-- SVG logo giữ nguyên -->
                <!-- ... (giữ nguyên phần SVG) ... -->
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">SmartCare</span>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Quản lý hệ thống -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Quản lý hệ thống</span>
        </li>
        <!-- Người dùng -->
        <li class="menu-item">
            <a href="{{ route('admin.users.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div data-i18n="Users">Người dùng</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.users.index') }}" class="menu-link">
                        <div data-i18n="List">Danh sách</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Vai trò -->
        <li class="menu-item">
            <a href="{{ route('admin.roles.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                <div data-i18n="Roles">Vai trò</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.roles.index') }}" class="menu-link">
                        <div data-i18n="List">Danh sách</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Quản lý bác sĩ và phòng ban -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Quản lý bác sĩ & phòng ban</span>
        </li>
        <!-- Quản lý bác sĩ -->
        <li class="menu-item">
            <a href="{{ route('admin.doctors.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-user-md"></i>
                <div data-i18n="Quản Lý Bác Sỹ">Quản lý bác sĩ</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.doctors.index') }}" class="menu-link">
                        <div data-i18n="List">Danh sách</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Quản lý phòng ban -->
        <li class="menu-item">
            <a href="{{ route('admin.departments.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-building-house"></i>
                <div data-i18n="Quản Lý Phòng Ban">Quản lý phòng ban</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.departments.index') }}" class="menu-link">
                        <div data-i18n="List">Danh sách</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Quản lý lịch -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Quản lý lịch</span>
        </li>
        <!-- Lịch làm việc bác sĩ -->
        <li class="menu-item">
            <a href="{{ route('admin.schedules.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                <div data-i18n="Lịch làm việc bác sĩ">Lịch làm việc bác sĩ</div>
            </a>
        </li>
        <!-- Lịch nghỉ bác sĩ -->
        <li class="menu-item">
            <a href="{{ route('admin.doctor_leaves.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-plus-medical"></i>
                <div data-i18n="Lịch nghỉ bác sĩ">Lịch nghỉ bác sĩ</div>
            </a>
        </li>
        <!-- Quản lý lịch hẹn khám -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-calendar'></i>
                <div>Quản lý lịch hẹn khám</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.appointments.index') }}" class="menu-link">
                        <div data-i18n="Danh sách">Danh sách</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.appointments.create') }}" class="menu-link">
                        <div data-i18n="Danh sách">Thêm mới</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Quản lý dịch vụ và đơn thuốc -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Dịch vụ & đơn thuốc</span>
        </li>
        <!-- Danh mục dịch vụ -->
        <li class="menu-item">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div data-i18n="Danh mục dịch vụ">Danh mục dịch vụ</div>
            </a>
        </li>
        <!-- Dịch vụ -->
        <li class="menu-item">
            <a href="{{ route('admin.services.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Dịch vụ">Dịch vụ</div>
            </a>
        </li>
        <!-- Quản lý đơn thuốc -->
        <li class="menu-item">
            <a href="{{ route('admin.prescriptions.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-file-prescription"></i>
                <div data-i18n="Quản lý đơn thuốc">Quản lý đơn thuốc</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.prescriptions.index') }}" class="menu-link">
                        <div data-i18n="Danh sách">Danh sách</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.prescriptions.create') }}" class="menu-link">
                        <div data-i18n="Thêm mới">Thêm mới</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Vouchers -->
        <li class="menu-item">
            <a href="{{ route('admin.vouchers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fas fa-ticket-alt"></i>
                <div data-i18n="Vouchers">Vouchers</div>
            </a>
        </li>

        <!-- Quản lý thanh toán -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Thanh toán</span>
        </li>
        <!-- Quản lý lịch sử thanh toán -->
        <li class="menu-item">
            <a href="{{ route('admin.payments.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-wallet"></i>
                <div data-i18n="Quản Lý Lịch sử Thanh Toán">Thanh toán</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.payments.index') }}" class="menu-link">
                        <div data-i18n="List">Danh sách</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Liên lạc -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Liên lạc</span>
        </li>
        <!-- Email -->
        <li class="menu-item">
            <a href="app-email.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div data-i18n="Email">Email</div>
            </a>
        </li>
        <!-- Chat -->
        <li class="menu-item">
            <a href="app-chat.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chat"></i>
                <div data-i18n="Chat">Chat</div>
            </a>
        </li>

        <!-- Đăng xuất -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Tài khoản</span>
        </li>
        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-log-out"></i>
                <div data-i18n="Đăng xuất">Đăng xuất</div>
            </a>
        </li>
    </ul>
</aside>
