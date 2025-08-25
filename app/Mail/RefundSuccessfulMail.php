<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\WorkingSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class RefundSuccessfulMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $reason; // 🟢 thêm biến lý do

    public function __construct(Appointment $appointment, ?string $reason = null)
    {
        $this->appointment = $appointment;
        $this->reason = $reason; // 🟢 gán lý do

        $schedule = WorkingSchedule::with('room')
        ->where('doctor_id', $appointment->doctor_id)
        ->whereDate('day', Carbon::parse($appointment->appointment_time)->toDateString()) 
        ->first();

    $appointment->room_name = $schedule && $schedule->room
        ? $schedule->room->name
        : 'Chưa xác định';
    }

    public function build()
    {
        return $this->subject('Hoàn tiền lịch hẹn #' . $this->appointment->id)
            ->view('emails.refund_success');
    }
}
