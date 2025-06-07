<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo ">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <!-- SVG ICON GIỮ NGUYÊN -->
                <!-- ... -->
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">SmartCare</span>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Email -->
        <li class="menu-item">
            <a href="app-email.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div>Email</div>
            </a>
        </li>

        <!-- Chat -->
        <li class="menu-item">
            <a href="app-chat.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chat"></i>
                <div>Chat</div>
            </a>
        </li>

        <!-- Vouchers -->
        <li class="menu-item">
            <a href="{{ route('admin.vouchers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div>Vouchers</div>
            </a>
        </li>

        <!-- Lịch làm việc bác sĩ -->
        <li class="menu-item">
            <a href="{{ route('admin.schedules.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-plus-medical"></i>
                <div>Lịch làm việc bác sĩ</div>
            </a>
        </li>

        <!-- Quản lý lịch hẹn khám -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-food-menu"></i>
                <div>Quản lý lịch hẹn khám</div>
                <div class="badge bg-danger rounded-pill ms-auto">4</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item"><a href="{{ route('admin.appointments.index') }}" class="menu-link"><div>List</div></a></li>
                <li class="menu-item"><a href="{{ route('admin.appointments.create') }}" class="menu-link"><div>Add</div></a></li>
            </ul>
        </li>

        <!-- Users -->
        <li class="menu-item">
            <a href="{{ route('admin.users.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Users</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item"><a href="{{ route('admin.users.index') }}" class="menu-link"><div>List</div></a></li>
            </ul>
        </li>

        <!-- Roles -->
        <li class="menu-item">
            <a href="{{ route('admin.roles.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Roles</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item"><a href="{{ route('admin.roles.index') }}" class="menu-link"><div>List</div></a></li>
            </ul>
        </li>

        <!-- Quản lý đơn thuốc -->
        <li class="menu-item">
            <a href="{{ route('admin.prescriptions.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-file-medical"></i>
                <div>Quản lý đơn thuốc</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item"><a href="{{ route('admin.prescriptions.index') }}" class="menu-link"><div>List</div></a></li>
                <li class="menu-item"><a href="{{ route('admin.prescriptions.create') }}" class="menu-link"><div>Add</div></a></li>
            </ul>
        </li>

        <!-- Danh mục dịch vụ -->
        <li class="menu-item">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div>Danh mục dịch vụ</div>
            </a>
        </li>

        <!-- Quản lý bác sỹ -->
        <li class="menu-item">
            <a href="{{ route('admin.doctors.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-user-md"></i>
                <div>Quản lý bác sỹ</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item"><a href="{{ route('admin.doctors.index') }}" class="menu-link"><div>List</div></a></li>
            </ul>
        </li>

        <!-- Quản lý phòng ban -->
        <li class="menu-item">
            <a href="{{ route('admin.departments.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div>Quản lý phòng</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item"><a href="{{ route('admin.departments.index') }}" class="menu-link"><div>List</div></a></li>
            </ul>
        </li>

        <!-- Quản lý thanh toán -->
        <li class="menu-item">
            <a href="{{ route('admin.payments.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-wallet"></i>
                <div>Thanh toán</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item"><a href="{{ route('admin.payments.index') }}" class="menu-link"><div>List</div></a></li>
                <li class="menu-item"><a href="{{ route('logout') }}" class="menu-link"><div>Đăng xuất</div></a></li>
            </ul>
        </li>

        <!-- Dịch vụ -->
        <li class="menu-item">
            <a href="{{ route('admin.services.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Dịch vụ</div>
            </a>
        </li>
    </ul>
</aside>
