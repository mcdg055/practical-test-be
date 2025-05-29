<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Steve Rogers',
                'email' => 'superadmin@domain.com',
                'role' => 'Super Admin',
                'password' => 'password',
            ],
            [
                'name' => 'Tony Stark',
                'email' => 'user@domain.com',
                'role' => 'User',
                'password' => 'password',
            ]
        ];

        foreach ($users as $user) {
            User::firstOrCreate([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
            ])->assignRole($user['role']);
        }
    }
}
