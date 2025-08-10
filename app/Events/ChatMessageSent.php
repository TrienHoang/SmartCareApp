<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;
    public $sessionId;

    public function __construct($message, $sessionId)
    {
        $this->message = $message;
        $this->sessionId = (int) $sessionId;
    }

    // Private channel để match với channels.php
    public function broadcastOn()
    {
        return new PrivateChannel('chat-session-' . $this->sessionId);
    }

    // Custom event name (frontend sẽ lắng nghe tên này)
    public function broadcastAs()
    {
        return 'chat-message-sent';
    }
}
