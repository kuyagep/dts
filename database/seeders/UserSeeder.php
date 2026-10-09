<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Administrative Accounts
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('SuperAdmin');

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Admin');

        $clerk = User::firstOrCreate(
            ['email' => 'clerk@gmail.com'],
            [
                'name' => 'Receiving Clerk',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $clerk->assignRole('Clerk');

        // 2. Create Random Demo Users & Assign Default Role
        User::factory(10)->create()->each(function (User $user) {
            $user->assignRole('User');
        });
    }
}
