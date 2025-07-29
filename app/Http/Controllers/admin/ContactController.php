<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReplyContactMail;
use App\Notifications\ContactReplyNotification;
use Illuminate\Support\Facades\Notification;


class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::where('is_hidden', false);

        if ($request->has('keyword') && $request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('title', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $contacts = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        if ($contact->status === 'unread') {
            $contact->status = 'read';
            $contact->save();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Xóa liên hệ thành công!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:unread,read,replied',
        ]);

        $contact = Contact::findOrFail($id);
        $currentStatus = $contact->status;
        $newStatus = $request->status;

        // Không cho phép chuyển trạng thái ngược lại
        $validTransitions = [
            'unread' => ['read', 'replied'],
            'read' => ['replied'],
            'replied' => [], // không được chuyển đi đâu nữa
        ];

        if (!in_array($newStatus, $validTransitions[$currentStatus])) {
            return back()->with('error', 'Không thể chuyển đổi trạng thái!');
        }

        $contact->status = $newStatus;
        $contact->save();

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }

   public function reply(Request $request, $id)
{
    $request->validate([
        'reply_message' => 'required|string|max:1000',
    ]);

    $contact = Contact::findOrFail($id);

    // Gửi email phản hồi qua notification
    $contact->notify(new ContactReplyNotification($contact, $request->reply_message));

    // Cập nhật trạng thái
    $contact->status = 'replied';
    $contact->save();

    return redirect()->route('admin.contacts.index')->with('success', 'Đã gửi phản hồi thành công!');
}

    
}
