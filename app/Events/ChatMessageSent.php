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
        return [
            new PrivateChannel('chat-session-' . $this->sessionId),
            new Channel('chat-global'),
        ];
    }

    public function broadcastAs()
    {
        return 'chat-message-sent';
    }

    public function broadcastWith()
    {
        return [
            'id' => uniqid(),
            'message' => $this->message,
            'session_id' => $this->sessionId,
            'sender_type' => $this->sender_type,
            'sender_name' => $this->sender_name,
        ];
    }
}
