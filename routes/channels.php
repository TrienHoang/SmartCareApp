<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// ✅ THÊM: Channel cho chat session
Broadcast::channel('chat-session-{sessionId}', function ($user, $sessionId) {
    // \Log::info('🔐 User trying to join channel', [
    //     'user' => $user ? $user->id : 'guest',
    //     'sessionId' => $sessionId
    // ]);

    // Chỉ cho phép nếu đã đăng nhập
    if ($user) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    return false; // Không đăng nhập thì không join được
});
