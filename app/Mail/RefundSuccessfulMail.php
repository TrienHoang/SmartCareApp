<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundSuccessfulMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $reason; // 🟢 thêm biến lý do

    public function __construct(Appointment $appointment, ?string $reason = null)
    {
        $this->appointment = $appointment;
        $this->reason = $reason; // 🟢 gán lý do
    }

    public function build()
    {
        return $this->subject('Hoàn tiền lịch hẹn #' . $this->appointment->id)
            ->view('emails.refund_success');
    }
}
