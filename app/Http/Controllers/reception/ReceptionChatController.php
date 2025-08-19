<?php

namespace App\Http\Controllers\reception;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;

class ReceptionChatController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::with(['latestMessage', 'user'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('is_read', 0)
                    ->whereIn('sender_type', ['user']);
            }])
            ->whereHas('messages')
            ->orderByDesc(
                ChatMessage::select('created_at')
                    ->whereColumn('chat_messages.chat_session_id', 'chat_sessions.id')
                    ->latest()
                    ->take(1)
            )
            ->paginate(20);

        return view('reception.chat.index', compact('sessions'));
    }

    public function show(ChatSession $session)
    {
        $session->messages()
            ->where('is_read', 0)
            ->whereIn('sender_type', ['user', 'bot'])
            ->update(['is_read' => 1]);

        $messages = $session->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('reception.chat.show', compact('session', 'messages'));
    }

    public function sendMessage(Request $request, ChatSession $session)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type' => 'receptionist',
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        broadcast(new ChatMessageSent($request->message, $session->session_id, 'receptionist', auth()->user()->name));
        \Log::info('📡 Đã broadcast event', [
            'message' => $request->message,
            'session' => $session->session_id
        ]);

        return redirect()->back()->with('success', 'Tin nhắn đã được gửi!');
    }
}
