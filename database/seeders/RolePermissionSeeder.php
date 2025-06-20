<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolePermission;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::find(1); // lấy role_id = 1

        if ($role) {
            $permissionIds = range(1, 61); // tạo mảng từ 1 đến 52
            $role->permissions()->sync($permissionIds);
        }
    }
}
