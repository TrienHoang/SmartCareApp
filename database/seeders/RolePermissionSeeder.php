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
   // Gán toàn bộ quyền cho admin
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->permissions()->sync(range(1, 75));
        }

   // Gán quyền phù hợp cho bác sĩ
        $doctor = Role::where('name', 'doctor')->first();
        if ($doctor) {
            $doctorPermissionIds = [
                14,                // Schedules (xem)
                18, 20, 22, 23,    // Appointments
                28, 29, 30, 31,    // Prescriptions
                39, 40, 41,        // Medical Records
                43, 44,            // Treatment Plans
                45, 47, 74,        // Files
                57,                // Statistics
                62, 63, 64, 65     // Doctor Leaves
            ];
            $doctor->permissions()->sync($doctorPermissionIds);
        }
    }
}
