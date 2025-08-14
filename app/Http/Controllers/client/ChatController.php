<?php

namespace App\Http\Controllers\client;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function startSession(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $session = $this->chatService->getOrCreateSession(
            $request->session_id,
            $request->only(['name', 'email', 'phone'])
        );

        return response()->json([
            'success'  => true,
            'session'  => $session,
            'messages' => $this->chatService->getSessionMessages($session->session_id)
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required', // Có thể là ID số hoặc UUID
            'message'    => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // Lưu tin nhắn vào DB
            $this->chatService->sendMessage(
                $request->session_id,
                $request->message,
                'user'
            );

            // Lấy session từ DB (có cả ID số)
            $session = $this->chatService->resolveSession($request->session_id, true);

            broadcast(new ChatMessageSent($request->message, $session->session_id, 'user', $session->user_id));

            \Log::info('📡 Client gửi lên channel', [
                'channel' => 'chat-session-' . $session->session_id
            ]);

            $messages = $this->chatService->getSessionMessages($request->session_id);

            return response()->json([
                'success'  => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ Lỗi khi gửi tin nhắn: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại!'
            ], 500);
        }
    }

    public function getMessages(Request $request)
    {
        try {
            $messages = $this->chatService->getSessionMessages($request->session_id);

            return response()->json([
                'success'  => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Session không tồn tại'
            ], 404);
        }
    }
}
