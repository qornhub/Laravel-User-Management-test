<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the application's administrator account.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'Administrator',
                'phone_number' => '0139290436',
                'password' => Hash::make('12345'),
                'status' => 'active',
                'is_admin' => true,
            ]
        );
    }
}