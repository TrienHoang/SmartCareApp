<?php

if (!function_exists('getPermissionLabel')) {
    function getPermissionLabel($permission)
    {
        $labels = [

            // 1. Người dùng
            'view_users' => 'Xem người dùng',
            'create_users' => 'Thêm người dùng',
            'edit_users' => 'Sửa người dùng',
            'delete_users' => 'Xóa người dùng',
            'assign_roles' => 'Gán vai trò',

            // 2. Bác sĩ
            'view_doctors' => 'Xem bác sĩ',
            'create_doctors' => 'Thêm bác sĩ',
            'edit_doctors' => 'Sửa bác sĩ',
            'delete_doctors' => 'Xóa bác sĩ',


            // 3. Phòng ban
            'view_departments' => 'Xem phòng ban',
            'create_departments' => 'Thêm phòng ban',
            'edit_departments' => 'Sửa phòng ban',
            'delete_departments' => 'Xóa phòng ban',

            // 4. Lịch làm việc
            'view_schedules' => 'Xem lịch làm việc',
            'create_schedules' => 'Tạo lịch làm việc',
            'edit_schedules' => 'Sửa lịch làm việc',
            'delete_schedules' => 'Xóa lịch làm việc',

            // 5. Lịch hẹn
            'view_appointments' => 'Xem lịch hẹn',
            'create_appointments' => 'Tạo lịch hẹn',
            'edit_appointments' => 'Sửa lịch hẹn',
            'delete_appointments' => 'Xóa lịch hẹn',
            'approve_appointments' => 'Duyệt lịch hẹn',
            'cancel_appointments' => 'Hủy lịch hẹn',

            // 6. Dịch vụ
            'view_services' => 'Xem dịch vụ',
            'create_services' => 'Thêm dịch vụ',
            'edit_services' => 'Sửa dịch vụ',
            'delete_services' => 'Xóa dịch vụ',

            // 7. Đơn thuốc
            'view_prescriptions' => 'Xem đơn thuốc',
            'create_prescriptions' => 'Tạo đơn thuốc',
            'edit_prescriptions' => 'Sửa đơn thuốc',
            'delete_prescriptions' => 'Xóa đơn thuốc',

            // 8. Mã giảm giá
            'view_coupons' => 'Xem mã giảm giá',
            'create_coupons' => 'Thêm mã giảm giá',
            'edit_coupons' => 'Sửa mã giảm giá',
            'delete_coupons' => 'Xóa mã giảm giá',

            // 9. Đơn hàng & thanh toán
            'view_orders' => 'Xem đơn hàng',
            'manage_payments' => 'Quản lý thanh toán',
            'view_payment_history' => 'Xem lịch sử thanh toán',

            // 10. Hồ sơ bệnh án / điều trị
            'view_medical_records' => 'Xem hồ sơ bệnh án',
            'create_medical_records' => 'Thêm hồ sơ bệnh án',
            'edit_medical_records' => 'Sửa hồ sơ bệnh án',
            'delete_medical_records' => 'Xóa hồ sơ bệnh án',
            'view_treatment_plans' => 'Xem kế hoạch điều trị',
            'update_treatment_plans' => 'Cập nhật kế hoạch điều trị',

            // 11. Tài liệu / file
            'upload_files' => 'Tải lên tài liệu',
            'delete_files' => 'Xóa tài liệu',
            'view_medical_documents' => 'Xem tài liệu y tế',

            // 12. Đánh giá / phản hồi
            'view_reviews' => 'Xem đánh giá',
            'delete_reviews' => 'Xóa đánh giá',

            // 13. Hỗ trợ / thông báo
            'manage_support_content' => 'Quản lý nội dung hỗ trợ',
            'send_notifications' => 'Gửi thông báo',

            // 14. Thống kê
            'view_statistics' => 'Xem thống kê',
        ];

        return $labels[$permission] ?? $permission;
    }
}

if (!function_exists('getPermissionGroupLabel')) {
    function getPermissionGroupLabel($group)
    {
        $groups = [
            'Users' => 'Người dùng',
            'Doctors' => 'Bác sĩ',
            'Departments' => 'Phòng ban',
            'schedules' => 'Lịch làm việc',
            'appointments' => 'Lịch hẹn',
            'services' => 'Dịch vụ',
            'prescriptions' => 'Đơn thuốc',
            'coupons' => 'Mã giảm giá',
            'orders' => 'Đơn hàng & thanh toán',
            'medical_records' => 'Hồ sơ bệnh án',
            'treatment_plans' => 'Kế hoạch điều trị',
            'documents' => 'Tài liệu',
            'reviews' => 'Đánh giá',
            'support' => 'Hỗ trợ & Thông báo',
            'statistics' => 'Thống kê',
        ];

        return $labels[$group] ?? ucfirst(str_replace('_', ' ', $group));
    }
}
