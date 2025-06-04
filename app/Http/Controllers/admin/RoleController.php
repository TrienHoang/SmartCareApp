<?php

namespace App\Http\Controllers\admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(5  );
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->permissions()->sync($request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Tạo vai trò thành công');
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('admin.roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Kiểm tra vai trò mặc định
        if ($role->name === 'admin') {
            return redirect()->back()->with('error', 'Không thể chỉnh sửa vai trò hệ thống mặc định.');
        }

        // Kiểm tra vai trò của chính người dùng
        if ($role->users()->where('users.id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'Bạn không thể chỉnh sửa vai trò của chính mình.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions()->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Kiểm tra vai trò mặc định
        if ($role->name === 'admin') {
            return redirect()->back()->with('error', 'Không thể chỉnh sửa vai trò hệ thống mặc định.');
        }

        // Kiểm tra vai trò của chính người dùng
        if ($role->users()->where('users.id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'Bạn không thể chỉnh sửa vai trò của chính mình.');
        }

        $role->update(['name' => $request->name]);
        $role->permissions()->sync($request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Cập nhật vai trò thành công');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Kiểm tra vai trò mặc định
        if ($role->name === 'admin') {
            return redirect()->back()->with('error', 'Không thể xóa vai trò hệ thống mặc định.');
        }

        // Kiểm tra vai trò của chính người dùng
        if ($role->users()->where('users.id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'Bạn không thể xóa vai trò của chính mình.');
        }
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Xóa vai trò thành công');
    }
}
