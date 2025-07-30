<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\client\PrescriptionClientController;
use App\Http\Controllers\client\ClientFileController;
use App\Http\Controllers\Client\PaymentHistoryClientController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Client\PaymentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\ReviewReplyController;
use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\DoctorController;
use App\Http\Controllers\Client\AppointmentClientController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/gioi-thieu', function () {
    return view('client.about');
})->name('about');

// Dịch vụ
Route::get('/dich-vu', [ServiceController::class, 'index'])->name('client.services');
Route::get('/dich-vu/{id}', [ServiceController::class, 'show'])->name('client.services.show');
Route::get('/chi-tiet-dich-vu/{service_id}', [BookingController::class, 'show'])->name('booking.showService');
// Route::get('/dich-vu/chi-tiet/{id}', [ServiceController::class, 'detail'])->name('client.services.detail');

// Tin tức
Route::get('/tin-tuc', [NewsController::class, 'index'])->name('client.news');
Route::get('/tin-tuc/danh-muc/{id}', [NewsController::class, 'category'])->name('client.news.category');
Route::get('/tin-tuc/{slug}', [NewsController::class, 'show'])->name('client.news.show');



// liên hệ
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

Route::get('/dat-lich', function () {
    return view('client.booking');
})->name('booking');

Route::get('/lien-he', function () {
    return view('client.contact');
})->name('contact');

// Route::get('/tin-tuc', function () {
//     return view('client.news');
// })->name('news');

Route::get('/chi-tiet-tin-tuc/{id}', function ($id) {
    return view('client.news_detail', ['id' => $id]);
})->name('news_detail');

Route::get('/thong-tin-ca-nhan', function () {
    return view('client.profile');
})->name('profile');

// Route::get('/thong-tin-bac-si', function () {
//     return view('client.doctors_detail');
// })->name('doctors_detail');

Route::get('/thong-tin-bac-si/{id}', [DoctorController::class, 'show'])->name('doctor.show');



Route::middleware(['auth'])->group(function () {
    // Gửi đánh giá
    Route::post('/doctors/{doctor}/reviews', [ReviewReplyController::class, 'store'])->name('reviews.store');

    // Gửi phản hồi đánh giá
    Route::post('/reviews/{review}/replies', [ReviewReplyController::class, 'storeReply'])->name('reviews.replies.store');

    // Đánh dấu đánh giá là hữu ích
    Route::post('/reviews/{review}/useful', [ReviewReplyController::class, 'markUseful'])->name('reviews.useful');

    Route::put('/thong-tin-bac-si/{doctor}/reviews/{id}', [ReviewReplyController::class, 'update'])->name('reviews.update');
});

// Route hiển thị chi tiết bác sĩ (không yêu cầu đăng nhập)
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    // Danh sách lịch sử khám
    Route::get('/appointment-history', [AppointmentHistoryController::class, 'index'])
        ->name('appointments.history');

    // Xem chi tiết từng cuộc khám
    Route::get('/appointment-history/{id}', [AppointmentHistoryController::class, 'show'])
        ->name('appointments.detail');
});


Route::prefix('client/uploads')->name('client.uploads.')->middleware(['auth'])->group(function () {
    Route::get('/', [ClientFileController::class, 'index'])->name('index');
    Route::get('/create', [ClientFileController::class, 'create'])->name('create');
    Route::post('/store', [ClientFileController::class, 'store'])->name('store');
    Route::get('/download/{id}', [ClientFileController::class, 'download'])->name('download');
});

Route::prefix('client/payment_history')->name('client.payment_history.')->middleware(['auth'])->group(function () {
    Route::get('/', [PaymentHistoryClientController::class, 'index'])->name('index');
    Route::get('/{id}', [PaymentHistoryClientController::class, 'show'])->name('show');
});
Route::get('/test-payment', function () {
    return view('test-payment');
});

// Danh sách bình luận của người dùng (client)
Route::prefix('client/review')->name('client.review.')->middleware(['auth'])->group(function () {
    Route::get('/', [ReviewReplyController::class, 'index'])->name('index');
    Route::get('show/{id}', [ReviewReplyController::class, 'show'])->name('show');
});

// Danh sách bình luận của người dùng (client)
Route::prefix('client/review')->name('client.review.')->middleware(['auth'])->group(function () {
    Route::get('/', [ReviewReplyController::class, 'index'])->name('index');
});


Route::get('/payment/return', [BookingController::class, 'paymentReturn'])->name('payment.return');
Route::match(['get', 'post'], '/payment/ipn', [BookingController::class, 'paymentIpn'])->name('payment.ipn');

Route::middleware(['auth'])->group(function () {
    Route::post('/booking/prepare', [BookingController::class, 'prepare'])->name('booking.prepare');
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/available-dates', [BookingController::class, 'getAvailableDates'])->name('booking.available-dates');
    Route::post('/booking/slots', [BookingController::class, 'getSlots'])->name('booking.slots');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
    Route::post('/booking/confirm', [BookingController::class, 'save'])->name('booking.save');
    // Route::get('/payment/return', [BookingController::class, 'paymentReturn'])->name('payment.return');
    // Route::match(['get', 'post'], '/payment/ipn', [BookingController::class, 'paymentIpn'])->name('payment.ipn');
    Route::get('/booking/success', [BookingController::class, 'success'])->name('booking.success');
});



Route::get('/abc', function () {
    return view('client.note');
});


Route::prefix('client/prescriptions')->name('client.prescriptions.')->middleware(['auth'])->group(function () {
    Route::get('/', [PrescriptionClientController::class, 'index'])->name('index');
    Route::get('/{id}', [PrescriptionClientController::class, 'show'])->name('show');
});

Route::prefix('client/appointments')->name('client.appointments.')->middleware(['auth'])->group(function () {
    Route::get('/', [AppointmentClientController::class, 'index'])->name('index'); // Danh sách lịch hẹn
    // Sửa tên tham số từ {id} thành {appointment} để Route Model Binding hoạt động
    Route::get('/{appointment}', [AppointmentClientController::class, 'show'])->name('show'); // Chi tiết lịch hẹn
    Route::get('/{appointment}/edit', [AppointmentClientController::class, 'edit'])->name('edit'); // Sửa lịch hẹn
    Route::put('/{appointment}', [AppointmentClientController::class, 'update'])->name('update'); // Cập nhật
Route::delete('/{appointment}/cancel', [AppointmentClientController::class, 'cancel'])->name('cancel');
// Hủy lịch hẹn
});
// Sửa lại routes của bạn như sau:

Route::prefix('client/notifications')->middleware('auth')->name('client.notifications.')->group(function () {
    Route::get('/', [ClientNotificationController::class, 'index'])->name('index');
    
    // 👇 Các route tĩnh (string) phải để TRƯỚC
    Route::delete('/delete-all', [ClientNotificationController::class, 'deleteAll'])->name('deleteAll');
    Route::post('/mark-all-as-read', [ClientNotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');

    Route::delete('/{id}', [ClientNotificationController::class, 'destroy'])->name('destroy');
    Route::get('/{notification}', [ClientNotificationController::class, 'show'])->name('show');
    Route::post('/{notification}/mark-as-read', [ClientNotificationController::class, 'markAsRead'])->name('mark-as-read');
});

// giảm kịch khung
// Thêm vào file routes/web.php

Route::middleware(['auth'])->group(function () {
    // Routes cho promotion (phù hợp với view có sẵn)
    Route::get('/promotions', [PromotionController::class, 'index'])->name('client.promotions.index');
    Route::post('/promotions/apply/{promotion}', [PromotionController::class, 'apply'])->name('client.promotions.apply');
    Route::post('/promotions/remove', [PromotionController::class, 'remove'])->name('client.promotions.remove');
});






