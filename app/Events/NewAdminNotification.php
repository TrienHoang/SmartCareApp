<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class NewAdminNotification implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $id;
  public $title;
  public $content;
  public $type;
  public $time;
  public $userId;

  public function __construct($data, $userId)
  {
    $this->id      = $data['id'];
    $this->title   = $data['title'];
    $this->content = $data['content'];
    $this->type    = $data['type'];
    $this->time    = $data['time'];
    $this->userId  = $userId;

    // ✅ THÊM LOG DEBUG
    \Log::info("🎯 Event constructor - User: $userId, Title: " . $data['title']);
  }

  public function broadcastOn()
  {
    $channel = 'user-notifications.' . $this->userId;
    \Log::info("📡 Broadcasting on channel: " . $channel);

    return new PrivateChannel($channel);
  }

  public function broadcastAs()
  {
    return 'NewAdminNotification';
  }


  public function broadcastWith()
  {
    $data = [
      'id' => $this->id,
      'title' => $this->title,
      'content' => $this->content,
      'type' => $this->type,
      'time' => $this->time,
    ];

    \Log::info("📦 Broadcasting data: ", $data);
    return $data;
  }
}
