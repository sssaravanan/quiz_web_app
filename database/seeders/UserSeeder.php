<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make(env('DEFAULT_PASSWORD', 'test@123')),
            ]
        );
        $admin->assignRole($adminRole);

        $user = User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make(env('DEFAULT_PASSWORD', 'test@123')),
            ]
        );
        $user->assignRole($userRole);
    }
}
