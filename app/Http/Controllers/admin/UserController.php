<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
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
    public function edit()
    {
        $users = User::all();
        return view('admin.users.edit', compact('users'));
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User not found');
        }

        $user->update($request->all());
        return redirect()->route('admin.user.index')->with('success', 'User updated successfully');
    }
}
