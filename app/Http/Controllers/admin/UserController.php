<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
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
        // Không cho phép người dùng chỉnh sửa quyền của chính mình
        if (auth()->id() == $id) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể sửa quyền của chính mình.');
        }

        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Không cho phép người dùng chỉnh sửa quyền của chính mình
        if (auth()->id() == $id) {
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
        $users = User::where('username', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->paginate(10);
        return view('admin.users.search', compact('users'));
    }
}
