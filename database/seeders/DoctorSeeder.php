<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\User;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctorRoleId = Role::where('name', 'doctor')->value('id');
$doctorUsers = User::where('role_id', $doctorRoleId)->get();


        foreach ($doctorUsers as $user) {
            Doctor::create([
                'user_id' => $user->id,
                'department_id' => Department::inRandomOrder()->first()->id,
                'specialization' => 'Chuyên khoa tổng quát',
                'biography' => 'Bác sĩ giàu kinh nghiệm...',
            ]);
        }
    }
}
