<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use Illuminate\Http\Request;
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
        $session = $request = $this->chatService->getOrCreateSession(
            $request->session_id,
            $request->only(['name', 'email', 'phone'])
        );

        return response()->json([
            'success' => true,
            'session' => $session,
            'messages' => $this->chatService->getSessionMessages($session->session_id)
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $this->chatService->sendMessage(
                $request->session_id,
                $request->message,
                'user'
            );

            $messages = $this->chatService->getSessionMessages($request->session_id);

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
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
                'success' => true,
                'messages' => $messages
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Session không tồn tại'
            ], 404);
        }
    }
}
