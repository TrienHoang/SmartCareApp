<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
      <span class="app-brand-logo demo">
        <!-- SVG logo giữ nguyên -->
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">SmartCare</span>
    </a>
  </div>

  <div class="menu-divider mt-0"></div>
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    <!-- Quản lý hệ thống -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Quản lý hệ thống</span></li>

    <li class="menu-item">
      <a href="{{ route('admin.users.index') }}" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div>Người dùng</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.users.index') }}" class="menu-link">
            <div>Danh sách</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item">
      <a href="{{ route('admin.roles.index') }}" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
        <div>Vai trò</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.roles.index') }}" class="menu-link">
            <div>Danh sách</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Quản lý bác sĩ & phòng ban -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Bác sĩ & phòng ban</span></li>

    <li class="menu-item">
      <a href="{{ route('admin.doctors.index') }}" class="menu-link menu-toggle">
        <i class="menu-icon fas fa-user-md"></i>
        <div>Quản lý bác sĩ</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item"><a href="{{ route('admin.doctors.index') }}" class="menu-link">
            <div>Danh sách bác sĩ</div>
          </a></li>
        <li class="menu-item"><a href="{{ route('admin.schedules.index') }}" class="menu-link">
            <div>Lịch làm việc bác sĩ</div>
          </a></li>
      </ul>
    </li>

    <li class="menu-item">
      <a href="{{ route('admin.doctor_leaves.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-plus-medical"></i>
        <div>Lịch nghỉ bác sĩ</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="{{ route('admin.departments.index') }}" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-building-house"></i>
        <div>Quản lý phòng ban</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item"><a href="{{ route('admin.departments.index') }}" class="menu-link">
            <div>Danh sách</div>
          </a></li>
      </ul>
    </li>

    <!-- Quản lý lịch -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Lịch hẹn & làm việc</span></li>

    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-calendar"></i>
        <div>Quản lý lịch hẹn khám</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item"><a href="{{ route('admin.appointments.index') }}" class="menu-link">
            <div>Danh sách</div>
          </a></li>
        <li class="menu-item"><a href="{{ route('admin.appointments.create') }}" class="menu-link">
            <div>Thêm mới</div>
          </a></li>
      </ul>
    </li>

    <!-- Dịch vụ & đơn thuốc -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Dịch vụ & đơn thuốc</span></li>

    <li class="menu-item">
      <a href="{{ route('admin.categories.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-category"></i>
        <div>Danh mục dịch vụ</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="{{ route('admin.services.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-cog"></i>
        <div>Dịch vụ</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="{{ route('admin.prescriptions.index') }}" class="menu-link menu-toggle">
        <i class="menu-icon fas fa-file-prescription"></i>
        <div>Quản lý đơn thuốc</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item"><a href="{{ route('admin.prescriptions.index') }}" class="menu-link">
            <div>Danh sách</div>
          </a></li>
        <li class="menu-item"><a href="{{ route('admin.prescriptions.create') }}" class="menu-link">
            <div>Thêm mới</div>
          </a></li>
      </ul>
    </li>

    <!-- Tiện ích -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Tiện ích</span></li>

    <li class="menu-item">
      <a href="{{ route('admin.vouchers.index') }}" class="menu-link">
        <i class="menu-icon fas fa-ticket-alt"></i>
        <div>Vouchers</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="{{ route('admin.reviews.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-star"></i>
        <div>Đánh giá</div>
      </a>
    </li>

    <!-- Thanh toán -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Thanh toán</span></li>

    <li class="menu-item">
      <a href="{{ route('admin.payment_histories.index') }}" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-credit-card"></i>
        <div data-i18n="lịch sử thanh toán">lịch sử thanh toán</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.payment_histories.index') }}" class="menu-link">
            <div data-i18n="List">List</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Liên lạc -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Liên lạc</span></li>

    <li class="menu-item">
      <a href="app-email.html" class="menu-link">
        <i class="menu-icon tf-icons bx bx-envelope"></i>
        <div>Email</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="app-chat.html" class="menu-link">
        <i class="menu-icon tf-icons bx bx-chat"></i>
        <div>Chat</div>
      </a>
    </li>

    <!-- Tài khoản -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Tài khoản</span></li>

    <li class="menu-item">
      <a href="{{ route('logout') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-log-out"></i>
        <div>Đăng xuất</div>
      </a>
    </li>


  </ul>

</aside>