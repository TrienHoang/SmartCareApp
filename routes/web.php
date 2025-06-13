<?php

use App\Http\Controllers\admin\DoctorLeaveController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\AppointmentController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\admin\SchedulesController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\VoucherController;
use App\Http\Controllers\admin\PrescriptionController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\FacebookController;
use App\Http\Controllers\auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ReviewController;


Route::get('/', function () {
    return view('client.home');
})->name('home');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('postLogin');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('postRegister');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Trang nhập email để gửi link
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Gửi email chứa link reset
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Trang nhập mật khẩu mới khi bấm vào link từ email
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Gửi mật khẩu mới về server để cập nhật
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// đăng nhập bằng facebook
Route::get('/auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('/auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->name('facebook.callback');

// đăng nhập bằng google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');


Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'checkAdmin'
], function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view(view: 'admin.dashboard');
    })->name('dashboard');

    // Nhóm users
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
        'middleware' => 'check_permission:view_users'
    ], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');

        Route::get('/show/{id}', [UserController::class, 'show'])->name('show');

        Route::get('/edit/{id}/edit', [UserController::class, 'edit'])
            ->middleware('check_permission:edit_users')->name('edit');

        Route::put('/edit/{id}', [UserController::class, 'update'])
            ->middleware('check_permission:edit_users')->name('update');

        Route::get('/search', [UserController::class, 'search'])->name('search');

        Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])
            ->middleware('check_permission:edit_users')->name('toggleStatus');
    });



    // Nhóm roles
    Route::group([
        'prefix' => 'roles',
        'as' => 'roles.',
        'middleware' => ['auth', 'checkAdmin']
    ], function () {
        Route::get('/', [RoleController::class, 'index'])
            ->middleware('check_permission:assign_roles')
            ->name('index');

        Route::get('/create', [RoleController::class, 'create'])
            ->middleware('check_permission:assign_roles')
            ->name('create');

        Route::post('/', [RoleController::class, 'store'])
            ->middleware('check_permission:assign_roles')
            ->name('store');

        Route::get('/{id}', [RoleController::class, 'show'])
            ->middleware('check_permission:assign_roles')
            ->name('show');

        Route::get('/{id}/edit', [RoleController::class, 'edit'])
            ->middleware('check_permission:assign_roles')
            ->name('edit');

        Route::put('/{id}', [RoleController::class, 'update'])
            ->middleware('check_permission:assign_roles')
            ->name('update');

        Route::delete('/{id}', [RoleController::class, 'destroy'])
            ->middleware('check_permission:assign_roles')
            ->name('destroy');
    });

    // Quản lý voucher
    Route::group([
        'prefix' => 'vouchers',
        'as' => 'vouchers.',
        'middleware' => 'check_permission:view_coupons'
    ], function () {
        Route::get('/', [VoucherController::class, 'index'])->name('index');


        Route::get('/create', [VoucherController::class, 'create'])
            ->middleware('check_permission:create_coupons')->name('create');


        Route::post('/create', [VoucherController::class, 'store'])
            ->middleware('check_permission:create_coupons')->name('store');


        Route::get('/edit/{id}', [VoucherController::class, 'edit'])
            ->middleware('check_permission:edit_coupons')->name('edit');


        Route::put('/edit/{id}', [VoucherController::class, 'update'])
            ->middleware('check_permission:edit_coupons')->name('update');


        Route::delete('/destroy/{id}', [VoucherController::class, 'destroy'])
            ->middleware('check_permission:delete_coupons')->name('destroy');


        Route::get('/show/{id}', [VoucherController::class, 'show'])->name('show');
    });


    // quản lý lịch làm việc
    Route::group([
        'prefix' => 'schedules',
        'as' => 'schedules.',
        'middleware' => 'check_permission:view_schedules'
    ], function () {
        Route::get('/', [SchedulesController::class, 'index'])->name('index');


        Route::get('/create', [SchedulesController::class, 'create'])
            ->middleware('check_permission:create_schedules')->name('create');


        Route::post('/create', [SchedulesController::class, 'store'])
            ->middleware('check_permission:create_schedules')->name('store');


        Route::get('/edit/{id}', [SchedulesController::class, 'edit'])
            ->middleware('check_permission:edit_schedules')->name('edit');


        Route::put('/edit/{id}', [SchedulesController::class, 'update'])
            ->middleware('check_permission:edit_schedules')->name('update');


        Route::delete('/destroy/{id}', [SchedulesController::class, 'destroy'])
            ->middleware('check_permission:delete_schedules')->name('destroy');


        Route::get('/show/{id}', [SchedulesController::class, 'show'])->name('show');
    });



    // quản lý lịch hẹn khám
    Route::group([
        'prefix' => 'appointments',
        'as' => 'appointments.',
        'middleware' => 'check_permission:view_appointments'
    ], function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');


        Route::get('/create', [AppointmentController::class, 'create'])
            ->middleware('check_permission:create_appointments')->name('create');


        Route::post('/store', [AppointmentController::class, 'store'])
            ->middleware('check_permission:create_appointments')->name('store');


        Route::get('/edit/{id}', [AppointmentController::class, 'edit'])
            ->middleware('check_permission:edit_appointments')->name('edit');


        Route::put('/update/{id}', [AppointmentController::class, 'update'])
            ->middleware('check_permission:edit_appointments')->name('update');

        Route::patch('/{id}/update-status', [AppointmentController::class, 'updateStatus'])
            ->middleware('check_permission:edit_appointments')->name('update-status');

        Route::patch('/{id}/cancel', [AppointmentController::class, 'cancel'])
            ->middleware('check_permission:cancel_appointments')->name('cancel');


        Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
    });


    // quản lý đơn thuốc
    Route::group([
        'prefix' => 'prescriptions',
        'as' => 'prescriptions.',
        'middleware' => 'check_permission:view_prescriptions'
    ], function () {
        Route::get('/', [PrescriptionController::class, 'index'])->name('index');

        Route::get('/create', [PrescriptionController::class, 'create'])
            ->middleware('check_permission:create_prescriptions')->name('create');

        Route::post('/store', [PrescriptionController::class, 'store'])
            ->middleware('check_permission:create_prescriptions')->name('store');

        Route::get('/{id}/edit', [PrescriptionController::class, 'edit'])
            ->middleware('check_permission:edit_prescriptions')->name('edit');

        Route::put('/{id}', [PrescriptionController::class, 'update'])
            ->middleware('check_permission:edit_prescriptions')->name('update');

        Route::get('/{id}', [PrescriptionController::class, 'show'])->name('show');
        Route::get('/{id}/print', [PrescriptionController::class, 'exportPdf'])->name('print');
    });


    // Nhóm quản lý bác sĩ
    Route::group([
        'prefix' => 'doctors',
        'as' => 'doctors.',
        'middleware' => 'check_permission:view_doctors'
    ], function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');

        Route::get('/create', [DoctorController::class, 'create'])
            ->middleware('check_permission:create_doctors')->name('create');

        Route::post('/store', [DoctorController::class, 'store'])
            ->middleware('check_permission:create_doctors')->name('store');

        Route::get('/edit/{doctor}', [DoctorController::class, 'edit'])
            ->middleware('check_permission:edit_doctors')->name('edit');

        Route::put('/update/{doctor}', [DoctorController::class, 'update'])
            ->middleware('check_permission:edit_doctors')->name('update');

        Route::delete('/delete/{doctor}', [DoctorController::class, 'destroy'])
            ->middleware('check_permission:delete_doctors')->name('destroy');
    });


    // Quản lý phòng ban

    Route::group([
        'prefix' => 'departments',
        'as' => 'departments.',
        'middleware' => 'check_permission:view_departments'
    ], function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::get('/create', [DepartmentController::class, 'create'])->middleware('check_permission:create_departments')->name('create');
        Route::post('/', [DepartmentController::class, 'store'])->middleware('check_permission:create_departments')->name('store');
        Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->middleware('check_permission:edit_departments')->name('edit');
        Route::put('/{department}', [DepartmentController::class, 'update'])->middleware('check_permission:edit_departments')->name('update');
        Route::delete('/{department}', [DepartmentController::class, 'destroy'])->middleware('check_permission:delete_departments')->name('destroy');
    });

    // Quản lý lịch sử thanh toán
    Route::group([
        'prefix' => 'payments',
        'as' => 'payments.',
        'middleware' => 'check_permission:view_payment_history'
    ], function () {
        Route::get('/', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('show');
    });
});



Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/users/show/{id}', [UserController::class, 'show'])->name('admin.users.show');
Route::get('admin/users/edit/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('admin/users/edit/{id}', [UserController::class, 'update'])->name('admin.users.update');
Route::get('admin/users/search', [UserController::class, 'search'])->name('admin.users.search');
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);

    // ✅ Route xử lý đổi trạng thái người dùng
    Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggleStatus');
});
// Quản lý voucher
Route::get('admin/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
Route::get('admin/vouchers/create', [VoucherController::class, 'create'])->name('admin.vouchers.create');
Route::post('admin/vouchers/create', [VoucherController::class, 'store'])->name('admin.vouchers.store');
Route::get('admin/vouchers/edit/{id}', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
Route::put('admin/vouchers/edit/{id}', [VoucherController::class, 'update'])->name('admin.vouchers.update');
Route::delete('admin/vouchers/destroy/{id}', [VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
Route::get('admin/vouchers/show/{id}', [VoucherController::class, 'show'])->name('admin.vouchers.show');
// quản lý lịch làm việc
Route::get('admin/schedules', [SchedulesController::class, 'index'])->name('admin.schedules.index');
Route::get('admin/schedules/create', [SchedulesController::class, 'create'])->name('admin.schedules.create');
Route::post('admin/schedules/create', [SchedulesController::class, 'store'])->name('admin.schedules.store');
Route::get('admin/schedules/edit/{id}', [SchedulesController::class, 'edit'])->name('admin.schedules.edit');
Route::put('admin/schedules/edit/{id}', [SchedulesController::class, 'update'])->name('admin.schedules.update');
Route::delete('admin/schedules/destroy/{id}', [SchedulesController::class, 'destroy'])->name('admin.schedules.destroy');
Route::get('admin/schedules/show/{id}', [SchedulesController::class, 'show'])->name('admin.schedules.show');
// quản lý lịch hẹn khám
// Route::prefix('admin/appointments')->name('admin.appointments.')->group(function () {
//     Route::get('/', [AppointmentController::class, 'index'])->name('index');
//     Route::get('/create', [AppointmentController::class, 'create'])->name('create');
//     Route::post('/store', [AppointmentController::class, 'store'])->name('store');
// });
// Quản lý lịch nghỉ của bác sĩ
Route::group([
    'prefix' => 'admin/doctor_leaves',
    'as' => 'admin.doctor_leaves.',
    'middleware' => 'check_permission:view_doctor_leaves'
], function () {
    Route::get('/', [DoctorLeaveController::class, 'index'])->name('index');

    Route::get('/edit/{id}', [DoctorLeaveController::class, 'edit'])
        ->middleware('check_permission:edit_doctor_leaves')->name('edit');

    Route::put('/update/{id}', [DoctorLeaveController::class, 'update'])
        ->middleware('check_permission:edit_doctor_leaves')->name('update');
});

// Quản lý danh mục dịch vụ
Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
    Route::get('/', [ServiceCategoryController::class, 'index'])->name('index');
    Route::get('/create', [ServiceCategoryController::class, 'create'])->name('create');
    Route::post('/store', [ServiceCategoryController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ServiceCategoryController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [ServiceCategoryController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [ServiceCategoryController::class, 'destroy'])->name('destroy');
    Route::get('/show/{id}', [ServiceCategoryController::class, 'show'])->name('show');
});
// Quản lý dịch vụ
Route::prefix('admin/services')->name('admin.services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/create', [ServiceController::class, 'create'])->name('create');
    Route::post('/store', [ServiceController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [ServiceController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [ServiceController::class, 'destroy'])->name('destroy');
    Route::get('/show/{id}', [ServiceController::class, 'show'])->name('show');
});

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'checkAdmin'
], function () {
    Route::group([
        'prefix' => 'reviews',
        'as' => 'reviews.',
        'middleware' => 'check_permission:view_reviews'
    ], function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/{id}', [ReviewController::class, 'show'])->name('show');
        Route::post('/{id}/toggle', [ReviewController::class, 'toggleVisibility'])
            ->middleware('check_permission:edit_reviews')->name('toggle');
    });

    // Nhóm quản lý người dùng
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
        'middleware' => ['auth', 'checkAdmin', 'check_permission:view_users']
    ], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('show');

        Route::get('/edit/{id}/edit', [UserController::class, 'edit'])
            ->middleware('check_permission:edit_users')->name('edit');

        Route::put('/edit/{id}', [UserController::class, 'update'])
            ->middleware('check_permission:edit_users')->name('update');

        Route::get('/search', [UserController::class, 'search'])->name('search');
    });


    // Nhóm quản lý danh mục dịch vụ
    Route::group([
        'prefix' => 'categories',
        'as' => 'categories.',
        'middleware' => ['auth', 'checkAdmin', 'check_permission:view_categories']
    ], function () {
        Route::get('/', [ServiceCategoryController::class, 'index'])->name('index');
        Route::get('/create', [ServiceCategoryController::class, 'create'])
            ->middleware('check_permission:create_categories')->name('create');
        Route::post('/store', [ServiceCategoryController::class, 'store'])
            ->middleware('check_permission:create_categories')->name('store');
        Route::get('/edit/{id}', [ServiceCategoryController::class, 'edit'])
            ->middleware('check_permission:edit_categories')->name('edit');
        Route::put('/edit/{id}', [ServiceCategoryController::class, 'update'])
            ->middleware('check_permission:edit_categories')->name('update');
        Route::delete('/destroy/{id}', [ServiceCategoryController::class, 'destroy'])
            ->middleware('check_permission:delete_categories')->name('destroy');
        Route::get('/show/{id}', [ServiceCategoryController::class, 'show'])->name('show');
    });


    // Nhóm quản lý dịch vụ
    Route::group([
        'prefix' => 'services',
        'as' => 'services.',
        'middleware' => ['auth', 'checkAdmin', 'check_permission:view_services']
    ], function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])
            ->middleware('check_permission:create_services')->name('create');
        Route::post('/store', [ServiceController::class, 'store'])
            ->middleware('check_permission:create_services')->name('store');
        Route::get('/edit/{id}', [ServiceController::class, 'edit'])
            ->middleware('check_permission:edit_services')->name('edit');
        Route::put('/edit/{id}', [ServiceController::class, 'update'])
            ->middleware('check_permission:edit_services')->name('update');
        Route::delete('/destroy/{id}', [ServiceController::class, 'destroy'])
            ->middleware('check_permission:delete_services')->name('destroy');
        Route::get('/show/{id}', [ServiceController::class, 'show'])->name('show');
    });
});
