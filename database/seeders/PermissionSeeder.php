<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'view_users', 'description' => 'Xem danh sách người dùng'],
            ['name' => 'create_users', 'description' => 'Tạo người dùng mới'],
            ['name' => 'edit_users', 'description' => 'Chỉnh sửa người dùng'],
            ['name' => 'delete_users', 'description' => 'Xoá người dùng'],
            ['name' => 'assign_roles', 'description' => 'Phân quyền tài khoản'],

            ['name' => 'view_doctors', 'description' => 'Xem danh sách bác sĩ'],
            ['name' => 'create_doctors', 'description' => 'Thêm bác sĩ mới'],
            ['name' => 'edit_doctors', 'description' => 'Chỉnh sửa thông tin bác sĩ'],
            ['name' => 'delete_doctors', 'description' => 'Xoá bác sĩ'],

            ['name' => 'view_departments', 'description' => 'Xem danh sách phòng ban'],
            ['name' => 'create_departments', 'description' => 'Thêm phòng ban mới'],
            ['name' => 'edit_departments', 'description' => 'Chỉnh sửa phòng ban'],
            ['name' => 'delete_departments', 'description' => 'Xoá phòng ban'],

            ['name' => 'view_schedules', 'description' => 'Xem lịch làm việc của bác sĩ'],
            ['name' => 'create_schedules', 'description' => 'Tạo lịch làm việc'],
            ['name' => 'edit_schedules', 'description' => 'Chỉnh sửa lịch làm việc'],
            ['name' => 'delete_schedules', 'description' => 'Xoá lịch làm việc'],

            ['name' => 'view_appointments', 'description' => 'Xem lịch hẹn'],
            ['name' => 'create_appointments', 'description' => 'Đặt lịch hẹn'],
            ['name' => 'edit_appointments', 'description' => 'Chỉnh sửa lịch hẹn'],
            ['name' => 'delete_appointments', 'description' => 'Xoá lịch hẹn'],
            ['name' => 'approve_appointments', 'description' => 'Duyệt lịch hẹn'],
            ['name' => 'cancel_appointments', 'description' => 'Huỷ lịch hẹn'],

            ['name' => 'view_services', 'description' => 'Xem danh sách dịch vụ'],
            ['name' => 'create_services', 'description' => 'Thêm dịch vụ'],
            ['name' => 'edit_services', 'description' => 'Chỉnh sửa dịch vụ'],
            ['name' => 'delete_services', 'description' => 'Xoá dịch vụ'],

            ['name' => 'view_prescriptions', 'description' => 'Xem đơn thuốc'],
            ['name' => 'create_prescriptions', 'description' => 'Tạo đơn thuốc'],
            ['name' => 'edit_prescriptions', 'description' => 'Chỉnh sửa đơn thuốc'],
            ['name' => 'delete_prescriptions', 'description' => 'Xoá đơn thuốc'],

            ['name' => 'view_coupons', 'description' => 'Xem mã giảm giá'],
            ['name' => 'create_coupons', 'description' => 'Tạo mã giảm giá'],
            ['name' => 'edit_coupons', 'description' => 'Chỉnh sửa mã giảm giá'],
            ['name' => 'delete_coupons', 'description' => 'Xoá mã giảm giá'],

            ['name' => 'view_orders', 'description' => 'Xem đơn hàng'],
            ['name' => 'manage_payments', 'description' => 'Xử lý thanh toán'],
            ['name' => 'view_payment_history', 'description' => 'Xem lịch sử thanh toán'],

            ['name' => 'view_medical_records', 'description' => 'Xem hồ sơ bệnh án'],
            ['name' => 'create_medical_records', 'description' => 'Tạo hồ sơ bệnh án'],
            ['name' => 'edit_medical_records', 'description' => 'Chỉnh sửa hồ sơ bệnh án'],
            ['name' => 'delete_medical_records', 'description' => 'Xoá hồ sơ bệnh án'],
            ['name' => 'view_treatment_plans', 'description' => 'Xem kế hoạch điều trị'],
            ['name' => 'update_treatment_plans', 'description' => 'Cập nhật kế hoạch điều trị'],

            ['name' => 'upload_files', 'description' => 'Tải file lên'],
            ['name' => 'delete_files', 'description' => 'Xoá file'],
            ['name' => 'view_medical_documents', 'description' => 'Xem tài liệu y tế'],

            ['name' => 'view_reviews', 'description' => 'Xem đánh giá'],
            ['name' => 'delete_reviews', 'description' => 'Xoá đánh giá'],

            ['name' => 'manage_support_content', 'description' => 'Quản lý nội dung hỗ trợ'],
            ['name' => 'send_notifications', 'description' => 'Gửi thông báo'],

            ['name' => 'view_statistics', 'description' => 'Xem thống kê'],
        ];

        DB::table('permissions')->insert($permissions);
    }
}

