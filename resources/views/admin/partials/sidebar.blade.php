<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo ">
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

        <!-- Vouchers -->
        <li class="menu-item">
            <a href="{{ route('admin.vouchers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fas fa-ticket-alt"></i> <!-- vé giảm giá -->
                <div data-i18n="Vouchers">Vouchers</div>
            </a>
        </li>

        <!-- Lịch làm việc bác sĩ -->
        <li class="menu-item">
            <a href="{{ route('admin.schedules.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                <div data-i18n="Lịch làm việc bác sĩ">Lịch làm việc bác sĩ</div>
            </a>
        </li>

        <!-- Quản lý lịch hẹn khám -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-calendar'></i>
                <div>Quản lý lịch hẹn khám</div>
                <div class="badge bg-danger rounded-pill ms-auto">4</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.appointments.index') }}" class="menu-link">
                        <div data-i18n="List">Danh sách</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.appointments.create') }}" class="menu-link">
                        <div data-i18n="Add">Thêm mới</div>
                    </a>
                </li>
            </ul>
        </li>

        


        <!-- Users -->
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

        <!-- Roles -->
        <li class="menu-item">
            <a href="{{ route('admin.roles.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-shield-quarter"></i> <!-- biểu tượng bảo mật -->
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

        <!-- Quản lý đơn thuốc -->
        <li class="menu-item">
            <a href="{{ route('admin.prescriptions.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-file-prescription"></i> <!-- icon đơn thuốc FontAwesome -->
                <div data-i18n="Quản lý đơn thuốc">Quản lý đơn thuốc</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.prescriptions.index') }}" class="menu-link">
                        <div data-i18n="List">Danh sách</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.prescriptions.create') }}" class="menu-link">
                        <div data-i18n="Add">Thêm mới</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Quản lý đơn hàng -->
        <li class="menu-item">
            <a href="{{ route('orders.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-receipt"></i> <!-- icon hóa đơn -->
                <div data-i18n="Quản lý đơn hàng">Quản lý đơn hàng</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('orders.index') }}" class="menu-link">
                        <div data-i18n="List">Danh sách</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Danh mục dịch vụ -->
        <li class="menu-item">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div data-i18n="Danh mục dịch vụ">Danh mục dịch vụ</div>
            </a>
        </li>
        <!-- dịch vụ -->
        <li class="menu-item">
            <a href="{{ route('admin.services.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Dịch vụ">Dịch vụ</div>
            </a>
        </li>

        <!-- Quản lý bác sỹ -->
        <li class="menu-item">
            <a href="{{ route('admin.doctors.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-user-md"></i>
                <div data-i18n="Quản Lý Bác Sỹ">Quản lý bác sỹ</div>
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
                <i class="menu-icon tf-icons bx bx-building-house"></i> <!-- biểu tượng phòng ban -->
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

        <!-- Quản lý lịch sử thanh toán -->
        <li class="menu-item">
            <a href="{{ route('admin.payments.index') }}" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-wallet"></i>
                <div data-i18n="Quản Lý Lịch xử Thanh Toán">Thanh toán</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.payments.index') }}" class="menu-link">
                        <div data-i18n="List">Danh sách</div>
                    </a>
                </li>

            </ul>
        </li>
        <!-- Đăng xuất -->
        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-log-out"></i>
                <div data-i18n="Đăng xuất">Đăng xuất</div>
            </a>
        </li>

        

    </ul>
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
      <a href="{{ route('admin.notifications.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-bell"></i>
        <div>Thông báo</div>
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