<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

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
Broadcast::channel('user-notifications.{userId}', function ($user, $userId) {
    // Chỉ cho phép user nghe channel của chính họ
    return (int) $user->id === (int) $userId;
});

