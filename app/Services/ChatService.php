<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\ChatTemplate;
use App\Models\Service;
use Illuminate\Support\Str;

class ChatService
{
    public function getOrCreateSession($sessionId = null, $userData = [])
    {
        if ($sessionId) {
            $session = ChatSession::where('session_id', $sessionId)->first();
            if ($session) {
                return $session;
            }
        }

        return ChatSession::create([
            'session_id' => Str::uuid(),
            'user_id' => auth()->id(),
            'visitor_name' => $userData['name'] ?? null,
            'visitor_email' => $userData['email'] ?? null,
            'visitor_phone' => $userData['phone'] ?? null,
        ]);
    }
    public function sendMessage($sessionId, $message, $senderType = 'user')
    {
        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();

        $chatMessage = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type' => $senderType,
            'sender_id' => $senderType === 'user' ? auth()->id() : null,
            'message' => $message,
        ]);

        // Tự động trả lời
        if ($senderType === 'user') {
            $botResponse = $this->generateBotResponse($message, $session);
            if ($botResponse) {
                ChatMessage::create([
                    'chat_session_id' => $session->id,
                    'sender_type' => 'bot',
                    'message' => $botResponse['message'],
                    'metadata' => $botResponse['metadata'] ?? null,
                ]);
            }
        }

        return $chatMessage;
    }

    private function generateBotResponse($userMessage, $session)
    {
        $message = strtolower(trim($userMessage));

        // Tìm template phù hợp
        $template = ChatTemplate::where('is_active', true)
            ->where(function ($query) use ($message) {
                $query->whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(keyword), "%")', [$message]);
            })
            ->orderBy('priority', 'desc')
            ->first();

        if ($template) {
            return [
                'message' => $template->response,
                'metadata' => [
                    'suggested_services' => $template->suggested_services,
                ]
            ];
        }

        // Phản hồi mặc định với gợi ý dịch vụ
        return [
            'message' => "Cảm ơn bạn đã liên hệ! Tôi sẽ kết nối bạn với chuyên viên tư vấn sớm nhất.",
            'metadata' => [
                'suggested_services' => $this->getPopularServices()
            ]
        ];
    }

    private function getPopularServices()
    {
        return Service::where('status', 'active')
            ->select('id', 'name', 'price', 'image')
            ->limit(3)
            ->get()
            ->toArray();
    }

    public function getSessionMessages($sessionId)
    {
        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();
        return ChatMessage::where('chat_session_id', $session->id)
            ->with('sender')
            ->orderBy('created_at')
            ->get();
    }
}
