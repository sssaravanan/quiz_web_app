<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make(env('DEFAULT_PASSWORD', 'test@123')),
            ]
        );

        if (! $admin->email_verified_at) {
            $admin->forceFill(['email_verified_at' => now()])->save();
        }

        if (! $admin->hasRole($adminRole->name)) {
            $admin->assignRole($adminRole);
        }

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $users = [
            ['name' => 'Regular User', 'email' => 'user@test.com'],
            ['name' => 'Priya Sharma', 'email' => 'priya.user@test.com'],
            ['name' => 'Arjun Patel', 'email' => 'arjun.user@test.com'],
            ['name' => 'Neha Verma', 'email' => 'neha.user@test.com'],
            ['name' => 'Rahul Mehta', 'email' => 'rahul.user@test.com'],
        ];

        foreach ($users as $seedUser) {
            $user = User::updateOrCreate(
                ['email' => $seedUser['email']],
                [
                    'name' => $seedUser['name'],
                    'password' => Hash::make(env('DEFAULT_PASSWORD', 'test@123')),
                ]
            );

            if (! $user->email_verified_at) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            if (! $user->hasRole($userRole->name)) {
                $user->assignRole($userRole);
            }
        }
    }
}
