<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Users
            ['name' => 'view_users', 'description' => 'Xem danh sách người dùng', 'group' => 'Users'],
            ['name' => 'create_users', 'description' => 'Tạo người dùng mới', 'group' => 'Users'],
            ['name' => 'edit_users', 'description' => 'Chỉnh sửa người dùng', 'group' => 'Users'],
            ['name' => 'delete_users', 'description' => 'Xoá người dùng', 'group' => 'Users'],
            ['name' => 'assign_roles', 'description' => 'Phân quyền tài khoản', 'group' => 'Users'],

            // Doctors
            ['name' => 'view_doctors', 'description' => 'Xem danh sách bác sĩ', 'group' => 'Doctors'],
            ['name' => 'create_doctors', 'description' => 'Thêm bác sĩ mới', 'group' => 'Doctors'],
            ['name' => 'edit_doctors', 'description' => 'Chỉnh sửa thông tin bác sĩ', 'group' => 'Doctors'],
            ['name' => 'delete_doctors', 'description' => 'Xoá bác sĩ', 'group' => 'Doctors'],

            // Departments
            ['name' => 'view_departments', 'description' => 'Xem danh sách phòng ban', 'group' => 'Departments'],
            ['name' => 'create_departments', 'description' => 'Thêm phòng ban mới', 'group' => 'Departments'],
            ['name' => 'edit_departments', 'description' => 'Chỉnh sửa phòng ban', 'group' => 'Departments'],
            ['name' => 'delete_departments', 'description' => 'Xoá phòng ban', 'group' => 'Departments'],

            // Schedules
            ['name' => 'view_schedules', 'description' => 'Xem lịch làm việc của bác sĩ', 'group' => 'Schedules'],
            ['name' => 'create_schedules', 'description' => 'Tạo lịch làm việc', 'group' => 'Schedules'],
            ['name' => 'edit_schedules', 'description' => 'Chỉnh sửa lịch làm việc', 'group' => 'Schedules'],
            ['name' => 'delete_schedules', 'description' => 'Xoá lịch làm việc', 'group' => 'Schedules'],

            // Appointments
            ['name' => 'view_appointments', 'description' => 'Xem lịch hẹn', 'group' => 'Appointments'],
            ['name' => 'create_appointments', 'description' => 'Đặt lịch hẹn', 'group' => 'Appointments'],
            ['name' => 'edit_appointments', 'description' => 'Chỉnh sửa lịch hẹn', 'group' => 'Appointments'],
            ['name' => 'delete_appointments', 'description' => 'Xoá lịch hẹn', 'group' => 'Appointments'],
            ['name' => 'approve_appointments', 'description' => 'Duyệt lịch hẹn', 'group' => 'Appointments'],
            ['name' => 'cancel_appointments', 'description' => 'Huỷ lịch hẹn', 'group' => 'Appointments'],

            // Services
            ['name' => 'view_services', 'description' => 'Xem danh sách dịch vụ', 'group' => 'Services'],
            ['name' => 'create_services', 'description' => 'Thêm dịch vụ', 'group' => 'Services'],
            ['name' => 'edit_services', 'description' => 'Chỉnh sửa dịch vụ', 'group' => 'Services'],
            ['name' => 'delete_services', 'description' => 'Xoá dịch vụ', 'group' => 'Services'],

            // Prescriptions
            ['name' => 'view_prescriptions', 'description' => 'Xem đơn thuốc', 'group' => 'Prescriptions'],
            ['name' => 'create_prescriptions', 'description' => 'Tạo đơn thuốc', 'group' => 'Prescriptions'],
            ['name' => 'edit_prescriptions', 'description' => 'Chỉnh sửa đơn thuốc', 'group' => 'Prescriptions'],
            ['name' => 'delete_prescriptions', 'description' => 'Xoá đơn thuốc', 'group' => 'Prescriptions'],

            // Coupons
            ['name' => 'view_coupons', 'description' => 'Xem mã giảm giá', 'group' => 'Coupons'],
            ['name' => 'create_coupons', 'description' => 'Tạo mã giảm giá', 'group' => 'Coupons'],
            ['name' => 'edit_coupons', 'description' => 'Chỉnh sửa mã giảm giá', 'group' => 'Coupons'],
            ['name' => 'delete_coupons', 'description' => 'Xoá mã giảm giá', 'group' => 'Coupons'],

            // Orders & Payments
            ['name' => 'view_orders', 'description' => 'Xem đơn hàng', 'group' => 'Orders'],
            ['name' => 'manage_payments', 'description' => 'Xử lý thanh toán', 'group' => 'Payments'],
            ['name' => 'view_payment_history', 'description' => 'Xem lịch sử thanh toán', 'group' => 'Payments'],

            // Medical Records
            ['name' => 'view_medical_records', 'description' => 'Xem hồ sơ bệnh án', 'group' => 'Medical Records'],
            ['name' => 'create_medical_records', 'description' => 'Tạo hồ sơ bệnh án', 'group' => 'Medical Records'],
            ['name' => 'edit_medical_records', 'description' => 'Chỉnh sửa hồ sơ bệnh án', 'group' => 'Medical Records'],
            ['name' => 'delete_medical_records', 'description' => 'Xoá hồ sơ bệnh án', 'group' => 'Medical Records'],
            ['name' => 'view_treatment_plans', 'description' => 'Xem kế hoạch điều trị', 'group' => 'Medical Records'],
            ['name' => 'update_treatment_plans', 'description' => 'Cập nhật kế hoạch điều trị', 'group' => 'Medical Records'],

            // Files
            ['name' => 'upload_files', 'description' => 'Tải file lên', 'group' => 'Files'],
            ['name' => 'delete_files', 'description' => 'Xoá file', 'group' => 'Files'],
            ['name' => 'view_medical_documents', 'description' => 'Xem tài liệu y tế', 'group' => 'Files'],

            // Categories
            ['name' => 'view_categories', 'description' => 'Xem danh sách danh mục', 'group' => 'Categories'],
            ['name' => 'create_categories', 'description' => 'Tạo danh mục mới', 'group' => 'Categories'],
            ['name' => 'edit_categories', 'description' => 'Chỉnh sửa danh mục', 'group' => 'Categories'],
            ['name' => 'delete_categories', 'description' => 'Xoá danh mục', 'group' => 'Categories'],

            // Reviews
            ['name' => 'view_reviews', 'description' => 'Xem đánh giá', 'group' => 'Reviews'],
            ['name' => 'delete_reviews', 'description' => 'Xoá đánh giá', 'group' => 'Reviews'],
            ['name' => 'edit_reviews', 'description' => 'Chỉnh sửa đánh giá', 'group' => 'Reviews'],

            // Notifications & Content
            ['name' => 'manage_support_content', 'description' => 'Quản lý nội dung hỗ trợ', 'group' => 'Content'],
            ['name' => 'send_notifications', 'description' => 'Gửi thông báo', 'group' => 'Content'],

            // Statistics
            ['name' => 'view_statistics', 'description' => 'Xem thống kê', 'group' => 'Statistics'],

            // FAQ Management
            ['name' => 'view_faqs', 'description' => 'Xem câu hỏi thường gặp', 'group' => 'FAQ'],
            ['name' => 'create_faqs', 'description' => 'Thêm câu hỏi thường gặp', 'group' => 'FAQ'],
            ['name' => 'edit_faqs', 'description' => 'Sửa câu hỏi thường gặp', 'group' => 'FAQ'],
            ['name' => 'delete_faqs', 'description' => 'Xoá câu hỏi thường gặp', 'group' => 'FAQ'],

            // Room Management
            ['name' => 'view_rooms', 'description' => 'Xem danh sách phòng khám', 'group' => 'Rooms'],
            ['name' => 'create_rooms', 'description' => 'Thêm phòng khám mới', 'group' => 'Rooms'],
            ['name' => 'edit_rooms', 'description' => 'Chỉnh sửa phòng khám', 'group' => 'Rooms'],
            ['name' => 'delete_rooms', 'description' => 'Xoá phòng khám', 'group' => 'Rooms'],

            // Doctor Leaves
            ['name' => 'view_doctor_leaves', 'description' => 'Xem danh sách nghỉ phép', 'group' => 'Doctor Leaves'],
            ['name' => 'create_doctor_leaves', 'description' => 'Thêm lịch nghỉ phép', 'group' => 'Doctor Leaves'],
            ['name' => 'edit_doctor_leaves', 'description' => 'Sửa lịch nghỉ phép', 'group' => 'Doctor Leaves'],
            ['name' => 'delete_doctor_leaves', 'description' => 'Xoá nghỉ phép', 'group' => 'Doctor Leaves'],

            // Blogs
            ['name' => 'view_blogs', 'description' => 'Xem bài viết', 'group' => 'Blogs'],
            ['name' => 'create_blogs', 'description' => 'Thêm bài viết', 'group' => 'Blogs'],
            ['name' => 'edit_blogs', 'description' => 'Sửa bài viết', 'group' => 'Blogs'],
            ['name' => 'delete_blogs', 'description' => 'Xoá bài viết', 'group' => 'Blogs'],

            // Vouchers
            ['name' => 'view_vouchers', 'description' => 'Xem danh sách voucher', 'group' => 'Vouchers'],
            ['name' => 'create_vouchers', 'description' => 'Thêm voucher mới', 'group' => 'Vouchers'],
            ['name' => 'edit_vouchers', 'description' => 'Chỉnh sửa voucher', 'group' => 'Vouchers'],
            ['name' => 'delete_vouchers', 'description' => 'Xoá voucher', 'group' => 'Vouchers'],

            // Upload Histories
            ['name' => 'view_upload_histories', 'description' => 'Xem lịch sử upload', 'group' => 'Files'],

            // Prescription Histories
            ['name' => 'view_prescription_histories', 'description' => 'Xem lịch sử chỉnh sửa đơn thuốc', 'group' => 'Prescriptions'],

        ];

        DB::table('permissions')->insert($permissions);
    }
}
