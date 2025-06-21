<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $doctorRoleId = Role::where('name', 'doctor')->value('id');
        $patientRoleId = Role::where('name', 'patient')->value('id');

        // Tạo 10 bác sĩ
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'username' => "doctor$i",
                'email' => "doctor$i@example.com",
                'password' => Hash::make('password'),
                'full_name' => "Bác sĩ $i",
                'phone' => "09000000$i",
                'role_id' => $doctorRoleId,
                'status' => 'online',
            ]);
        }

        // Tạo 30 bệnh nhân
        for ($i = 1; $i <= 30; $i++) {
            User::create([
                'username' => "patient$i",
                'email' => "patient$i@example.com",
                'password' => Hash::make('password'),
                'full_name' => "Bệnh nhân $i",
                'phone' => "09880000$i",
                'role_id' => $patientRoleId,
                'status' => 'offline',
            ]);
        }
    }
}
