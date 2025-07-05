<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskLog;
use App\Models\TaskComment;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['assignedUser', 'createdBy']);

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        

        $tasks = $query->latest()->paginate(10)->withQueryString();
        $users = \App\Models\User::orderBy('full_name')->get();

        return view('admin.tasks.index', compact('tasks', 'users'));
    }


    public function create()
    {
        $users = User::all();
        return view('admin.tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'deadline'     => 'nullable|date',
            'assigned_to'  => 'required|exists:users,id',
        ], [
            'title.required'       => '📝 Vui lòng nhập tiêu đề công việc.',
            'title.max'            => '📝 Tiêu đề không được vượt quá 255 ký tự.',
            'deadline.date'        => '📅 Deadline phải là ngày hợp lệ.',
            'assigned_to.required' => '👤 Vui lòng chọn người được giao.',
            'assigned_to.exists'   => '👤 Người được giao không hợp lệ.',
        ]);

        Task::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'deadline'     => $request->deadline,
            'assigned_to'  => $request->assigned_to,
            'created_by'   => auth()->id(),
            'status'       => 'moi_tao',
            'priority'     => 'trung_binh', // default
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', '✅ Đã tạo công việc thành công!');
    }

    public function show(Task $task)
    {
        $task->load([
            'assignedUser',
            'createdBy',
            'logs.user',
        ]);

        // Lấy 5 bình luận mới nhất kèm theo user
        $comments = $task->comments()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // Tổng số bình luận để biết có cần hiển thị nút "Xem tất cả"
        $totalComments = $task->comments()->count();

        return view('admin.tasks.show', compact('task', 'comments', 'totalComments'));
    }


    public function edit(Task $task)
    {
        $users = User::all();
        return view('admin.tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'deadline'     => 'nullable|date',
            'assigned_to'  => 'required|exists:users,id',
            'status'       => 'required|in:moi_tao,dang_lam,hoan_thanh,tre_han',
            'priority'     => 'required|in:thap,trung_binh,cao',
        ], [
            'title.required'       => '📝 Vui lòng nhập tiêu đề công việc.',
            'title.max'            => '📝 Tiêu đề không được vượt quá 255 ký tự.',
            'deadline.date'        => '📅 Deadline phải là ngày hợp lệ.',
            'assigned_to.required' => '👤 Vui lòng chọn người được giao.',
            'assigned_to.exists'   => '👤 Người được giao không hợp lệ.',
            'status.required'      => '⚠️ Vui lòng chọn trạng thái công việc.',
            'status.in'            => '⚠️ Trạng thái không hợp lệ.',
            'priority.required'    => '❗ Vui lòng chọn mức độ ưu tiên.',
            'priority.in'          => '❗ Mức độ ưu tiên không hợp lệ.',
        ]);

        if ($request->status !== $task->status) {
            TaskLog::create([
                'task_id'     => $task->id,
                'changed_by'  => auth()->id(),
                'from_status' => $task->status,
                'to_status'   => $request->status,
                'changed_at'  => now(),
            ]);
        }

        $task->update([
            'title'        => $request->title,
            'description'  => $request->description,
            'deadline'     => $request->deadline,
            'assigned_to'  => $request->assigned_to,
            'status'       => $request->status,
            'priority'     => $request->priority,
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', '🛠️ Cập nhật công việc thành công!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', '🗑️ Đã xoá công việc thành công.');
    }

    public function comment(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ], [
            'comment.required' => '💬 Vui lòng nhập nội dung bình luận.',
            'comment.max'      => '💬 Bình luận không được vượt quá 1000 ký tự.',
        ]);

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return back()->with('success', '💬 Bình luận đã được gửi.');
    }
}
