<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\WorkingSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AppointmentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $qrCodeUrl;
    protected $qrCodeFilename;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;

        // Tạo QR code
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L,
            'scale'      => 3,
            'imageBase64' => false,
        ]);

        $schedule = WorkingSchedule::with('room')
        ->where('doctor_id', $appointment->doctor_id)
        ->whereDate('day', Carbon::parse($appointment->appointment_time)->toDateString()) 
        ->first();

    $appointment->room_name = $schedule && $schedule->room
        ? $schedule->room->name
        : 'Chưa xác định';

        $qrData = $appointment->qr_code ?? 'smartcare';

        // Render ảnh QR
        $qrImage = (new QRCode($options))->render($qrData);

        // Đặt tên file
        $this->qrCodeFilename = 'qrcode_' . md5($qrData . time()) . '.png';
        $path = 'qrcodes/' . $this->qrCodeFilename;

        // Đảm bảo thư mục tồn tại
        Storage::disk('public')->makeDirectory('qrcodes');

        // Lưu file
        Storage::disk('public')->put($path, $qrImage);

        // Lấy URL công khai
        $this->qrCodeUrl = asset('storage/' . $path);
    }

    public function __destruct()
    {
        $path = 'qrcodes/' . $this->qrCodeFilename;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function build()
    {
        // Đường dẫn tới file QR code
        $qrCodePath = public_path('storage/qrcodes/' . $this->qrCodeFilename);

        // Nhúng hình ảnh inline bằng withSwiftMessage
        $cid = null;
        $this->withSwiftMessage(function ($message) use ($qrCodePath, &$cid) {
            $cid = $message->embed($qrCodePath);
        });

        return $this->subject('Xác nhận lịch hẹn - SmartCare')
            ->view('emails.appointment_confirmed')
            ->attach($qrCodePath, [
                'as' => 'qrcode.png',
                'mime' => 'image/png',
            ])
            ->with([
                'qrCodeCid' => $cid,
            ]);
    }
}
