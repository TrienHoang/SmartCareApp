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
    public $sender_type;
    public $sender_name;

    public function __construct($message, $sessionId, $sender_type, $sender_name)
    {
        $this->message = $message;
        $this->sessionId = $sessionId;
        $this->sender_type = $sender_type;
        $this->sender_name = $sender_name;
    }

    public function broadcastOn()
    {
        \Log::info('📡 Sending broadcast on: chat-session-' . $this->sessionId);
        return new PrivateChannel('chat-session-' . $this->sessionId);
    }

    public function broadcastAs()
    {
        return 'chat-message-sent';
    }
}
