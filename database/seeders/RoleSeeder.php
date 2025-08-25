<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'description' => 'Administrator with full access'],
            ['name' => 'doctor', 'description' => 'Medical professional with patient management access'],
            ['name' => 'nurse', 'description' => 'Healthcare provider with patient care access'],
            ['name' => 'receptionist', 'description' => 'Front desk staff with appointment scheduling access'],
            ['name' => 'pharmacist', 'description' => 'Responsible for medication dispensing and management'],
            ['name' => 'patient', 'description' => 'Registered patient'],
        ]);
        // Role::factory()->count(5)->create();
    }
}

