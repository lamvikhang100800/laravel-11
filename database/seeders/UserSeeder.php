<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {   
        
        User::create([
            'name' => 'Admin User',
            'code' => 'A001',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'address' => '123 Admin Street',
            'phone' => '1234567890',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123456'),
            'status' => 'ACT',
            'branch_id' => 1,
            'date_of_birth' => '2000-01-01',
        ]);
        User::create([
            'name' => 'Manager User',
            'code' => 'M001',
            'email' => 'manager@example.com',
            'username' => 'manager',
            'address' => '123 Staff Street',
            'phone' => '753159852',
            'email_verified_at' => now(),
            'password' => Hash::make('manager123456'),
            'status' => 'ACT',
            'branch_id' => 1,
            'date_of_birth' => '2000-01-01',
        ]);

        User::create([
            'name' => 'Staff User',
            'code' => 'S001',
            'email' => 'staff@example.com',
            'username' => 'staff',
            'address' => '123 Staff Street',
            'phone' => '0123456789',
            'email_verified_at' => now(),
            'password' => Hash::make('staff123456'),
            'status' => 'ACT',
            'branch_id' => 1,
            'date_of_birth' => '2000-01-01',
        ]);
    }
}
