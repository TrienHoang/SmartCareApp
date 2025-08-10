<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// ✅ THÊM: Channel cho chat session
Broadcast::channel('chat-session-{sessionId}', function ($user, $sessionId) {
    // Với private channel, phải return true hoặc user data
    \Log::info('🔐 User trying to join channel', [
        'user' => $user ? $user->id : 'guest',
        'sessionId' => $sessionId
    ]);

    // Tạm thời cho phép tất cả join để test
    return ['id' => $user ? $user->id : 'guest'];
});
