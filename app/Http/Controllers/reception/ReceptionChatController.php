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
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('reception.chat.index', compact('sessions'));
    }

    public function show(ChatSession $session)
    {
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

        broadcast(new ChatMessageSent($request->message, $session->session_id));
        \Log::info('📡 Đã broadcast event', [
            'message' => $request->message,
            'session' => $session->session_id
        ]);

        return redirect()->back()->with('success', 'Tin nhắn đã được gửi!');
    }
}
