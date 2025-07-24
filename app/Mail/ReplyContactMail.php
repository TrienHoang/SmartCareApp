<?php

// app/Mail/ReplyContactMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReplyContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $replyMessage;

    public function __construct($name, $replyMessage)
    {
        $this->name = $name;
        $this->replyMessage = $replyMessage;
    }

    public function build()
    {
        return $this->subject('Phản hồi từ quản trị viên')
                    ->view('emails.reply_contact');
    }
}
