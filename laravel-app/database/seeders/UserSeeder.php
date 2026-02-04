<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => '一般ユーザー',
                'email' => 'user@example.com',
                'role' => User::ROLE_USER,
                'password' => Hash::make('password'),
            ],
            [
                'name' => '承認者',
                'email' => 'approver@example.com',
                'role' => User::ROLE_APPROVER,
                'password' => Hash::make('password'),
            ],
            [
                'name' => '管理者',
                'email' => 'admin@example.com',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
