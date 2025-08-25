<?php



namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class CustomResetPassword extends Notification 
{
    // use Queueable;
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

       public function via($notifiable)
    {
        return ['mail', 'database']; 
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', ['token' => $this->token, 'email' => $this->email], false));

        return (new MailMessage)
            ->subject('🔒 Yêu cầu đặt lại mật khẩu')
            ->view('emails.reset-password-custom', [
                'resetLink' => $resetUrl,
            ]);
    }
 

    public function toDatabase($notifiable)
    {
        return [
            'email' => $this->email,
            'token' => $this->token,
            'type' => 'reset_password',
            'sent_at' => now(),
        ];
    }
}

