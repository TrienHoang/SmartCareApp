<?php

namespace App\Http\Controllers\admin;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\ChatTemplate;
use App\Models\Service;
use App\Services\ChatService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminChatController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::with(['latestMessage', 'user'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('is_read', 0)
                    ->whereIn('sender_type', ['user', 'bot']);
            }])
            ->whereHas('messages')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.chat.index', compact('sessions'));
    }

    public function show(ChatSession $session)
    {
        // Đánh dấu tin nhắn chưa đọc thành đã đọc
        $session->messages()
            ->where('is_read', 0)
            ->whereIn('sender_type', ['user', 'bot'])
            ->update(['is_read' => 1]);
            
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

        broadcast(new ChatMessageSent($request->message, $session->session_id, 'admin', auth()->user()->name));
        \Log::info('📡 Đã broadcast event', [
            'message' => $request->message,
            'session' => $session->session_id
        ]);

        return redirect()->back()->with('success', 'Tin nhắn đã được gửi!');
    }

    public function templates()
    {
        $templates = ChatTemplate::orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.chat.templates', compact('templates'));
    }

    public function createTemplate()
    {
        return view('admin.chat.create-template');
    }

    public function storeTemplate(Request $request)
    {
        if ($request->has('suggested_services')) {
            $request->merge([
                'suggested_services' => array_map('intval', (array)$request->suggested_services)
            ]);
            // dd($request->suggested_services);
        }

        $request->validate([
            'keyword' => 'required|string|max:255|unique:chat_templates,keyword',
            'response' => 'required|string|max:2000',
            'priority' => 'integer|min:0|max:10',
            'suggested_services' => 'nullable|array',
            'suggested_services.*' => 'integer|exists:services,id'
        ], [
            'keyword.required' => 'Vui lòng nhập từ khóa.',
            'keyword.unique' => 'Từ khóa đã tồn tại.',
            'response.required' => 'Vui lòng nhập phản hồi.',
            'priority.integer' => 'Độ ưu tiên phải là một số nguyên.',
            'priority.min' => 'Độ ưu tiên phải lớn hơn hoặc bằng 0.',
            'priority.max' => 'Độ ưu tiên phải nhỏ hơn hoặc bằng 10.',
            'suggested_services.*.integer' => 'Dịch vụ gợi ý không hợp lệ.',
            'suggested_services.*.exists' => 'Dịch vụ gợi ý không tồn tại.'
        ]);

        $data = $request->only(['keyword', 'response', 'priority']);

        $data['suggested_services'] = $request->filled('suggested_services')
            ? $request->suggested_services
            : null;

        $data['is_active'] = true;

        ChatTemplate::create($data);

        return redirect()->route('admin.chat.templates')
            ->with('success', 'Template đã được tạo thành công!');
    }

    public function editTemplate(ChatTemplate $template)
    {
        $serviceIds = $template->suggested_services ?? [];

        $selectedServices = Service::whereIn('id', $serviceIds)->get(['id', 'name']);

        return view('admin.chat.edit-template', compact('template', 'selectedServices'));
    }


    public function updateTemplate(Request $request, ChatTemplate $template)
    {
        // Ép tất cả giá trị suggested_services thành integer
        if ($request->has('suggested_services')) {
            $request->merge([
                'suggested_services' => array_map('intval', (array)$request->suggested_services)
            ]);
        }

        // Validate dữ liệu
        $request->validate([
            'keyword' => 'required|string|max:255|unique:chat_templates,keyword,' . $template->id,
            'response' => 'required|string|max:2000',
            'priority' => 'integer|min:0|max:10',
            'suggested_services' => 'nullable|array',
            'suggested_services.*' => 'integer|exists:services,id'
        ], [
            'keyword.required' => 'Vui lòng nhập từ khóa.',
            'keyword.unique' => 'Từ khóa đã tồn tại.',
            'response.required' => 'Vui lòng nhập phản hồi.',
            'priority.integer' => 'Độ ưu tiên phải là một số nguyên.',
            'priority.min' => 'Độ ưu tiên phải lớn hơn hoặc bằng 0.',
            'priority.max' => 'Độ ưu tiên phải nhỏ hơn hoặc bằng 10.',
            'suggested_services.*.integer' => 'Dịch vụ gợi ý không hợp lệ.',
            'suggested_services.*.exists' => 'Dịch vụ gợi ý không tồn tại.'
        ]);

        $data = $request->only(['keyword', 'response', 'priority']);

        // Lưu trực tiếp mảng số nguyên, Laravel tự convert JSON
        $data['suggested_services'] = $request->filled('suggested_services')
            ? $request->suggested_services
            : null;

        $template->update($data);

        return redirect()->route('admin.chat.templates')
            ->with('success', 'Template đã được cập nhật thành công!');
    }

    public function destroyTemplate(ChatTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.chat.templates')
            ->with('success', 'Template đã được xóa thành công!');
    }

    public function toggleTemplate(ChatTemplate $template)
    {
        $template->update([
            'is_active' => !$template->is_active
        ]);

        $status = $template->is_active ? 'kích hoạt' : 'tạm dừng';

        return redirect()->back()
            ->with('success', "Template đã được {$status} thành công!");
    }
    public function searchServices(Request $request)
    {
        $q = (string) $request->query('q', '');

        $services = Service::query()
            ->when($q !== '', function (Builder $builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%");
            })
            ->where('status', 'active') // chỉ lấy dịch vụ đang hoạt động
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'price', 'department_id']); // lấy thêm các cột cần thiết

        return response()->json($services);
    }
}
