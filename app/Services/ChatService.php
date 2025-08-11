<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\ChatTemplate;
use App\Models\Service;
use Illuminate\Support\Str;

class ChatService
{
    /**
     * Tìm hoặc tạo session mới
     */
    public function getOrCreateSession($sessionId = null, $userData = [])
    {
        if ($sessionId) {
            $session = $this->resolveSession($sessionId);
            if ($session) {
                return $session;
            }
        }

        return ChatSession::create([
            'session_id'    => Str::uuid(),
            'user_id'       => auth()->id(),
            'visitor_name'  => $userData['name'] ?? null,
            'visitor_email' => $userData['email'] ?? null,
            'visitor_phone' => $userData['phone'] ?? null,
        ]);
    }

    /**
     * Gửi tin nhắn và lưu vào DB
     */
    public function sendMessage($sessionId, $message, $senderType = 'user')
    {
        $session = $this->resolveSession($sessionId, true);

        $chatMessage = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type'     => $senderType,
            'sender_id'       => $senderType === 'user' ? auth()->id() : null,
            'message'         => $message,
        ]);

        // Tự động trả lời (nếu người gửi là user)
        if ($senderType === 'user') {
            $botResponse = $this->generateBotResponse($message, $session);
            if ($botResponse) {
                ChatMessage::create([
                    'chat_session_id' => $session->id,
                    'sender_type'     => 'bot',
                    'message'         => $botResponse['message'],
                    'metadata'        => $botResponse['metadata'] ?? null,
                ]);
            }
        }

        return $chatMessage;
    }

    /**
     * Lấy tin nhắn của session
     */
    public function getSessionMessages($sessionId)
    {
        $session = $this->resolveSession($sessionId, true);

        return ChatMessage::where('chat_session_id', $session->id)
            ->with('sender')
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Tìm session theo ID numeric
     */
    public function findSessionById($id)
    {
        return ChatSession::findOrFail($id);
    }

    /**
     * Dò tìm session bằng numeric ID hoặc UUID
     */
    public function resolveSession($sessionId, $failIfNotFound = false)
    {
        $query = ChatSession::query();

        if (is_numeric($sessionId)) {
            $query->where('id', $sessionId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $failIfNotFound
            ? $query->firstOrFail()
            : $query->first();
    }

    /**
     * Trả lời tự động từ bot
     */
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
                'message'  => $template->response,
                'metadata' => [
                    'suggested_services' => $template->suggested_services,
                ]
            ];
        }

        // Phản hồi mặc định
        return [
            'message'  => "Cảm ơn bạn đã liên hệ! Tôi sẽ kết nối bạn với chuyên viên tư vấn sớm nhất.",
            'metadata' => [
                'suggested_services' => $this->getPopularServices()
            ]
        ];
    }

    /**
     * Gợi ý dịch vụ phổ biến
     */
    private function getPopularServices()
    {
        return Service::where('status', 'active')
            ->select('id', 'name', 'price', 'image')
            ->limit(3)
            ->get()
            ->toArray();
    }
}
