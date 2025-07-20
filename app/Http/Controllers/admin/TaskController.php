<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TaskLog;
use App\Models\TaskComment;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Helpers\TimeHelper;

use Carbon\Carbon;



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
        $users = User::whereHas('doctor')->orderBy('full_name')->get();

        return view('admin.tasks.index', compact('tasks', 'users'));
    }


    public function create()
    {
        $users = User::whereHas('doctor')->orderBy('full_name')->get();
        return view('admin.tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $now = Carbon::now();

        // ✅ Nếu KHÔNG phải admin
        if (auth()->user()?->role?->name !== '1') {

            // ✅ Kiểm tra deadline nếu có nhập
            if ($request->filled('deadline')) {
                $deadline = Carbon::parse($request->deadline);

                // ❌ Deadline không được ở trong quá khứ
                if ($deadline->lessThan($now)) {
                    return back()->withErrors([
                        'deadline' => '❌ Deadline không được ở trong quá khứ.',
                    ])->withInput();
                }

                // ❌ Deadline phải nằm trong giờ hành chính
                if (!TimeHelper::isWithinBusinessHours($deadline)) {
                    return back()->withErrors([
                        'deadline' => '❌ Deadline phải nằm trong giờ hành chính (Thứ 2 - Thứ 6, 08:00 đến 17:00).',
                    ])->withInput();
                }
            }
        }

        // ✅ Validate dữ liệu
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'deadline'      => 'nullable|date',
            'priority'      => 'required|in:thap,trung_binh,cao',
            'assigned_to'   => 'required|array|min:1',
            'assigned_to.*' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::with('role')->find($value);
                    if (!$user || $user->role?->name !== '2') {
                        $fail('Người dùng được chọn phải là bác sĩ.');
                    }
                }
            ],
        ], [
            'title.required'        => 'Vui lòng nhập tiêu đề công việc.',
            'title.max'             => 'Tiêu đề không được vượt quá 255 ký tự.',
            'deadline.date'         => 'Deadline phải là ngày hợp lệ.',
            'priority.required'     => 'Vui lòng chọn mức độ ưu tiên.',
            'priority.in'           => 'Mức độ ưu tiên không hợp lệ.',
            'assigned_to.required'  => 'Vui lòng chọn bác sĩ được giao.',
            'assigned_to.array'     => 'Dữ liệu người được giao không hợp lệ.',
        ]);

        // ✅ Tạo công việc
        $task = Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->filled('deadline') ? Carbon::parse($request->deadline) : null,
            'created_by'  => auth()->id(),
            'priority'    => $request->priority,
        ]);

        // ✅ Giao cho các bác sĩ
        $task->assignedUsers()->sync($request->assigned_to);

        return redirect()->route('admin.tasks.index')
            ->with('success', '🎉 Đã tạo công việc và giao cho bác sĩ thành công.');
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
    $users = User::whereHas('role', function ($q) {
        $q->where('name', 'doctor'); // hoặc theo role_id nếu cần
    })->orderBy('full_name')->get();

    return view('admin.tasks.edit', compact('task', 'users'));
}


public function update(Request $request, Task $task)
{
        $now = Carbon::now();

        // ✅ Nếu KHÔNG phải admin
        if (auth()->user()?->role?->name !== '1') {

            // ✅ Kiểm tra deadline nếu có nhập
            if ($request->filled('deadline')) {
                $deadline = Carbon::parse($request->deadline);

                // ❌ Deadline không được ở trong quá khứ
                if ($deadline->lessThan($now)) {
                    return back()->withErrors([
                        'deadline' => '❌ Deadline không được ở trong quá khứ.',
                    ])->withInput();
                }

                // ❌ Deadline phải nằm trong giờ hành chính
                if (!TimeHelper::isWithinBusinessHours($deadline)) {
                    return back()->withErrors([
                        'deadline' => '❌ Deadline phải nằm trong giờ hành chính (Thứ 2 - Thứ 6, 08:00 đến 17:00).',
                    ])->withInput();
                }
            }
        }

    // ✅ Validate dữ liệu đầu vào
    $request->validate([
        'title'         => 'required|string|max:255',
        'description'   => 'nullable|string',
        'deadline'      => 'nullable|date',
        'assigned_to'   => 'required|array|min:1',
        'assigned_to.*' => [
            'required',
            'exists:users,id',
            function ($attribute, $value, $fail) {
                $user = User::with('role')->find($value);
                if (!$user || $user->role?->name !== 'doctor') {
                    $fail('👨‍⚕️ Người được giao phải là bác sĩ.');
                }
            }
        ],
        'status'        => 'required|in:moi_tao,dang_lam,hoan_thanh,tre_han',
        'priority'      => 'required|in:thap,trung_binh,cao',
    ], [
        'title.required'        => 'Vui lòng nhập tiêu đề công việc.',
        'title.max'             => 'Tiêu đề không được vượt quá 255 ký tự.',
        'deadline.date'         => 'Deadline không hợp lệ.',
        'assigned_to.required'  => 'Vui lòng chọn ít nhất một bác sĩ.',
        'assigned_to.array'     => 'Danh sách người nhận không hợp lệ.',
        'status.required'       => 'Vui lòng chọn trạng thái.',
        'status.in'             => 'Trạng thái không hợp lệ.',
        'priority.required'     => 'Vui lòng chọn mức độ ưu tiên.',
        'priority.in'           => 'Mức độ ưu tiên không hợp lệ.',
    ]);

    // 📝 Ghi log nếu thay đổi trạng thái
    if ($task->status !== $request->status) {
        TaskLog::create([
            'task_id'     => $task->id,
            'changed_by'  => $user->id,
            'from_status' => $task->status,
            'to_status'   => $request->status,
            'changed_at'  => now(),
        ]);
    }

    // ✅ Cập nhật task
    $task->update([
        'title'       => $request->title,
        'description' => $request->description,
        'deadline'    => $request->filled('deadline') ? Carbon::parse($request->deadline) : null,
        'status'      => $request->status,
        'priority'    => $request->priority,
    ]);

    // 👥 Gán lại người được giao
    $task->assignedUsers()->sync($request->assigned_to);

    return redirect()->route('admin.tasks.index')
        ->with('success', '✅ Cập nhật công việc thành công.');
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
