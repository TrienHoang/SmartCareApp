<?php

namespace App\Http\Controllers\admin;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\ChatTemplate;
use Illuminate\Http\Request;

class AdminChatController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::with(['latestMessage', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.chat.index', compact('sessions'));
    }

    public function show(ChatSession $session)
    {
        $messages = $session->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('admin.chat.show', compact('session', 'messages'));
    }

    public function sendMessage(Request $request, ChatSession $session)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type' => 'admin',
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

    public function templates()
    {
        $templates = ChatTemplate::orderBy('priority', 'desc')->paginate(20);
        return view('admin.chat.templates', compact('templates'));
    }

    public function createTemplate()
    {
        return view('admin.chat.create-template');
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
            'response' => 'required|string',
            'priority' => 'integer|min:0|max:10'
        ]);

        ChatTemplate::create($request->all());

        return redirect()->route('admin.chat.templates')
            ->with('success', 'Template đã được tạo thành công!');
    }
}
