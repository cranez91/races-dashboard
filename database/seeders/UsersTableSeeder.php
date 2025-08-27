<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Curotec Admin',
            'email' => 'admin@curotec.com',
            'password' => Hash::make('passwordCuro123'),
            'email_verified_at' => now(),
        ]);
    }
}
