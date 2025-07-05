<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role_id') && $request->role_id !== 'all') {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%");
            });
        }

        $users = $query->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User not found');
        }
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        if (Auth::check() && Auth::id() == $id) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể sửa quyền của chính mình.');
        }

        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::check() && Auth::id() == $id) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể sửa quyền của chính mình.');
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật quyền thành công.');
    }

    public function search()
    {
        $search = request()->input('search');

        $users = User::where(function ($query) use ($search) {
            $query->where('username', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })->paginate(10);

        return view('admin.users.search', compact('users'));
    }

    public function toggleStatus($id)
    {
        // Không cho thay đổi trạng thái chính mình
        if (Auth::id() == $id) {
            return back()->with('error', 'Bạn không thể thay đổi trạng thái của chính mình.');
        }

        $user = User::findOrFail($id);
        $user->status = $user->status === 'online' ? 'offline' : 'online';
        $user->save();

        return back()->with('success', 'Trạng thái người dùng đã được cập nhật.');
    }
}
