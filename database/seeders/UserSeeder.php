<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '+1 234 567 890',
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '+1 234 567 891',
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '+1 234 567 892',
            'email_verified_at' => now(),
        ]);
    }
}
