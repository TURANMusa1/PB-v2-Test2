<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@peoplebox.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'company_id' => 1,
            'is_active' => true,
        ]);

        // Create HR user
        User::create([
            'name' => 'HR Manager',
            'email' => 'hr@peoplebox.com',
            'password' => Hash::make('password123'),
            'role' => 'hr',
            'company_id' => 1,
            'is_active' => true,
        ]);

        // Create manager user
        User::create([
            'name' => 'Department Manager',
            'email' => 'manager@peoplebox.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'company_id' => 1,
            'is_active' => true,
        ]);

        $this->command->info('Test users created successfully!');
        $this->command->info('Admin: admin@peoplebox.com / password123');
        $this->command->info('HR: hr@peoplebox.com / password123');
        $this->command->info('Manager: manager@peoplebox.com / password123');
    }
}
