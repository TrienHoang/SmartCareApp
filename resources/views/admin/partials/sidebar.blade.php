<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <div class="app-brand demo">
    <a href="{{ url('index.html') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <!-- SVG logo ở đây -->
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">SmartCare</span>
    </a>
  </div>

  <div class="menu-divider mt-0"></div>
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <li class="menu-item">
      <a href="app-email.html" class="menu-link">
        <i class="menu-icon tf-icons bx bx-envelope"></i>
        <div data-i18n="Email">Email</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="app-chat.html" class="menu-link">
        <i class="menu-icon tf-icons bx bx-chat"></i>
        <div data-i18n="Chat">Chat</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="{{ route('admin.vouchers.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-receipt"></i>
        <div data-i18n="Vouchers">Vouchers</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="{{ route('admin.schedules.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-plus-medical"></i>
        <div data-i18n="Lịch làm việc bác sĩ">Lịch làm việc bác sĩ</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class='menu-icon tf-icons bx bx-food-menu'></i>
        <div>Quản lý lịch hẹn khám</div>
        <div class="badge bg-danger rounded-pill ms-auto">4</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.appointments.index') }}" class="menu-link">
            <div data-i18n="List">List</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('admin.appointments.create') }}" class="menu-link">
            <div data-i18n="Add">Add</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Users</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.users.index') }}" class="menu-link">
            <div data-i18n="List">List</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Roles</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.roles.index') }}" class="menu-link">
            <div data-i18n="List">List</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon fas fa-file-medical"></i>
        <div>Quản lý đơn thuốc</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.prescriptions.index') }}" class="menu-link">
            <div data-i18n="List">List</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('admin.prescriptions.create') }}" class="menu-link">
            <div data-i18n="Add">Add</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item">
      <a href="{{ route('admin.categories.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-category"></i>
        <div data-i18n="Danh mục dịch vụ">Danh mục dịch vụ</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon fas fa-user-md"></i>
        <div>Quản lý bác sỹ</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.doctors.index') }}" class="menu-link">
            <div data-i18n="List">List</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-table"></i>
        <div>Quản lý phòng ban</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.departments.index') }}" class="menu-link">
            <div data-i18n="List">List</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-wallet"></i>
        <div>Quản lý lịch xử thanh toán</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('admin.payments.index') }}" class="menu-link">
            <div data-i18n="List">List</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('logout') }}" class="menu-link">
            <div data-i18n="Đăng xuất">Đăng xuất</div>
          </a>
        </li>
      </ul>
    </li>
  </ul>

</aside>
